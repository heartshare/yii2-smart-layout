# Yii2 Smart Layout behavior

[Yii2](http://www.yiiframework.com) Smart Layout behavior makes the theming of your application so simple

## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require uniqby/yii2-smart-layout "dev-master"
```

or add

```
"uniqby/yii2-smart-layout": "dev-master"
```

to the require section of your ```composer.json```

## Usage

Configure Smart Layout behavior in common config:

```php
'view' => [
	'as smartLayout' => [
    	'class' => \uniqby\smartLayout\Behavior::className(),
	]
]
```



## Info

Component searches for the layout file relative to the current theme, module, controller and action.

```php
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
```

## Author

[Alexander Sazanovich](https://uniq.by/), e-mail: [alexander@uniq.by](mailto:alexander@uniq.by)