# whirl
a tool for blending images according to a term (google image search)

## usage

### via interface
you can use the interface and see how the script works step for step

### manually
*(may not work this way at the moment)*

1) initialise the class. all options (see below) are passed via the $options array in the initialisation of the class:

    $options['term'] = 'babycat';
    $options['quantity'] = 40;
    $whirl = new Whirl($options);

2) after the initialisation you can simply go through the whole process and get the final blended image in this way:

	$finalImage = $whirl->whirl();

as alternative you can go through the whole process step by step:

1) initialise with options:

    $options['term'] = 'babycat';
    $options['quantity'] = 40;
    $whirl = new Whirl($options);

2) coming soon

### options


## todo
 1. class: optional cacheDir (via options)
 2. class: optional size of final image (via options)
 3. class: optional term (via options)
 4. class: optional quantity (via options)
 5. class: optional blendmode (via options)
 6. class: full implementation of construct function (with all options/etc working)
 7. class: create cache dirs if not existing
 8. class: create json for fetching if not existing
 9. class: spaces in term cause problems
 10. interface: thumbnail view of what images you will get
 11. interface: complete credit section
 12. interface: style adaptions
 13. interface: remove debug output
 14. interface: choose different modes:
  a. fast: only necessary settings and directly show the final image
  b. steps: go trough the steps of the script
  c: explain: it will be explained which part of the interface runs which part of the class
 15. readme: complete documentation