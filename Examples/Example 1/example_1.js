$(document).ready(function(){

    // Set up our options for the slideshow...
    var myOptions = {
        noImages: 4,
        path: "Examples/Example 1/slideshow_images/",  // Relative path with trailing slash.
        linksOpen:'newWindow',
        timerInterval: 3600, // 6500 = 6.5 seconds
	randomise: true // Start with random image?
    };

   
    $('#example_1_container').easySlides(myOptions);

})