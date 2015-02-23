<?php

use yii\helpers\Html;

$backgroundKey = $widget->backgroundKey;
$titleKey = $widget->titleKey;
$imageKey = $widget->imageKey;
$descriptionKey = $widget->descriptionKey;
$urlKey = $widget->urlKey;

$widget->itemPager = function ($model, $key, $widget) {											
						$html = '<li><img src="'.$model->image.'" alt="'.$key.'" style="max-width:40px;max-height:40px;"/></li>';										
						return $html;
					};


echo '	<li style="background-image:url('.$model->$backgroundKey.');'.(!empty($model->$urlKey)?'cursor:pointer;':'').'" '.(!empty($model->$urlKey)?'data-url="'.(substr($model->$urlKey,0,4) == "http"?$model->$urlKey:Yii::$app->urlManager->createUrl($model->$urlKey)).'"':'').'>
			'.($widget->showText?'			
			<h2 class="title">'.Html::encode($model->$titleKey).'</h2>						
			<h3 class="subtitle">'.Html::encode($model->$descriptionKey).'</h3>':'').'
			<img class="model" src="'.($model->$imageKey == null?"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=":$model->$imageKey).'" />								
		</li>';						
?>
