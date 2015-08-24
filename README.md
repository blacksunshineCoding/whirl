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
 - **dissolve:**<br>
Edits or paints each pixel to make it the result color. However, the result color is a random replacement of the pixels with the base color or the blend color, depending on the opacity at any pixel location
 
 - **darkerColor:** (not implemented yet)<br>
Compares the total of all channel values for the blend and base color and displays the lower value color. Darker Color does not produce a third color, which can result from the Darken blend, because it chooses the lowest channel values from both the base and the blend color to create the result color.
 
 - **darken:**<br>
Looks at the color information in each channel and selects the base or blend color—whichever is darker—as the result color. Pixels lighter than the blend color are replaced, and pixels darker than the blend color do not change.
 
 - **multiply:**<br>
Looks at the color information in each channel and multiplies the base color by the blend color. The result color is always a darker color. Multiplying any color with black produces black. Multiplying any color with white leaves the color unchanged. When you’re painting with a color other than black or white, successive strokes with a painting tool produce progressively darker colors. The effect is similar to drawing on the image with multiple marking pens.

 - **colorBurn:**<br>
Looks at the color information in each channel and darkens the base color to reflect the blend color by increasing the contrast between the two. Blending with white produces no change.

 - **linearBurn:**<br>
Looks at the color information in each channel and darkens the base color to reflect the blend color by decreasing the brightness. Blending with white produces no change.

 - **lighterColor:** (not implemented yet)<br>
Compares the total of all channel values for the blend and base color and displays the higher value color. Lighter Color does not produce a third color, which can result from the Lighten blend, because it chooses the highest channel values from both the base and blend color to create the result color.
 
 - **lighten:**<br>
Looks at the color information in each channel and selects the base or blend color—whichever is lighter—as the result color. Pixels darker than the blend color are replaced, and pixels lighter than the blend color do not change.
 
 - **screen:**<br>
Looks at each channel’s color information and multiplies the inverse of the blend and base colors. The result color is always a lighter color. Screening with black leaves the color unchanged. Screening with white produces white. The effect is similar to projecting multiple photographic slides on top of each other.
 
 - **colorDodge:**<br>
Looks at the color information in each channel and brightens the base color to reflect the blend color by decreasing contrast between the two. Blending with black produces no change.

 - **linearDodge:**<br>
Looks at the color information in each channel and brightens the base color to reflect the blend color by increasing the brightness. Blending with black produces no change.

 - **overlay:**<br>
Multiplies or screens the colors, depending on the base color. Patterns or colors overlay the existing pixels while preserving the highlights and shadows of the base color. The base color is not replaced, but mixed with the blend color to reflect the lightness or darkness of the original color.

 - **softLight:** (not implemented yet)<br>
Darkens or lightens the colors, depending on the blend color. The effect is similar to shining a diffused spotlight on the image. If the blend color (light source) is lighter than 50% gray, the image is lightened as if it were dodged. If the blend color is darker than 50% gray, the image is darkened as if it were burned in. Painting with pure black or white produces a distinctly darker or lighter area, but does not result in pure black or white.
 
 - **hardLight:** (not implemented yet)<br>
Multiplies or screens the colors, depending on the blend color. The effect is similar to shining a harsh spotlight on the image. If the blend color (light source) is lighter than 50% gray, the image is lightened, as if it were screened. This is useful for adding highlights to an image. If the blend color is darker than 50% gray, the image is darkened, as if it were multiplied. This is useful for adding shadows to an image. Painting with pure black or white results in pure black or white.
 
 - **vividLight:** (not implemented yet)<br>
Burns or dodges the colors by increasing or decreasing the contrast, depending on the blend color. If the blend color (light source) is lighter than 50% gray, the image is lightened by decreasing the contrast. If the blend color is darker than 50% gray, the image is darkened by increasing the contrast.
 
 - **linearLight:** (not implemented yet)<br>
Burns or dodges the colors by decreasing or increasing the brightness, depending on the blend color. If the blend color (light source) is lighter than 50% gray, the image is lightened by increasing the brightness. If the blend color is darker than 50% gray, the image is darkened by decreasing the brightness.

 - **pinLight:** (not implemented yet)<br>
Replaces the colors, depending on the blend color. If the blend color (light source) is lighter than 50% gray, pixels darker than the blend color are replaced, and pixels lighter than the blend color do not change. If the blend color is darker than 50% gray, pixels lighter than the blend color are replaced, and pixels darker than the blend color do not change. This is useful for adding special effects to an image.
 
 - **hardMix:**<br>
Adds the red, green and blue channel values of the blend color to the RGB values of the base color. If the resulting sum for a channel is 255 or greater, it receives a value of 255; if less than 255, a value of 0. Therefore, all blended pixels have red, green, and blue channel values of either 0 or 255. This changes all pixels to primary additive colors (red, green, or blue), white, or black.

 - **difference:**<br>
Looks at the color information in each channel and subtracts either the blend color from the base color or the base color from the blend color, depending on which has the greater brightness value. Blending with white inverts the base color values; blending with black produces no change.

 - **exclusion:**<br>
Creates an effect similar to but lower in contrast than the Difference mode. Blending with white inverts the base color values. Blending with black produces no change.

 - **subtract:**<br>
Looks at the color information in each channel and subtracts the blend color from the base color. In 8- and 16-bit images, any resulting negative values are clipped to zero.
 
 - **divide:**<br>
Looks at the color information in each channel and divides the blend color from the base color.

 - **hue:**<br>
Creates a result color with the luminance and saturation of the base color and the hue of the blend color.
 
 - **saturation:**<br>
Creates a result color with the luminance and hue of the base color and the saturation of the blend color. Painting with this mode in an area with no (0) saturation (gray) causes no change.
 
 - **color:**<br>
Creates a result color with the luminance of the base color and the hue and saturation of the blend color. This preserves the gray levels in the image and is useful for coloring monochrome images and for tinting color images.
 
 - **luminosity:**<br>
Creates a result color with the hue and saturation of the base color and the luminance of the blend color. This mode creates the inverse effect of Color mode.

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
 
**version 0.4** (in progress):

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
 - documentation updated
 - ...

---

## tasks
 - class: add alternative for users with short execution time on their servers
 - interface: thumbnail view of what images you will get
 - interface: style adaptions
 - interface: fast mode -  only necessary settings and directly show the final image
 - class: possibility to use alternative search engines
 - class: effects alternative for php versions without imagefilter
 - class: add image crop mode
 - class: finish all blend modes
 - class: test all blend modes (control effect with photoshop)
 - class: add presets (group containing various effects)