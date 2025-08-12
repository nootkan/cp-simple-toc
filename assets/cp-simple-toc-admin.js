/**
 * Simple Table of Contents Admin JavaScript
 * Handles color picker functionality in the admin settings page
 */

jQuery(document).ready(function($) {
    
    // Initialize WordPress Color Picker for all color input fields
    $('.color-picker').wpColorPicker({
        // Color picker options
        defaultColor: false,
        change: function(event, ui) {
            // Optional: Add real-time preview functionality here
            var element = event.target;
            var color = ui.color.toString();
            
            // Update live preview if preview element exists
            updateLivePreview();
        },
        clear: function() {
            // Handle color clearing
            updateLivePreview();
        }
    });
    
    // Add live preview functionality
    function updateLivePreview() {
        // Get current color values
        // Get current color values
        var backgroundColor = $('input[name="simple_toc_options[background_color]"]').val() || '#7cb894';
        var textColor = $('input[name="simple_toc_options[text_color]"]').val() || '#2d5a3d';
        var linkColor = $('input[name="simple_toc_options[link_color]"]').val() || '#2d5a3d';
        var linkHoverColor = $('input[name="simple_toc_options[link_hover_color]"]').val() || '#1a3d26';
        var borderColor = $('input[name="simple_toc_options[border_color]"]').val() || '#6da582';
        var bulletColor = $('input[name="simple_toc_options[bullet_color]"]').val() || '#4a7c59';
        
        // Get typography values
        var fontFamily = $('select[name="simple_toc_options[font_family]"]').val() || 'inherit';
        var titleFontSize = $('input[name="simple_toc_options[title_font_size]"]').val() || '16';
        var titleFontWeight = $('select[name="simple_toc_options[title_font_weight]"]').val() || '500';
        var linkFontSize = $('input[name="simple_toc_options[link_font_size]"]').val() || '14';
        var linkFontWeight = $('select[name="simple_toc_options[link_font_weight]"]').val() || '400';
        
        // Get custom title
        var customTitle = $('input[name="simple_toc_options[custom_title]"]').val() || 'Table Of Contents';
        
        // Create or update preview styles
        var previewStyles = `
            <style id="toc-preview-styles">
                .toc-preview .simple-toc-container {
                    background-color: ${backgroundColor} !important;
                    font-family: ${fontFamily} !important;
                }
                .toc-preview .simple-toc-header {
                    background-color: ${backgroundColor} !important;
                }
                .toc-preview .simple-toc-header:hover {
                    background-color: ${borderColor} !important;
                }
                .toc-preview .simple-toc-title {
                    color: ${textColor} !important;
                    font-size: ${titleFontSize}px !important;
                    font-weight: ${titleFontWeight} !important;
                }
                .toc-preview .simple-toc-toggle {
                    color: ${textColor} !important;
                }
                .toc-preview .simple-toc-content {
                    background-color: ${backgroundColor} !important;
                }
                .toc-preview .simple-toc-link {
                    color: ${linkColor} !important;
                    font-size: ${linkFontSize}px !important;
                    font-weight: ${linkFontWeight} !important;
                }
                .toc-preview .simple-toc-link:hover {
                    color: ${linkHoverColor} !important;
                }
				.toc-preview .simple-toc-list {
                    list-style-type: disc !important;
                    padding-left: 20px !important;
                }
                .toc-preview .simple-toc-list > li::marker {
                    color: ${bulletColor} !important;
                }
                .toc-preview .simple-toc-sublist {
                    list-style-type: disc !important;
                    padding-left: 20px !important;
                }
               .toc-preview .simple-toc-sublist li::marker {
                   color: ${bulletColor} !important;
                }
                @media (max-width: 768px) {
                    .toc-preview .simple-toc-list > li::marker,
                    .toc-preview .simple-toc-sublist li::marker {
                        color: ${bulletColor} !important;
                    }
                }
            </style>
        `;
        
        // Remove existing preview styles and add new ones
        $('#toc-preview-styles').remove();
        $('head').append(previewStyles);
        
        // Update the preview title text
        $('.toc-preview .simple-toc-title').text(customTitle);
    }
    
    // Create live preview section
    function createPreviewSection() {
        var previewHTML = `
            <div class="toc-preview-section" style="margin-top: 20px; padding: 20px; background: #fff; border: 1px solid #ccd0d4; border-radius: 4px;">
                <h3>Live Preview</h3>
                <p>This is how your Table of Contents will look with the current color settings:</p>
                <div class="toc-preview">
                    <div class="simple-toc-container" style="max-width: 400px;">
                        <div class="simple-toc-header" style="padding: 15px 20px; cursor: pointer; display: flex; align-items: center;">
                            <span class="simple-toc-toggle" style="margin-right: 10px; font-size: 12px;">▼</span>
                            <span class="simple-toc-title" style="font-weight: 500; font-size: 16px;">Table Of Contents</span>
                        </div>
                        <div class="simple-toc-content" style="padding: 0 20px 15px;">
                            <ul class="simple-toc-list" style="margin: 0; padding-left: 20px; list-style-type: disc;">
                                <li style="margin: 8px 0;">
                                    <a href="#" class="simple-toc-link" style="text-decoration: none; font-size: 14px;">Introduction to Your Topic</a>
                                </li>
                                <li style="margin: 8px 0;">
                                    <a href="#" class="simple-toc-link" style="text-decoration: none; font-size: 14px;">Main Section</a>
                                    <ul class="simple-toc-sublist" style="margin-top: 5px; padding-left: 20px; list-style-type: disc;">
                                        <li style="margin: 5px 0;">
                                            <a href="#" class="simple-toc-link" style="text-decoration: none; font-size: 14px;">Subsection One</a>
                                        </li>
                                        <li style="margin: 5px 0;">
                                            <a href="#" class="simple-toc-link" style="text-decoration: none; font-size: 14px;">Subsection Two</a>
                                        </li>
                                    </ul>
                                </li>
                                <li style="margin: 8px 0;">
                                    <a href="#" class="simple-toc-link" style="text-decoration: none; font-size: 14px;">Conclusion</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p style="margin-top: 15px; font-style: italic; color: #666;">
                    <strong>Tip:</strong> Changes are applied immediately in the preview. Save your settings to apply them to your live site.
                </p>
            </div>
        `;
        
        // Add preview section after the form
        $('form').after(previewHTML);
        
        // Add hover effects to preview
        $('.toc-preview .simple-toc-header').hover(
            function() {
                var borderColor = $('input[name="simple_toc_options[border_color]"]').val() || '#6da582';
                $(this).css('background-color', borderColor);
            },
            function() {
                var backgroundColor = $('input[name="simple_toc_options[background_color]"]').val() || '#7cb894';
                $(this).css('background-color', backgroundColor);
            }
        );
        
        $('.toc-preview .simple-toc-link').hover(
            function() {
                var linkHoverColor = $('input[name="simple_toc_options[link_hover_color]"]').val() || '#1a3d26';
                $(this).css('color', linkHoverColor);
            },
            function() {
                var linkColor = $('input[name="simple_toc_options[link_color]"]').val() || '#2d5a3d';
                $(this).css('color', linkColor);
            }
        );
    }
    
    // Initialize preview after a short delay to ensure color pickers are ready
    setTimeout(function() {
        createPreviewSection();
        updateLivePreview();
    }, 500);
    
    // Add reset to defaults functionality
    function addResetButton() {
        var resetHTML = `
            <div style="margin-top: 15px;">
                <button type="button" id="reset-colors" class="button button-secondary">
                    Reset All to Defaults
                </button>
                <p class="description">This will reset all colors and typography to the original settings.</p>
            </div>
        `;
        
        $('form').after(resetHTML);
        
        $('#reset-colors').on('click', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to reset all colors and typography to their default values?')) {
                $('input[name="simple_toc_options[background_color]"]').wpColorPicker('color', '#7cb894');
                $('input[name="simple_toc_options[text_color]"]').wpColorPicker('color', '#2d5a3d');
                $('input[name="simple_toc_options[link_color]"]').wpColorPicker('color', '#2d5a3d');
                $('input[name="simple_toc_options[link_hover_color]"]').wpColorPicker('color', '#1a3d26');
                $('input[name="simple_toc_options[border_color]"]').wpColorPicker('color', '#6da582');
                
                // Reset typography values
                $('select[name="simple_toc_options[font_family]"]').val('inherit');
                $('input[name="simple_toc_options[title_font_size]"]').val('16');
                $('select[name="simple_toc_options[title_font_weight]"]').val('500');
                $('input[name="simple_toc_options[link_font_size]"]').val('14');
                $('select[name="simple_toc_options[link_font_weight]"]').val('400');
                
                // Reset custom title
                $('input[name="simple_toc_options[custom_title]"]').val('Table Of Contents');
                
                // Update preview
                updateLivePreview();
            }
        });
    }
    
    // Add reset button after color pickers are initialized
    setTimeout(addResetButton, 600);
    
    // Watch for any changes and update preview
    $('.color-picker, select[name*="simple_toc_options"], input[name*="simple_toc_options"]').on('change input', function() {
        setTimeout(updateLivePreview, 100);
    });
    
});