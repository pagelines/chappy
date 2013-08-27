jQuery(document).ready(function() {
	jQuery('a').live('click', function (event) {
		event.preventDefault();
		window.location = jQuery(this).attr("href");
	});
});