# whirl
a tool for blending images according to a term (google image search)

## usage

### via interface
you can use the interface and see how the script works step for step.
the interface can be accessed with this file:

	interface/interface.php

### manually

1) initialise the class. all options (see below) are passed via the $options array in the initialisation of the class:

	$options['term'] = 'babycat';
	$options['quantity'] = 40;
	$options['cacheDir'] = dirname(__FILE__) . '/cache';
	$whirl = new Whirl($options);

2) after the initialisation you can simply go through the whole process and get the final blended image in this way:

	$finalImage = $whirl->whirl();

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

### options

## changelog
version 0.1 (20.08.2015):
 - initial commit

version 0.2 (21.08.2015):
 - a custom cache dir can now be set in the options
 - if the cache subdirs don't exists, they are created in the constructor
 - a custom term can now be set in the options
 - a custom quantity can now be set in the options
 - the constructor is now full implemented
 - the json in the interface is now created if it doesn't exist
 - bugfix: the term can now contain spaces/etc (urlencoded)
 - the final blend result is now saved in the cache dir with the search term as filename
 - completed the credits section in the interface
 - remove the debug output from the interface
 - documentation completed

version 0.3:
 - coming soon

## todo
 1. class: optional size of final image (via options)
 2. class: optional blendmode (via options)
 3. class: add alternative for users with short execution time on their servers
 4. interface: thumbnail view of what images you will get
 5. interface: style adaptions
 6. interface: choose different modes:
  a. fast: only necessary settings and directly show the final image
  b. steps: go trough the steps of the script
  c: explain: it will be explained which part of the interface runs which part of the class