<?php

namespace mrlco\revolutionslider;

/**
 * Class SliderAsset
 */
class SliderAsset extends \yii\web\AssetBundle
{
    public $sourcePath;

    public $css = [
        'revolution/css/settings.css',
        'revolution/css/layers.css',
        'revolution/css/navigation.css',
    ];
    public $js = [
        'revolution/js/jquery.themepunch.tools.min.js',
        'revolution/js/jquery.themepunch.revolution.min.js',
        'revolution-addons/slicey/js/revolution.addon.slicey.min.js',
        'revolution/js/extensions/revolution.extension.actions.min.js',
        'revolution/js/extensions/revolution.extension.carousel.min.js',
        'revolution/js/extensions/revolution.extension.kenburn.min.js',
        'revolution/js/extensions/revolution.extension.layeranimation.min.js',
        'revolution/js/extensions/revolution.extension.migration.min.js',
        'revolution/js/extensions/revolution.extension.navigation.min.js',
        'revolution/js/extensions/revolution.extension.parallax.min.js',
        'revolution/js/extensions/revolution.extension.slideanims.min.js',
        'revolution/js/extensions/revolution.extension.video.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

    public function init()
    {
        $module = \Yii::$app->getModule('revolutionslider');
        $this->sourcePath = $module->getPluginLocation();
        parent::init();
    }
}
