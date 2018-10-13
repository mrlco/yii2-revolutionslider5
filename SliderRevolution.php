<?php

namespace mrlco\sliderrevolution;

use mrlco\sliderrevolution\SliderAsset;

use yii\helpers\Html;
use yii\base\InvalidConfigException;

/**
 * Slider Revolution Widget
 *
 * @package yii2-revolutionslider5
 * @version 1.0.0
 */
class SliderRevolution extends \yii\base\Widget
{

    public $defaultConfig = ['delay' => 9000, 'startwidth' => 1170, 'startheight' => 500, 'hideThumbs' => 10];

    // default from docs
    public $container = ['class' => 'tp-banner-container'];

    // default from docs
    public $wrapper = ['class' => 'tp-banner'];

    // none by default
    public $ulOptions = [];

    //<li data-transition="fade" data-slotamount="2" data-masterspeed="1500" >
    public $defaultSlideOptions = ['data' => ['transition' => 'fade', 'slotamount' => '2', 'masterspeed' => '1500']];

    //alt="slidebg1" data-bgfit="cover" data-bgposition="left center" data-bgrepeat="no-repeat"
    public $defaultImageOptions = ['alt' => '', 'data' => ['bgfit' => 'cover', 'bgposition' => 'left center', 'bgrepeat' => 'no-repeat']];

    // class="tp-caption lft"
    public $defaultLayerOptions = ['class' => 'tp-caption lft', 'data' => ['x' => 'center', 'y' => 'top', 'hoffset' => '0', 'voffset' => '50', 'speed' => '2500', 'start' => '1200', 'easing' => 'Power4.easeOut', 'endspeed' => '300', 'endeasing' => 'Power1.easeIn', 'captionhidden' => 'off']];

    public $defaultContent = 'My Caption';

    public $config;

    public $slides;

    private $basePath;

    private $baseUrl;

    private $rawSliderHtml;

    private $_module;

    private $_pluginLocation;

    public function init()
    {
        parent::init();

        $this->_module = \Yii::$app->getModule('sliderrevolution');
        $this->_pluginLocation = $this->_module->getPluginLocation();

        if ($this->_module === null) {
            throw new InvalidConfigException('Slider Revolution Error: Module not found!');
        }

        if ($this->_pluginLocation === null) {
            throw new InvalidConfigException('Slider Revolution Error: Plugin location not found!');
        }

        if ($this->config === null || !is_array($this->config)) {
            $this->config = $this->defaultConfig;
        }

        if (!isset($this->slides)) {
            throw new InvalidConfigException('Slider Revolution Error: You must have at least 1 slide!');
        }

        $asset = SliderAsset::register($this->view);
        $this->basePath = $asset->basePath;
        $this->baseUrl = $asset->baseUrl;
    }

    public function run()
    {
        $this->buildJsScript();
        $this->buildSlider();

        return $this->getSliderHtml();
    }

    public function buildJsScript()
    {
        $jqueryIdentifier;
        if (isset($this->wrapper['id'])) {
            $jqueryIdentifier = '#' . $this->wrapper['id'];
        } elseif (isset($this->wrapper['class'])) {
            $jqueryIdentifier = '.' . $this->wrapper['class'];
        } else {
            throw new InvalidConfigException('Slider Revolution Error: Wrapper requires an id or a class!');
        }

        $config = '';
        foreach ($this->config as $key => $val) {
            if (is_array($val)) {
                $item = '{';
                foreach ($val as $key2 => $val2) {
                    if (is_array($val2)) {
                        $subItem = '{';
                        foreach ($val2 as $key3 => $val3) {
                            $subItem .= $key3 . ':' . $val3 . ',';
                        }
                        $item .= $key2 . ':' . rtrim($subItem, ',') . '},';
                    } else {
                        $item .= $key2 . ':' . $val2 . ',';
                    }
                }
                $config .= $key . ':' . rtrim($item, ',') . '},';

            } else {
                $config .= $key . ':' . $val . ',' . PHP_EOL;
            }
        }
        $code = "
            var tpj=jQuery;
            var revapi24;
            tpj(document).ready(function() {
                if(tpj('" . $jqueryIdentifier . "').revolution == undefined){
                    revslider_showDoubleJqueryError('" . $jqueryIdentifier . "');
                } else {
                    revapi24 = tpj('" . $jqueryIdentifier . "').show().revolution({
                    " . rtrim($config, ','.PHP_EOL) . "
                    });
                }
                if (revapi24) revapi24.revSliderSlicey();
            });";

        $this->view->registerJs($code, \yii\web\View::POS_END, 'yii2-sliderrevolution');
    }

    public function buildSlider()
    {
        $this->rawSliderHtml = '';
        $this->rawSliderHtml .= Html::beginTag('div', $this->container);
        $this->rawSliderHtml .= Html::beginTag('div', $this->wrapper);
        $this->rawSliderHtml .= Html::beginTag('ul', $this->ulOptions);

        $this->rawSliderHtml .= $this->renderSlides();

        $this->rawSliderHtml .= Html::endTag('ul');
        $this->rawSliderHtml .= Html::tag('div', '',['class' => 'tp-bannertimer tp-bottom', 'style' => 'height: 5px; background: rgb(87,202,133);']);
        $this->rawSliderHtml .= Html::endTag('div');
        $this->rawSliderHtml .= Html::endTag('div');
    }

    public function renderSlides()
    {
        $slidesHtml = '';
        foreach ($this->slides as $slide) {
            $slidesHtml .= $this->renderSlide($slide);
        }
        return $slidesHtml;
    }

    public function renderSlide($slide)
    {
        if (!isset($slide['options'])) {
            $slide['options'] = $this->defaultSlideOptions;
        }

        if (!isset($slide['image']) || !isset($slide['image']['src']) || empty($slide['image']['src'])) {
            throw new InvalidConfigException('Slider Revolution Error: Missing slide image!');
        }

        if (!isset($slide['image']['options'])) {
            $slide['image']['options'] = $this->defaultImageOptions;
        }

        $slideHtml = '';
        $slideHtml .= Html::beginTag('li', $slide['options']);

        // image
        $slideHtml .= Html::img($slide['image']['src'], $slide['image']['options']);

        // layers: div content /div
        $layersHtml = '';
        foreach ($slide['layers'] as $layer) {
            if (!isset($layer['options'])) {
                $layer['options'] = $this->defaultLayerOptions;
            }

            if (!isset($layer['content'])) {
                $layer['content'] = $this->defaultContent;
            }

            $layerHtml = '';
            $layerHtml .= Html::beginTag('div', $layer['options']);
            $layerHtml .= $layer['content'];
            $layerHtml .= Html::endTag('div');

            $layersHtml .= $layerHtml;
        }

        $slideHtml .= $layersHtml;

        $slideHtml .= Html::endTag('li');
        return $slideHtml;
    }

    public function getSliderHtml()
    {
        return $this->rawSliderHtml;
    }
}
