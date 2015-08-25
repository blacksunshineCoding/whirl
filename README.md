# whirl
a tool for blending images according to a term (google image search)

## usage

### via interface
you can use the interface and see how the script works step for step.
the interface can be accessed with this file:

	interface/interface.php

### manually - normal

1) initialise the class. all options (see below) are passed via the $options array in the initialisation of the class:

	$options['term'] = 'babycat';
	$options['quantity'] = 40;
	$options['cacheDir'] = dirname(__FILE__) . '/cache';
	$whirl = new Whirl($options);

2) after the initialisation you can simply go through the whole process and get the final blended image in this way:

	$finalImage = $whirl->whirl();

### manually - step by step

as alternative you can go through the whole process step by step:

1) initialise with options:

	$options['term'] = 'babycat';
	$options['quantity'] = 40;
	$options['cacheDir'] = dirname(__FILE__) . '/cache';
	$whirl = new Whirl($options);

2) clear cache

	$whirl->clearCache();

3) get results

	$whirl->getResults();

4) save results

	$whirl->saveResults();

5) resize results

	$whirl->resizeResults();

6) multiply results

	$whirl->multiplyResults();

7) get the final image

	$finalImage = $whirl->finalImage();

---

### options

	$options['term'] = 'babycat'; // the search term
	$options['quantity'] = 40; // how much images will be blended
	$options['cacheDir'] = '../cache'; // the dir which is used for cache files
	$options['finalImageWidth'] = 400; // width of the final image
	$options['finalImageHeight'] = 400; // height of the final image
	$options['finalImageSizing'] = 'default'; // default/crop; if cropped the images will be cropped to exact size
	$options['finalImageAlignH'] = 'center'; // horicontal align if cropped (left/center/right)
	$options['finalImageAlignV'] = 'center'; // vertical align if cropped (top/center/bottom)
	$options['backgroundColor'] = '255,255,255'; // color of the base image behind all blended images (rgb values/transparent)
	$options['blendMode'] = false; // blend modes which graphic programs like photoshop use (for options see blend modes below)
	$options['blendOpacity'] = 'default'; // opacity of the single images when blended (range from 0 to 100; default is 100 divided by quantity)
	$options['alphaBlendMode'] = 'normal'; // normal, replace, overlay; alpha blending mode (imagelayereffect)
	$options['effectList'] = 'grayscale,negate'; // effects in a comma-seperated list, values see below
	$options['effectBrightnessLevel'] = 100; // level for the brigthness effect (-255 to 255)
	$options['effectContrastLevel'] = 50; // level for the contrast effect (-100 to 100)
	$options['effectColorizeRgba'] = '255,0,0,.75'; // color for the colorize effect (red, green, blue, alpha), colors 0 to 255 and alpha 0 to 1
	$options['effectSmoothLevel'] = '100'; // level for the smooth effect
	$options['effectPixelSize'] = '10'; // pixel size for the pixelate effect
	$options['effectAdvancedPixelation'] = true; // advanced mode for the pixelate effect
	$options['effectGammaCorrect'] = '1,1'; // input and output gamma for the gammacorrect effect

---

### effects
 - **negate:** reverses all colors of the image
 - **grayscale:** converts the images into grayscale
 - **brightness:** changes the brightness of the image (range is from -255 to 255)
 - **contrast:** change the contrast of the images (range is from -100 to 100)
 - **colorize:** colors the image (values are red, green, blue, alpha)
 - **edges:** highlight the edges in the images
 - **emboss:** embosses the image
 - **gaussBlur:** blurs the image using the gaussian method
 - **selectiveBlur:** blurs the image
 - **meanRemoval:** creates a sketchy effect
 - **smooth:** makes the images smoother
 - **pixelate:** pixelates the images with the set pixelsize

---

### blend modes
 - dissolve
 - darkerColor
 - darken
 - multiply
 - colorBurn
 - linearBurn
 - lighterColor
 - lighten
 - screen
 - colorDodge
 - linearDodge
 - overlay
 - softLight
 - hardLight
 - vividLight
 - linearLight
 - pinLight
 - hardMix
 - difference
 - exclusion
 - subtract
 - divide
 - hue
 - saturation
 - color
 - luminosity

---

## changelog
**version 0.1** (20.08.2015):

 - initial commit

**version 0.2** (21.08.2015):

 - class: a custom cache dir can now be set in the options
 - class: if the cache subdirs don't exists, they are created in the constructor
 - class: a custom term can now be set in the options
 - class: a custom quantity can now be set in the options
 - class: the constructor is now full implemented
 - class: bugfix: the term can now contain spaces/etc
 - interface: the json file is now created if it doesn't exist
 - interface: the final blend result is now saved in the cache dir with the search term as filename
 - interface: completed the credits section in the interface
 - interface: remove the debug output
 - documentation updated

**version 0.3** (22.08.2015):

 - class: option to gamma correct each image before blending was added
 - class: effects added
 - class: possibility to add multiple filters
 - class: gamma correct moved to effects
 - class: optional alpha blending mode was added
 - interface: scroll to steps after clicking buttons
 - interface: styling adaptions
 - interface: reset button added
 - interface: fadeout button after the execution
 - interface: now a mode can be chosen
 - interface: expert mode was added
 - documentation updated
 
**version 0.4** (25.08.2015):

 - class: background-color (base behind all blended images) can now be set in options
 - class: blend opacity can noww be set in options
 - class: custom size of the final image works now
 - class: real multiply was added (multiplies the images like photoshop)
 - class: real multiply is now available in blendMode along with many other blend modes
 - class: blendMode was renamed to alphaBlendMode because blendMode will be used for the real blend modes
 - class: renamed realBlendMode to blendMode because there is only real blend mode, the others are effects or alpha blend modes
 - class: quantity works now exactly (bugfix)
 - class: got the blend mode dissolve working in pretty much the same way photoshop does
 - class: soft light blend mode also works (damns, this was a hard one)
 - class: finished all blend modes
 - class: all blend modes were tested. most of them are pretty identical with the results of photoshop, some show various difference (at the moment it seems that nothing better is possible)
 - documentation updated

---

## tasks
 - interface: add alternative for users with short execution time on their servers
 - interface: thumbnail view of what images you will get
 - interface: style adaptions
 - interface: fast mode -  only necessary settings and directly show the final image
 - class: possibility to use alternative search engines
 - class: effects alternative for php versions without imagefilter
 - class: add image crop mode
 - class: add presets (group containing various effects)
 - class: check if images are valid