<?php
/**
 * @link https://github.com/amilna/yii2-sequence-widget
 * @copyright Copyright (c) 2015 Amilna
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace amilna\sequencejs;

use Yii;
use yii\helpers\Html;
use yii\base\Widget;
use yii\helpers\Json;

/**
 * Widget renders a Sequence JS widget.
 *
 * For example:
 *
 * ```php
 *  echo SequenceJs::widget([
 *		'dataProvider'=>$dataProvider, // active data provider
 *		'targetId'=>'sequence',	//id of rendered sequencejs (the container will constructed by the widget with the given id)
 *		'imageKey'=>'front_image', //model attribute to be used as image
 *		'backgroundKey'=>'image', //model attribute to be used as background
 *		'theme' => 'parallax', //available themes: default, parallax, modern
 * 
 *		'css' => 'test.css', // url of css to overide default css relative from @web	
 *  
 *		// example to overide default themes
 *		'itemView'=>function ($model, $key, $widget) {					
 *						$type = ['aeroplane','balloon','kite'];
 *						$html = '<li>
 *									<div class="info">
 *										<h2>'.$model->title.'</h2>
 *										<p>'.$model->description.'</p>
 *									</div>
 *									<img class="sky" src="'.$model->image.'" alt="Blue Sky" />
 *									<img class="'.$type[$key%3].'" src="'.$model->front_image.'" alt="Aeroplane" />
 *								</li>';
 *										
 *						return $html;
 *					}, 
 *		
 *		
 *		//	example to overide default options	more options on http://sequencejs.com
 *		'options'=>[
 *				'autoPlay'=> true,
 *				'autoPlayDelay'=> 3000,
 *				'cycle'=>true,						
 *				'nextButton'=> true,
 *				'prevButton'=> true,
 *				'preloader'=> true,
 *				'navigationSkip'=> false
 *			],
 *		
 *		//	example to use widget without active data provider (the target selector should already rendered)
 *		'targets' => [
 *			'.sequencejs' => [
 *				'autoPlay'=> true,
 *				'autoPlayDelay'=> 3000,
 *				'cycle'=>true,						
 *				'nextButton'=> true,
 *				'prevButton'=> true,
 *				'preloader'=> true,
 *				'navigationSkip'=> false
 *			],
 *		],
 *		 						
 * 	]); 
 * ```
 *
 * @author Amilna
 * @see http://www.sequencejs.com/
 * @package amilna\sequencejs
 */
class SequenceJs extends Widget
{
    
    public $dataProvider = null;
    public $itemView = null;
    public $itemPager = null;    
    public $titleKey = 'title';
    public $imageKey = 'image';
    public $descriptionKey = 'description';
    public $backgroundKey = 'background';
    public $urlKey = 'url';
    
    public $options = [
					'autoPlay'=> true,
					'nextButton'=> true,
					'prevButton'=> true,
					'pagination'=> true,
					'animateStartingFrameIn'=> true,
					'autoPlay'=> true,
					'autoPlayDelay'=> 3000,
					'preloader'=> true
				];
	
	public $targetId = 'sequence';			    
    public $showText = true;                    
    public $theme = 'default'; // available options 'parallax','default'    
    public $css = null;
    /** @var array $targets only use without active data provider*/
    public $targets = [];    
    private $bundle = null;

    public function init()
    {
        parent::init();
        $view = $this->getView();				
		
		$bundle = SequenceJsAsset::register($view);
		$this->bundle = $bundle;
        if ($this->theme !== false && $this->css == null) {
			$view->registerCssFile("{$bundle->baseUrl}/themes/{$this->theme}/style.css");
        }
        else
        {
			$view->registerCssFile("@web/{$this->css}");
		}
			
		if (count($this->dataProvider->getModels()) > 0)
        {												
			echo '<div id="'.$this->targetId.$this->dataProvider->id.'" class="sequencejs">';
			
			if (isset($this->options['prevButton']))
			{
				echo $this->options['prevButton']?'<img class="sequence-prev" src="'.$bundle->baseUrl.'/images/bt-prev.png" alt="Previous" />':'';
			}
			if (isset($this->options['nextButton']))
			{
				echo $this->options['nextButton']?'<img class="sequence-next" src="'.$bundle->baseUrl.'/images/bt-next.png" alt="Next" />':'';
			}			
			
			echo '	<ul class="sequence-canvas">';
			
			$pager = '';		
			foreach ($this->dataProvider->getModels() as $key=>$model)
			{		
				echo $this->renderItem($model,$key);			
				$pager .= $this->renderPager($model,$key);
			}	
			
			echo '	</ul>
					<div class="sequence-pagination-wrapper">
							<ul class="sequence-pagination">
								'.$pager.'
							</ul>
					</div>
				</div>';
				
			$this->targets = [
				'#'.$this->targetId.$this->dataProvider->id => $this->options,
			];	
		}
		
        if (!empty($this->targets)) {
            $script = '';
            foreach ($this->targets as $selector => $options) {
                $options = Json::encode($options);
                $script .= "
                var sequence = $('$selector').sequence($options).data('sequence');
                $('.sequence-canvas li').click(function(){					
					if (typeof $(this).attr('data-url') !== 'undefined') {
						window.location = $(this).attr('data-url');	
					}
				});	
                /*sequence.afterLoaded = function(){
					$('.sequence-prev, .sequence-next').fadeIn(500);
				}*/
                " . PHP_EOL;
                
            }
            $view->registerJs($script);
        }
        
    }
    
    public function renderPager($model, $key)
    {
        if ($this->itemPager === null) {
            $content = '<li>'.$key.'</li>';
        } elseif (is_string($this->itemPager)) {
            $content = $this->getView()->render($this->itemView, array_merge([                
                'model' => $model,                
                'key' => $key,
                'widget' => $this,
            ], $this->viewParams));
        } else {
            $content = call_user_func($this->itemPager, $model, $key, $this);
        }
        return $content;        
    }
    
    public function renderItem($model, $key)
    {
        if ($this->itemView === null) {
			$backgroundKey = $this->backgroundKey;
			$titleKey = $this->titleKey;
			$imageKey = $this->imageKey;
			$descriptionKey = $this->descriptionKey;
			$urlKey = $this->urlKey;			           
									
			$content = $this->render('@webroot/assets/'.basename($this->bundle->baseUrl).'/themes/'.$this->theme.'/view',['model'=>$model,'key'=>$key,'widget'=>$this]);
						
        } elseif (is_string($this->itemView)) {
            $content = $this->getView()->render($this->itemView, array_merge([
                'model' => $model,
                'key' => $key,                
                'widget' => $this,
            ], $this->viewParams));
        } else {
            $content = call_user_func($this->itemView, $model, $key, $this);
        }
        
        return $content;       
    }
}
