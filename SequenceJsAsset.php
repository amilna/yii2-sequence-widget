<?php
/**
 * @link https://github.com/amilna/yii2-sequence-widget
 * @copyright Copyright (c) 2015 Amilna
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace amilna\sequencejs;

use Yii;
use yii\web\AssetBundle;

class SequenceJsAsset extends AssetBundle
{
    public $sourcePath = '@amilna/sequencejs/assets';
	
	public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
	
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public function init()
    {
        parent::init();

        $this->js[] = YII_DEBUG ? 'js/jquery.sequence.js' : 'js/jquery.sequence-min.js';       
    }    
}
