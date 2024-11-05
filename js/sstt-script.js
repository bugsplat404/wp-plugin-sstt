jQuery(document).ready(function($) {
    var scrollToTop = $('#scrollToTop');

	// Set position of the scroll button
    if (sstt_vars.position == 'left') {
        scrollToTop.css('right', 'auto');
        scrollToTop.css('left', sstt_vars.side_distance + 'px');
    } else {
        scrollToTop.css('right', sstt_vars.side_distance + 'px');
    }

	/*
	 * Scroll Listener to toggle the visibility of the scroll-to-top button
	 *
	 * @global {Object} sstt_vars - Global configuration object for the scroll-to-top button.
	 * @param {number} sstt_vars.display_threshold - The minimum scroll position (in px)
	 * @param {string} sstt_vars.sstt_vars.animation_effect  - The animation effect type
	 */
	$(window).scroll(function() {
		if ($(window).scrollTop() > parseInt(sstt_vars.display_threshold)) {
			if (sstt_vars.animation_effect == 'fade') {
				scrollToTop.fadeIn();
			} else if (sstt_vars.animation_effect == 'slide') {
				scrollToTop.slideDown();
			} else {
				scrollToTop.show();
			}
		} else {
			if (sstt_vars.animation_effect == 'fade') {
				scrollToTop.fadeOut();
			} else if (sstt_vars.animation_effect == 'slide') {
				scrollToTop.slideUp();
			} else {
				scrollToTop.hide();
			}
		}
	});

    scrollToTop.click(function() {
        $('html, body').animate({ scrollTop: 0 }, 800);
        return false;
    });
});

