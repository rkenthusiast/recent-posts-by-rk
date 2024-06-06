(function( $ ) {
	'use strict';



	// alert('test');
	// $('.multiple-select').multipleSelect();


	// $(document).on('DOMNodeInserted', function(e) {
    //     if ($(e.target).hasClass('multiple-select')) {
    //         $('.multiple-select').multipleSelect();
    //     }
    // });


	var intervalId = setInterval(function() {

		console.log('inter val runnig');
        if ($('.multiple-select').length > 0) {
            $('.multiple-select').multipleSelect();
            clearInterval(intervalId); // Stop the interval once the element is found
        }
    }, 1000); // Check every 100 milliseconds

})( jQuery );
