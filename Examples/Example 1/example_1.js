$(document).ready(function(){

    // Set up our options for the slideshow...
    var myOptions = {
        noImages: 3,
        path: "Examples/Example 1/slideshow_images/",  // Relative path with trailing slash.
        linksOpen:'newWindow',
        timerInterval: 4500, // 6500 = 6.5 seconds
	randomise: false // Start with random image?
    };

    // Woo! We have a jquery slideshow plugin!
    $('#example_1_container').easySlides(myOptions);

})