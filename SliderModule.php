<?php

namespace mrlco\revolutionslider;

/**
 * Slider Revolution Module
 */
class SliderModule extends \yii\base\Module
{
    public $pluginLocation;

    public function init()
    {
        parent::init();
    }

    public function getPluginLocation()
    {
        return $this->pluginLocation;
    }

}
