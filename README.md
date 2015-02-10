Sequence JS Widget for Yii2
========================
A customizable sequencejs plugin for Yii2 based on [Sequencejs](http://www.sequencejs.com/).

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require "amilna/yii2-sequence-widget" "*"
```

or add

```json
"amilna/yii2-sequence-widget" : "*"
```
to the require section of your application's `composer.json` file.

Since this extensions stil in dev stages, be sure also add following line in `composer.json` file.


Usage
-----
In view:

```php

use amilna\sequencejs\SequenceJs;

echo SequenceJs::widget([
		'dataProvider'=>$dataProvider, // active data provider
		'targetId'=>'sequence',	//id of rendered sequencejs (the container will constructed by the widget with the given id)
		'imageKey'=>'front_image', //model attribute to be used as image
		'backgroundKey'=>'image', //model attribute to be used as background
		'theme' => 'parallax', //available themes: default, parallax, modern
 
 		'css' => 'test.css', // url of css to overide default css relative from @web	
  
		// example to overide default themes
		'itemView'=>function ($model, $key, $widget) {					
						$type = ['aeroplane','balloon','kite'];
						$html = '<li>
									<div class="info">
										<h2>'.$model->title.'</h2>
										<p>'.$model->description.'</p>
									</div>
									<img class="sky" src="'.$model->image.'" alt="Blue Sky" />
									<img class="'.$type[$key%3].'" src="'.$model->front_image.'" alt="Aeroplane" />
								</li>';
										
						return $html;
					}, 
		
		
		//	example to overide default options	more options on http://sequencejs.com
		'options'=>[
				'autoPlay'=> true,
				'autoPlayDelay'=> 3000,
				'cycle'=>true,						
				'nextButton'=> true,
				'prevButton'=> true,
				'preloader'=> true,
				'navigationSkip'=> false
			],
		
		//	example to use widget without active data provider (the target selector should already rendered)
		'targets' => [
			'.sequencejs' => [
				'autoPlay'=> true,
				'autoPlayDelay'=> 3000,
				'cycle'=>true,						
				'nextButton'=> true,
				'prevButton'=> true,
				'preloader'=> true,
				'navigationSkip'=> false
			],
		],
		 						
 	]); 
```