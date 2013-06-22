(function($) {
    $(document).ready(function() {
    	$('#nav-toggle').bind('click', function(event) {
    		var nav = $('#primary-menu');
    		var navItems = nav.children('li');
    		var displayHeight = navItems.length * navItems.height();
    		//Check if menu is already opened.
    		displayHeight = (nav.height() > 0) ? 0 : displayHeight;
    		nav.animate({
    			maxHeight: displayHeight
    		});
		});
    });
})(jQuery);