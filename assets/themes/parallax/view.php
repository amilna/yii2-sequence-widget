<?php

use yii\helpers\Html;

$backgroundKey = $widget->backgroundKey;
$titleKey = $widget->titleKey;
$imageKey = $widget->imageKey;
$descriptionKey = $widget->descriptionKey;
$urlKey = $widget->urlKey;

$type = ['aeroplane','kite','balloon'];
echo '	<li '.(!empty($model->$urlKey)?'style="cursor:pointer" data-url="'.(substr($model->$urlKey,0,4) == "http"?$model->$urlKey:Yii::$app->urlManager->createUrl($model->$urlKey)).'"':'').'>								
			'.($widget->showText?'
			<div class="info">
				<h2>'.Html::encode($model->$titleKey).'</h2>
				<p>'.Html::encode($model->$descriptionKey).'</p>
			</div>':'').'
			<img class="sky" src="'.$model->$backgroundKey.'" />
			<img class="'.$type[$key%3].'" src="'.($model->$imageKey == null?"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=":$model->$imageKey).'" />								
		</li>';						
?>
