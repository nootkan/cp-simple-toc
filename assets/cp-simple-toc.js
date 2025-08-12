/**
 * Simple Table of Contents JavaScript
 * Handles the collapse/expand functionality and smooth scrolling
 */

jQuery(document).ready(function($) {
    
    // Initialize TOC state on page load
    $('.simple-toc-container').each(function() {
        var $container = $(this);
        var $content = $container.find('.simple-toc-content');
        var $toggle = $container.find('.simple-toc-toggle');
        
        // Set initial state based on collapsed class
        if ($content.hasClass('collapsed')) {
            $toggle.text('▶');
        } else {
            $toggle.text('▼');
        }
    });
    
    // Handle TOC toggle functionality
    $('.simple-toc-header').on('click', function(e) {
        e.preventDefault();
        
        var $container = $(this).closest('.simple-toc-container');
        var $content = $container.find('.simple-toc-content');
        var $toggle = $(this).find('.simple-toc-toggle');
        
        // Toggle collapsed state
        $content.toggleClass('collapsed');
        
        // Update toggle icon
        if ($content.hasClass('collapsed')) {
            $toggle.text('▶');
        } else {
            $toggle.text('▼');
        }
    });
    
    // Handle smooth scrolling for TOC links
    $('.simple-toc-link').on('click', function(e) {
        e.preventDefault();
        
        var targetId = $(this).attr('href');
        var $target = $(targetId);
        
        if ($target.length) {
            // Calculate offset for fixed headers or other elements
            var offset = 20; // Adjust this value if you have fixed headers
            
            $('html, body').animate({
                scrollTop: $target.offset().top - offset
            }, 500, 'swing');
            
            // Update URL without jumping
            if (history.pushState) {
                history.pushState(null, null, targetId);
            }
        } else {
            // If target not found, try to scroll to heading by text content
            var linkText = $(this).text().trim();
            var $headingByText = $('h1, h2, h3, h4, h5, h6').filter(function() {
                return $(this).text().trim() === linkText;
            }).first();
            
            if ($headingByText.length) {
                $('html, body').animate({
                    scrollTop: $headingByText.offset().top - 20
                }, 500, 'swing');
            }
        }
    });
    
    // Highlight current section in TOC (optional enhancement)
    var $tocLinks = $('.simple-toc-link');
    var $headings = $('h1[id], h2[id], h3[id], h4[id], h5[id], h6[id]');
    
    if ($tocLinks.length && $headings.length) {
        $(window).on('scroll', throttle(function() {
            var scrollTop = $(window).scrollTop();
            var windowHeight = $(window).height();
            var currentHeading = null;
            
            // Find the current heading
            $headings.each(function() {
                var $heading = $(this);
                var headingTop = $heading.offset().top;
                
                if (headingTop <= scrollTop + 100) {
                    currentHeading = $heading.attr('id');
                }
            });
            
            // Update active link
            $tocLinks.removeClass('active');
            if (currentHeading) {
                $tocLinks.filter('[href="#' + currentHeading + '"]').addClass('active');
            }
        }, 100));
    }
    
    // Throttle function to limit scroll event firing
    function throttle(func, wait) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            if (!timeout) {
                timeout = setTimeout(function() {
                    timeout = null;
                    func.apply(context, args);
                }, wait);
            }
        };
    }
    
    // Handle keyboard navigation
    $('.simple-toc-header').on('keydown', function(e) {
        // Enter or Space key to toggle
        if (e.which === 13 || e.which === 32) {
            e.preventDefault();
            $(this).click();
        }
    });
    
    // Make TOC header focusable for accessibility
    $('.simple-toc-header').attr('tabindex', '0');
    
    // Add ARIA attributes for accessibility
    $('.simple-toc-container').each(function() {
        var $container = $(this);
        var $header = $container.find('.simple-toc-header');
        var $content = $container.find('.simple-toc-content');
        var isCollapsed = $content.hasClass('collapsed');
        
        // Generate unique IDs
        var headerId = 'toc-header-' + Math.random().toString(36).substr(2, 9);
        var contentId = 'toc-content-' + Math.random().toString(36).substr(2, 9);
        
        // Set ARIA attributes
        $header.attr({
            'aria-expanded': !isCollapsed,
            'aria-controls': contentId,
            'id': headerId
        });
        
        $content.attr({
            'aria-labelledby': headerId,
            'id': contentId
        });
    });
    
    // Update ARIA attributes when toggling
    $('.simple-toc-header').on('click', function() {
        var $content = $(this).closest('.simple-toc-container').find('.simple-toc-content');
        var isCollapsed = $content.hasClass('collapsed');
        
        $(this).attr('aria-expanded', !isCollapsed);
    });
    
});