# yii2-revolutionslider5

### Yii2 Themepunch Slider Revolution V5 jQuery Wrapper Extension

This is simply a handy helper/wrapper for Slider Revolution (not included).

**NOTICE: Slider Revolution IS NOT INCLUDED!** I want to make that very clear. You MUST have the jQuery Slider Revolution, or go purchase it, in order to use this. You will need to place the 'rs-plugin' directory somewhere and point to it in the config. This extension **WILL NOT WORK** if you do not have, or buy, Slider Revolution!

### Why isn't Slider Revolution included?
Slider Revolution is a paid plugin found on Theme Forest. I can not give it to you, that would be illegal and violating infringement terms across many sites. Slider Revolution is awesome, very cheap (about $14), so just go buy it. In my opinion, it makes the nicest and best sliders out there!

### Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

    $ php composer.phar require mrlco/yii2-revolutionslider5 "@dev"

or

    "mrlco/yii2-revolutionslider5": "@dev"

to the `require` section of your `composer.json` file.

### Usage

First, open your Yii main.php config file, and add the extension to your modules.
```php
    'modules' => [
        'revolutionslider' => [
            'class' => 'mrlco\revolutionslider\SliderModule',
            'pluginLocation' => '@frontend/views/private/rs-plugin',    // <-- path to your rs-plugin directory
        ],
    ],
```
You do not need to place the `rs-plugin` directory in a public accessible location. Yii will copy it using the AssetManager.

At the top of your view file (or layout), add the namespace.

    use mrlco\revolutionslider\SliderRevolution;

Then build the arrays, and echo the widget.
```php
    <?php
    $slides = [
        [
            'image' => ['src' => 'path/to/slider/image.png'],
            'layers' => [
                [
                    'content' => 'Layer 1'
                ],
                [
                    'content' => 'Layer 2'
                ],
            ],
        ]
    ];

    echo SliderRevolution::widget([
        'slides' => $slides
    ]);
    ?>
```
The above is the extension using the built in default options (with minor changes).

You could build the arrays inside your controller and pass them to your view, especially if you needed to run special logic to render them.

** Example configuring everything for example of flexibility:**
```php
    <?php
    $config = ['delay' => 9000, 'startwidth' => 1170, 'startheight' => 500, 'hideThumbs' => 10, 'fullWidth' => '"on"', 'forceFullWidth' => '"on"'];
    $container = ['class' => 'tp-banner-container'];
    $wrapper = ['class' => 'tp-banner'];
    $ulOptions = ['class' = 'my-ul'];

    $slides = [
        [
            'options' => ['data' => ['transition' => 'fade', 'slotamount' => '2', 'masterspeed' => '1500']],
            'image' => ['src' => 'path/to/slider/image.png', 'options' => ['alt' => 'slidebg1', 'data' => ['bgfit' => 'cover', 'bgposition' => 'left center', 'bgrepeat' => 'no-repeat']]],
            'layers' => [
                [
                    'options' => ['class' => 'tp-caption lft', 'data' => ['x' => 'center', 'y' => 'top', 'hoffset' => '0', 'voffset' => '50', 'speed' => '2500', 'start' => '1200', 'easing' => 'Power4.easeOut', 'endspeed' => '300', 'endeasing' => 'Power1.easeIn', 'captionhidden' => 'off'], 'style' => 'z-index: 6'],
                    'content' => 'My Slide'
                ],
                [
                    'options' => ['class' => 'tp-caption lfr', 'data' => ['x' => 'center', 'y' => 'bottom', 'hoffset' => '0', 'voffset' => '-50', 'speed' => '2500', 'start' => '1800', 'easing' => 'Power4.easeOut', 'endspeed' => '300', 'endeasing' => 'Power1.easeIn', 'captionhidden' => 'off'], 'style' => 'z-index: 6'],
                    'content' => 'My Text'
                ],
            ],
        ]
    ];

    echo SliderRevolution::widget([
        'config' => $config,
        'container' => $container,
        'wrapper' => $wrapper,
        'ulOptions' => $ulOptions,
        'slides' => $slides
    ]);
    ?>
```
The above is a full width slider and most of the options are defaults.

If you pass an `id` to the container, it will be used in the jQuery listener instead.

When passing `data` attributes through the array, use `data` as the key, then an array of each of the suffixes.
```php
    <div data-one="hello" data-two="world">...</div>
    ['data' => ['one' => 'hello', 'two' => 'world']]
```
This works the same way Yii handles options being passed. In fact, these options are passed to Yii to process.
