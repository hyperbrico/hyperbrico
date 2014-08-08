$(function () {
	$.each([0,1], function($i) {
		blueimp.Gallery(
		    document.getElementById('images' + $i).getElementsByTagName('a'),
		    {
		        container: '#viewer' + $i,
		        carousel: true,
			    enableKeyboardNavigation: true,
			    startSlideshow: false,
			    continuous: false
		    }
		);
	});
});
