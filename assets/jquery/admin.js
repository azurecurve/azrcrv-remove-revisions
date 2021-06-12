/*
 * Tabs
 */
jQuery(function($) {
	'use strict';
	
	$('#tabs ul li a').on('keyup click', function(e) {
        if (e.key === 'Enter' || e.type === 'click') {
			var id = $(this).attr('href');
			$('.ui-state-active').removeClass('ui-state-active').attr('aria-selected', 'false').attr('aria-expanded', 'false');
			$(this).parent('li').addClass('ui-state-active').attr('aria-selected', 'true').attr('aria-expanded', 'true');
			$(this).closest('ul').siblings().addClass('ui-tabs-hidden').attr('aria-hidden', 'true');
			$(id).removeClass('ui-tabs-hidden').attr('aria-hidden', 'false');
			e.preventDefault();
		}
	});
	
	$('#azrcrv-tabs ul li a').hover(
		function() { $(this).addClass('ui-state-hover'); },
		function() { $(this).removeClass('ui-state-hover'); }
	);
});