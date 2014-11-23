<?php

namespace uniqby\smartLayout;

use Yii;
use yii\base\View;
use yii\caching\Cache;
use yii\web\Controller;

/**
 * @author ElisDN <mail@elisdn.ru>, Yii2.0 adapter v.kriuchkov
 * @link http://www.elisdn.ru
 */
class Behavior extends \yii\base\Behavior
{
    public $useCache = true;
    public $cacheDuration = 86400;

    public function events()
    {
        return [
            View::EVENT_BEFORE_RENDER => 'initialize',
        ];
    }

    public function init()
    {
        parent::init();

        Yii::trace('Initializing SmartLayoutBehavior', __METHOD__);

        $this->useCache = $this->useCache && Yii::$app->cache instanceof Cache;
    }


    public function initialize()
    {
        /** @var Controller $controller */
        $controller = $this->owner->context;
        if (!($controller instanceof Controller)) {
            return false;
        }

        if (!empty($controller->layout)) {
            return false;
        }

        $moduleId = $controller->module !== null ? $controller->module->id : null;
        $controllerId = $controller->id;
        $actionId = $controller->action->id;

        $cacheKey = __CLASS__ . "_{$moduleId}_{$controllerId}_{$actionId}";

        if ($this->useCache && false !== ($layout = Yii::$app->cache->get($cacheKey))) {
            $controller->layout = $layout;
            Yii::trace('Layout applied from cache:' . "\n" . $controller->layout, __METHOD__);
        } else {
            $layouts = [];
            $pathMaps = $controller->view->theme->pathMap;
            if (is_array($pathMaps) && !empty($pathMaps)) {
                foreach ($pathMaps as $path) {
                    if ($moduleId !== null) {
                        $layouts[] = "{$path}/{$moduleId}/layouts/{$controllerId}_{$actionId}";
                        $layouts[] = "{$path}/{$moduleId}/layouts/{$controllerId}";
                        $layouts[] = "{$path}/{$moduleId}/layouts/main";
                    } else {
                        $layouts[] = "{$path}/layouts/{$controllerId}_{$actionId}";
                        $layouts[] = "{$path}/layouts/{$controllerId}";
                        $layouts[] = "{$path}/layouts/main";
                    }
                }
            }

            foreach ($layouts as $layout) {
                $layoutPath = Yii::getAlias($layout . '.' . Yii::$app->getView()->defaultExtension);
                if (file_exists($layoutPath)) {
                    $controller->layout = $layout;
                    if ($this->useCache) {
                        Yii::$app->cache->set($cacheKey, $controller->layout, $this->cacheDuration);
                    }

                    break;
                }
            }

            Yii::trace('Layout applied:' . "\n" . $controller->layout, __METHOD__);
        }
    }
}