<?php
/**
 * Plugin Name: Simple Table of Contents
 * Plugin URI: https://yoursite.com
 * Description: A simple, lightweight table of contents plugin for ClassicPress. Use [toc] shortcode to display.
 * Version: 1.2.0
 * Author: Van Isle Web Solutions
 * License: GPL v2 or later
 * Text Domain: simple-toc
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SIMPLE_TOC_VERSION', '1.2.0');
define('SIMPLE_TOC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SIMPLE_TOC_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Main Simple TOC Class
 */
class SimpleTOC {
    
    private $options;
    private $custom_content = '';
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_action('wp_head', array($this, 'add_custom_css'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
        // Register shortcode
        add_shortcode('toc', array($this, 'toc_shortcode'));
        
        // Capture page output for custom templates
        add_action('wp_head', array($this, 'start_output_buffering'), 1);
        add_action('wp_footer', array($this, 'end_output_buffering'), 999);
        
        // Get plugin options with defaults
        $this->options = get_option('simple_toc_options', array(
            'list_type' => 'ul',
            'default_state' => 'expanded',
            'heading_levels' => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
            'background_color' => '#7cb894',
            'text_color' => '#2d5a3d',
            'link_color' => '#2d5a3d',
            'link_hover_color' => '#1a3d26',
            'border_color' => '#6da582',
            'bullet_color' => '#4a7c59',
            'font_family' => 'inherit',
            'title_font_size' => '16',
            'title_font_weight' => '500',
            'link_font_size' => '14',
            'link_font_weight' => '400',
            'custom_title' => 'Table Of Contents'
        ));
    }
    
    public function init() {
        // Plugin initialization
    }
    
    /**
     * Enqueue admin scripts for color picker
     */
    public function admin_enqueue_scripts($hook) {
        if ($hook !== 'settings_page_simple-toc') {
            return;
        }
        
        // Add the color picker css file       
        wp_enqueue_style('wp-color-picker');
        
        // Include our custom jQuery file with WordPress Color Picker dependency
        $admin_js_url = SIMPLE_TOC_PLUGIN_URL . 'assets/cp-simple-toc-admin.js';
        
        if (!empty($admin_js_url) && !empty(SIMPLE_TOC_PLUGIN_URL)) {
            wp_enqueue_script('simple-toc-admin', $admin_js_url, array('wp-color-picker'), SIMPLE_TOC_VERSION, true);
        }
    }
    
    /**
     * Add custom CSS to frontend based on user choices
     */
    public function add_custom_css() {
        // Comprehensive null checks for all color options
        $background_color = (!empty($this->options['background_color']) && is_string($this->options['background_color'])) ? $this->options['background_color'] : '#7cb894';
        $text_color = (!empty($this->options['text_color']) && is_string($this->options['text_color'])) ? $this->options['text_color'] : '#2d5a3d';
        $link_color = (!empty($this->options['link_color']) && is_string($this->options['link_color'])) ? $this->options['link_color'] : '#2d5a3d';
        $link_hover_color = (!empty($this->options['link_hover_color']) && is_string($this->options['link_hover_color'])) ? $this->options['link_hover_color'] : '#1a3d26';
        $border_color = (!empty($this->options['border_color']) && is_string($this->options['border_color'])) ? $this->options['border_color'] : '#6da582';
        $bullet_color = (!empty($this->options['bullet_color']) && is_string($this->options['bullet_color'])) ? $this->options['bullet_color'] : '#4a7c59';
        
        // Font settings with null checks
        $font_family = (!empty($this->options['font_family']) && is_string($this->options['font_family'])) ? $this->options['font_family'] : 'inherit';
        $title_font_size = (!empty($this->options['title_font_size']) && is_numeric($this->options['title_font_size'])) ? $this->options['title_font_size'] : '16';
        $title_font_weight = (!empty($this->options['title_font_weight']) && is_string($this->options['title_font_weight'])) ? $this->options['title_font_weight'] : '500';
        $link_font_size = (!empty($this->options['link_font_size']) && is_numeric($this->options['link_font_size'])) ? $this->options['link_font_size'] : '14';
        $link_font_weight = (!empty($this->options['link_font_weight']) && is_string($this->options['link_font_weight'])) ? $this->options['link_font_weight'] : '400';
        
        echo '<style type="text/css">
        .simple-toc-container {
            background-color: ' . esc_attr($background_color) . ' !important;
            font-family: ' . esc_attr($font_family) . ' !important;
        }
        .simple-toc-header {
            background-color: ' . esc_attr($background_color) . ' !important;
        }
        .simple-toc-header:hover {
            background-color: ' . esc_attr($border_color) . ' !important;
        }
        .simple-toc-title {
            color: ' . esc_attr($text_color) . ' !important;
            font-size: ' . esc_attr($title_font_size) . 'px !important;
            font-weight: ' . esc_attr($title_font_weight) . ' !important;
        }
        .simple-toc-toggle {
            color: ' . esc_attr($text_color) . ' !important;
        }
        .simple-toc-content {
            background-color: ' . esc_attr($background_color) . ' !important;
        }
        .simple-toc-link {
            color: ' . esc_attr($link_color) . ' !important;
            font-size: ' . esc_attr($link_font_size) . 'px !important;
            font-weight: ' . esc_attr($link_font_weight) . ' !important;
        }
        .simple-toc-link:hover {
            color: ' . esc_attr($link_hover_color) . ' !important;
        }
        .simple-toc-link:visited {
            color: ' . esc_attr($link_color) . ' !important;
        }
        .simple-toc-list > li::marker {
            color: ' . esc_attr($bullet_color) . ' !important;
        }
        .simple-toc-sublist li::marker {
            color: ' . esc_attr($bullet_color) . ' !important;
        }
        .simple-toc-sublist .simple-toc-sublist li::marker {
            color: ' . esc_attr($bullet_color) . ' !important;
        }
        </style>';
    }
    
    /**
     * Start output buffering to capture page content
     */
    public function start_output_buffering() {
        if ($this->is_custom_template_page()) {
            ob_start();
        }
    }
    
    /**
     * End output buffering and store content
     */
    public function end_output_buffering() {
        if ($this->is_custom_template_page() && ob_get_level() > 0) {
            $content = ob_get_contents();
            if ($content !== false && is_string($content)) {
                $this->custom_content = $content;
            }
            // Don't clean the buffer here - let it continue to display normally
        }
    }
    
    /**
     * Check if we're on a custom template page
     */
    private function is_custom_template_page() {
        global $post;
        
        if (!is_object($post)) {
            return false;
        }
        
        // Check for your specific tide calendar page (for backwards compatibility)
        if (in_array($post->post_name, array('bc-tide-prediction-calendar', 'tide-calendar'))) {
            return true;
        }
        
        if (in_array($post->post_title, array('BC Tide Prediction Calendar', 'Tide Calendar'))) {
            return true;
        }
        
        // Generic custom template detection for other users
        $template = get_page_template_slug($post->ID);
        if (!empty($template) && $template !== 'default') {
            return true;
        }
        
        $page_template = get_post_meta($post->ID, '_wp_page_template', true);
        if (!empty($page_template) && $page_template !== 'default') {
            return true;
        }
        
        return false;
    }
    
    /**
     * Enqueue CSS and JavaScript
     */
    public function enqueue_scripts() {
        $css_url = SIMPLE_TOC_PLUGIN_URL . 'assets/cp-simple-toc.css';
        $js_url = SIMPLE_TOC_PLUGIN_URL . 'assets/cp-simple-toc.js';
        
        if (!empty($css_url) && !empty(SIMPLE_TOC_PLUGIN_URL)) {
            wp_enqueue_style('simple-toc-css', $css_url, array(), SIMPLE_TOC_VERSION);
        }
        
        if (!empty($js_url) && !empty(SIMPLE_TOC_PLUGIN_URL)) {
            wp_enqueue_script('simple-toc-js', $js_url, array('jquery'), SIMPLE_TOC_VERSION, true);
        }
    }
    
    /**
     * TOC Shortcode Handler
     */
    public function toc_shortcode($atts) {
        global $post;
        
        // Return empty if not in a post/page or post is null
        if (!is_object($post) || empty($post->ID)) {
            return '';
        }
        
        // Ensure options is an array with null check
        if (!is_array($this->options)) {
            $this->options = array();
        }
        
        // Parse shortcode attributes with comprehensive null checks
        $default_list_type = (!empty($this->options['list_type']) && is_string($this->options['list_type'])) ? $this->options['list_type'] : 'ul';
        $default_collapsed = (!empty($this->options['default_state']) && $this->options['default_state'] === 'collapsed') ? 'true' : 'false';
        
        $atts = shortcode_atts(array(
            'list_type' => $default_list_type,
            'collapsed' => $default_collapsed
        ), $atts);
        
        // Get content to parse
        $content = $this->get_content_for_parsing();
        
        if (empty($content)) {
            return '';
        }
        
        // Find all headings
        $headings = $this->extract_headings($content);
        
        if (empty($headings)) {
            return '';
        }
        
        // Generate table of contents HTML
        $toc_html = $this->generate_toc_html($headings, $atts);
        
        // Add IDs to actual headings in content on next page load
        add_filter('the_content', array($this, 'add_heading_ids'), 99);
        
        // For custom templates, also add a JavaScript solution to add IDs
        if ($this->is_custom_template_page()) {
            $toc_html .= $this->add_heading_ids_script($headings);
        }
        
        return $toc_html;
    }
    
    /**
     * Get content for parsing based on page type
     */
    private function get_content_for_parsing() {
        global $post;
        
        if ($this->is_custom_template_page()) {
            // For custom templates, we need to get the content differently
            // First try to get content from captured output buffer
            if (!empty($this->custom_content) && is_string($this->custom_content)) {
                // Extract content from the captured output
                preg_match('/<div[^>]*class="[^"]*entry-content[^"]*"[^>]*>(.*?)<\/div>/s', $this->custom_content, $matches);
                if (!empty($matches[1]) && is_string($matches[1])) {
                    return $matches[1];
                }
                
                // If no entry-content div found, try to get the main content area
                preg_match('/<main[^>]*>(.*?)<\/main>/s', $this->custom_content, $matches);
                if (!empty($matches[1]) && is_string($matches[1])) {
                    return $matches[1];
                }
                
                // Fallback: return the entire captured content
                return $this->custom_content;
            }
            
            // If output buffer is empty, try alternative methods
            return $this->get_custom_template_content();
        } else {
            // Regular post/page content
            if (is_object($post) && isset($post->post_content)) {
                $content = $post->post_content;
                if (!empty($content) && is_string($content)) {
                    $content = preg_replace('/\[toc[^\]]*\]/', '', $content);
                    return do_shortcode($content);
                }
            }
        }
        
        return '';
    }
    
    /**
     * Get content from custom template
     * This is a fallback method for when content can't be captured automatically
     */
    private function get_custom_template_content() {
        global $post;
        
        // Try to get the template file and parse it for headings
        $template_file = get_page_template();
        
        if (!empty($template_file) && is_string($template_file) && file_exists($template_file)) {
            $template_content = file_get_contents($template_file);
            
            if ($template_content !== false && !empty($template_content)) {
                // Extract any hardcoded HTML content that contains headings
                preg_match_all('/<h[1-6][^>]*>.*?<\/h[1-6]>/i', $template_content, $matches);
                
                if (!empty($matches[0]) && is_array($matches[0])) {
                    return implode("\n", $matches[0]);
                }
            }
        }
        
        // Final fallback: use post content
        if (is_object($post) && isset($post->post_content) && !empty($post->post_content)) {
            $content = $post->post_content;
            $content = preg_replace('/\[toc[^\]]*\]/', '', $content);
            return do_shortcode($content);
        }
        
        return '';
    }
    
    /**
     * Add JavaScript to add IDs to headings for custom templates
     */
    private function add_heading_ids_script($headings) {
        $script = '<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            // Add IDs to headings for TOC functionality
            var headings = document.querySelectorAll("h1, h2, h3, h4, h5, h6");
            var headingData = ' . json_encode($headings) . ';
            var headingIndex = 0;
            
            headings.forEach(function(heading) {
                if (!heading.id && headingIndex < headingData.length) {
                    var headingText = heading.textContent.trim();
                    var expectedText = headingData[headingIndex].text;
                    
                    // Simple text matching (remove emojis and extra spaces)
                    var cleanText = headingText.replace(/[^\w\s]/g, "").replace(/\s+/g, " ").trim();
                    var cleanExpected = expectedText.replace(/[^\w\s]/g, "").replace(/\s+/g, " ").trim();
                    
                    if (cleanText.toLowerCase().includes(cleanExpected.toLowerCase()) || 
                        cleanExpected.toLowerCase().includes(cleanText.toLowerCase())) {
                        heading.id = headingData[headingIndex].id;
                        headingIndex++;
                    }
                }
            });
        });
        </script>';
        
        return $script;
    }
    
    /**
     * Extract headings from content
     */
    private function extract_headings($content) {
        $headings = array();
        
        // Comprehensive null and type checking for content
        if (empty($content) || !is_string($content)) {
            return $headings;
        }
        
        // Get enabled heading levels from settings with null checks
        $enabled_levels = (isset($this->options['heading_levels']) && is_array($this->options['heading_levels'])) 
                         ? $this->options['heading_levels'] 
                         : array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        
        // Additional null check for enabled_levels
        if (empty($enabled_levels) || !is_array($enabled_levels)) {
            $enabled_levels = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        }
        
        // Convert to numeric levels for regex with validation
        $level_numbers = array();
        foreach ($enabled_levels as $level) {
            if (!empty($level) && is_string($level) && preg_match('/h([1-6])/', $level, $matches)) {
                if (!empty($matches[1]) && is_numeric($matches[1])) {
                    $level_numbers[] = $matches[1];
                }
            }
        }
        
        if (empty($level_numbers)) {
            return $headings;
        }
        
        $level_pattern = '[' . implode('', $level_numbers) . ']';
        
        // Match only enabled heading levels with error handling
        $match_result = preg_match_all('/<h(' . $level_pattern . ')[^>]*>(.*?)<\/h[1-6]>/i', $content, $matches, PREG_SET_ORDER);
        
        if ($match_result === false || empty($matches) || !is_array($matches)) {
            return $headings;
        }
        
        foreach ($matches as $match) {
            if (!is_array($match) || count($match) < 3) {
                continue;
            }
            
            $level = intval($match[1]);
            $text = strip_tags($match[2]);
            
            // Additional null check for text
            if (empty($text) || !is_string($text)) {
                continue;
            }
            
            $id = $this->generate_heading_id($text);
            
            if (!empty($id) && is_string($id)) {
                $headings[] = array(
                    'level' => $level,
                    'text' => $text,
                    'id' => $id
                );
            }
        }
        
        return $headings;
    }
    
    /**
     * Generate unique ID for heading
     */
    private function generate_heading_id($text) {
        // Comprehensive null and type checking
        if (empty($text) || !is_string($text)) {
            return 'heading-' . uniqid();
        }
        
        $id = strtolower($text);
        // Remove emojis and special characters
        $id = preg_replace('/[^\w\s-]/', '', $id);
        $id = preg_replace('/\s+/', '-', $id);
        $id = trim($id, '-');
        
        // If ID becomes empty after cleaning, generate a unique one
        if (empty($id)) {
            return 'heading-' . uniqid();
        }
        
        // Ensure uniqueness
        static $used_ids = array();
        $original_id = $id;
        $counter = 1;
        
        while (in_array($id, $used_ids)) {
            $id = $original_id . '-' . $counter;
            $counter++;
        }
        
        $used_ids[] = $id;
        return $id;
    }
    
    /**
     * Generate TOC HTML
     */
    private function generate_toc_html($headings, $atts) {
        $list_tag = ($atts['list_type'] === 'ol') ? 'ol' : 'ul';
        $collapsed_class = ($atts['collapsed'] === 'true') ? ' collapsed' : '';
        $toggle_icon = ($atts['collapsed'] === 'true') ? '▶' : '▼';
        
        // Get custom title from settings, with fallback
        $toc_title = isset($this->options['custom_title']) && !empty($this->options['custom_title']) 
                     ? $this->options['custom_title'] 
                     : 'Table Of Contents';
        
        $html = '<div class="simple-toc-container">';
        $html .= '<div class="simple-toc-header">';
        $html .= '<span class="simple-toc-toggle">' . $toggle_icon . '</span>';
        $html .= '<span class="simple-toc-title">' . esc_html($toc_title) . '</span>';
        $html .= '</div>';
        $html .= '<div class="simple-toc-content' . $collapsed_class . '">';
        $html .= '<' . $list_tag . ' class="simple-toc-list">';
        
        $current_level = 0;
        $open_lists = array();
        
        foreach ($headings as $heading) {
            $level = $heading['level'];
            
            if ($level > $current_level) {
                // Opening new nested levels
                for ($i = $current_level; $i < $level; $i++) {
                    if ($i > 0) {
                        $html .= '<' . $list_tag . ' class="simple-toc-sublist">';
                        $open_lists[] = $list_tag;
                    }
                }
            } elseif ($level < $current_level) {
                // Closing nested levels
                for ($i = $current_level; $i > $level; $i--) {
                    if (!empty($open_lists)) {
                        $close_tag = array_pop($open_lists);
                        $html .= '</' . $close_tag . '></li>';
                    }
                }
            } elseif ($current_level > 0) {
                // Same level, close previous item
                $html .= '</li>';
            }
            
            $html .= '<li><a href="#' . $heading['id'] . '" class="simple-toc-link">' . esc_html($heading['text']) . '</a>';
            $current_level = $level;
        }
        
        // Close remaining open lists
        while (!empty($open_lists)) {
            $close_tag = array_pop($open_lists);
            $html .= '</' . $close_tag . '></li>';
        }
        
        $html .= '</li>';
        $html .= '</' . $list_tag . '>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Add IDs to headings in content
     */
    public function add_heading_ids($content) {
        static $processed_posts = array();
        global $post;
        
        // Check if this post has already been processed
        if (isset($processed_posts[$post->ID])) {
            return $content;
        }
        
        $processed_posts[$post->ID] = true;
        
        // Reset the used IDs for each post
        static $used_ids = array();
        $used_ids = array();
        
        $content = preg_replace_callback(
            '/<h([1-6])[^>]*>(.*?)<\/h[1-6]>/i',
            array($this, 'add_id_to_heading_callback'),
            $content
        );
        
        return $content;
    }
    
    /**
     * Callback function for adding IDs to headings
     */
    public function add_id_to_heading_callback($matches) {
        $level = $matches[1];
        $text = strip_tags($matches[2]);
        $id = $this->generate_heading_id($text);
        
        // Check if heading already has an ID
        if (preg_match('/id\s*=\s*["\'][^"\']*["\']/i', $matches[0])) {
            return $matches[0]; // Return unchanged if ID already exists
        }
        
        return '<h' . $level . ' id="' . $id . '">' . $matches[2] . '</h' . $level . '>';
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            'Simple TOC Settings',
            'Simple TOC',
            'manage_options',
            'simple-toc',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>Simple Table of Contents Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('simple_toc_settings');
                do_settings_sections('simple_toc_settings');
                submit_button();
                ?>
            </form>
            
            <div style="margin-top: 30px; padding: 20px; background: #f1f1f1; border-radius: 5px;">
                <h3>How to Use</h3>
                <p><strong>To add a table of contents to any post or page:</strong></p>
                <p>Simply add the shortcode <code>[toc]</code> wherever you want the table of contents to appear.</p>
                
                <h4>Shortcode Options:</h4>
                <ul>
                    <li><code>[toc]</code> - Uses default settings</li>
                    <li><code>[toc list_type="ol"]</code> - Force ordered list (numbered)</li>
                    <li><code>[toc list_type="ul"]</code> - Force unordered list (bullets)</li>
                    <li><code>[toc collapsed="true"]</code> - Start collapsed</li>
                    <li><code>[toc collapsed="false"]</code> - Start expanded</li>
                </ul>
                
                <h4>Custom Template Support:</h4>
                <p>This plugin automatically supports custom templates and will generate TOCs for pages using custom template files. It works by detecting custom templates and parsing their content for headings.</p>
                
                <h4>Color Customization:</h4>
                <p>Use the color options above to match your brand colors. Changes will be applied site-wide to all TOCs.</p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Initialize admin settings
     */
    public function admin_init() {
        register_setting('simple_toc_settings', 'simple_toc_options', array($this, 'sanitize_options'));
        
        // Basic Settings Section
        add_settings_section(
            'simple_toc_main_section',
            'Basic Settings',
            array($this, 'settings_section_callback'),
            'simple_toc_settings'
        );
        
        add_settings_field(
            'list_type',
            'Default List Type',
            array($this, 'list_type_callback'),
            'simple_toc_settings',
            'simple_toc_main_section'
        );
        
        add_settings_field(
            'default_state',
            'Default State',
            array($this, 'default_state_callback'),
            'simple_toc_settings',
            'simple_toc_main_section'
        );
        
        add_settings_field(
            'heading_levels',
            'Heading Levels to Include',
            array($this, 'heading_levels_callback'),
            'simple_toc_settings',
            'simple_toc_main_section'
        );
        
        add_settings_field(
            'custom_title',
            'TOC Title',
            array($this, 'custom_title_callback'),
            'simple_toc_settings',
            'simple_toc_main_section'
        );
        
        // Color Settings Section
        add_settings_section(
            'simple_toc_color_section',
            'Color Customization',
            array($this, 'color_section_callback'),
            'simple_toc_settings'
        );
        
        add_settings_field(
            'background_color',
            'Background Color',
            array($this, 'background_color_callback'),
            'simple_toc_settings',
            'simple_toc_color_section'
        );
        
        add_settings_field(
            'text_color',
            'Text Color',
            array($this, 'text_color_callback'),
            'simple_toc_settings',
            'simple_toc_color_section'
        );
        
        add_settings_field(
            'link_color',
            'Link Color',
            array($this, 'link_color_callback'),
            'simple_toc_settings',
            'simple_toc_color_section'
        );
        
        add_settings_field(
            'link_hover_color',
            'Link Hover Color',
            array($this, 'link_hover_color_callback'),
            'simple_toc_settings',
            'simple_toc_color_section'
        );
        
        add_settings_field(
            'border_color',
            'Border/Hover Color',
            array($this, 'border_color_callback'),
            'simple_toc_settings',
            'simple_toc_color_section'
        );
        
        add_settings_field(
            'bullet_color',
            'Bullet Color',
            array($this, 'bullet_color_callback'),
            'simple_toc_settings',
            'simple_toc_color_section'
        );
        
        // Typography Settings Section
        add_settings_section(
            'simple_toc_typography_section',
            'Typography Settings',
            array($this, 'typography_section_callback'),
            'simple_toc_settings'
        );
        
        add_settings_field(
            'font_family',
            'Font Family',
            array($this, 'font_family_callback'),
            'simple_toc_settings',
            'simple_toc_typography_section'
        );
        
        add_settings_field(
            'title_font_size',
            'Title Font Size',
            array($this, 'title_font_size_callback'),
            'simple_toc_settings',
            'simple_toc_typography_section'
        );
        
        add_settings_field(
            'title_font_weight',
            'Title Font Weight',
            array($this, 'title_font_weight_callback'),
            'simple_toc_settings',
            'simple_toc_typography_section'
        );
        
        add_settings_field(
            'link_font_size',
            'Link Font Size',
            array($this, 'link_font_size_callback'),
            'simple_toc_settings',
            'simple_toc_typography_section'
        );
        
        add_settings_field(
            'link_font_weight',
            'Link Font Weight',
            array($this, 'link_font_weight_callback'),
            'simple_toc_settings',
            'simple_toc_typography_section'
        );
    }
    
    /**
     * Sanitize options
     */
    public function sanitize_options($input) {
        $sanitized = array();
        
        // Basic settings
        $sanitized['list_type'] = isset($input['list_type']) ? sanitize_text_field($input['list_type']) : 'ul';
        $sanitized['default_state'] = isset($input['default_state']) ? sanitize_text_field($input['default_state']) : 'expanded';
        
        // Heading levels - sanitize array
        if (isset($input['heading_levels']) && is_array($input['heading_levels'])) {
            $sanitized['heading_levels'] = array();
            $valid_levels = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
            foreach ($input['heading_levels'] as $level) {
                if (in_array($level, $valid_levels)) {
                    $sanitized['heading_levels'][] = $level;
                }
            }
            // Ensure at least one level is selected
            if (empty($sanitized['heading_levels'])) {
                $sanitized['heading_levels'] = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
            }
        } else {
            $sanitized['heading_levels'] = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        }
        
        $sanitized['custom_title'] = isset($input['custom_title']) ? sanitize_text_field($input['custom_title']) : 'Table Of Contents';
        
        // Color settings - validate hex colors
        $colors = array('background_color', 'text_color', 'link_color', 'link_hover_color', 'border_color', 'bullet_color');
        $color_defaults = array(
            'background_color' => '#7cb894',
            'text_color' => '#2d5a3d',
            'link_color' => '#2d5a3d',
            'link_hover_color' => '#1a3d26',
            'border_color' => '#6da582',
            'bullet_color' => '#4a7c59'
        );
        
        foreach ($colors as $color) {
            if (isset($input[$color]) && preg_match('/^#[a-f0-9]{6}$/i', $input[$color])) {
                $sanitized[$color] = $input[$color];
            } else {
                $sanitized[$color] = $color_defaults[$color];
            }
        }
        
        // Typography settings
        $sanitized['font_family'] = isset($input['font_family']) ? sanitize_text_field($input['font_family']) : 'inherit';
        
        // Font sizes (validate range 10-30px)
        $sanitized['title_font_size'] = isset($input['title_font_size']) ? max(10, min(30, intval($input['title_font_size']))) : 16;
        $sanitized['link_font_size'] = isset($input['link_font_size']) ? max(10, min(24, intval($input['link_font_size']))) : 14;
        
        // Font weights (validate common values)
        $valid_weights = array('100', '200', '300', '400', '500', '600', '700', '800', '900', 'normal', 'bold');
        $sanitized['title_font_weight'] = isset($input['title_font_weight']) && in_array($input['title_font_weight'], $valid_weights) ? $input['title_font_weight'] : '500';
        $sanitized['link_font_weight'] = isset($input['link_font_weight']) && in_array($input['link_font_weight'], $valid_weights) ? $input['link_font_weight'] : '400';
        
        return $sanitized;
    }
    
    public function settings_section_callback() {
        echo '<p>Configure the default behavior and appearance for your table of contents.</p>';
    }
    
    public function color_section_callback() {
        echo '<p>Customize the colors to match your brand. Click on any color box to open the color picker.</p>';
    }
    
    public function typography_section_callback() {
        echo '<p>Customize the typography to match your site\'s design. Font sizes are in pixels.</p>';
    }
    
    public function list_type_callback() {
        $list_type = isset($this->options['list_type']) ? $this->options['list_type'] : 'ul';
        ?>
        <select name="simple_toc_options[list_type]">
            <option value="ul" <?php selected($list_type, 'ul'); ?>>Unordered List (bullets)</option>
            <option value="ol" <?php selected($list_type, 'ol'); ?>>Ordered List (numbers)</option>
        </select>
        <?php
    }
    
    public function default_state_callback() {
        $default_state = isset($this->options['default_state']) ? $this->options['default_state'] : 'expanded';
        ?>
        <select name="simple_toc_options[default_state]">
            <option value="expanded" <?php selected($default_state, 'expanded'); ?>>Expanded</option>
            <option value="collapsed" <?php selected($default_state, 'collapsed'); ?>>Collapsed</option>
        </select>
        <?php
    }
    
    public function heading_levels_callback() {
        $heading_levels = isset($this->options['heading_levels']) && is_array($this->options['heading_levels']) 
                         ? $this->options['heading_levels'] 
                         : array('h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        
        $level_labels = array(
            'h1' => 'H1 - Main Headings',
            'h2' => 'H2 - Major Sections', 
            'h3' => 'H3 - Subsections',
            'h4' => 'H4 - Sub-subsections',
            'h5' => 'H5 - Minor Headings',
            'h6' => 'H6 - Small Headings'
        );
        
        echo '<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; max-width: 500px;">';
        
        foreach ($level_labels as $level => $label) {
            $checked = in_array($level, $heading_levels) ? 'checked="checked"' : '';
            echo '<label style="display: flex; align-items: center; padding: 8px; background: #f9f9f9; border-radius: 4px;">';
            echo '<input type="checkbox" name="simple_toc_options[heading_levels][]" value="' . esc_attr($level) . '" ' . $checked . ' style="margin-right: 8px;" />';
            echo '<span>' . esc_html($label) . '</span>';
            echo '</label>';
        }
        
        echo '</div>';
        echo '<p class="description">Select which heading levels to include in the table of contents. At least one level must be selected.</p>';
    }
    
    public function custom_title_callback() {
        $custom_title = isset($this->options['custom_title']) ? $this->options['custom_title'] : 'Table Of Contents';
        ?>
        <input type="text" name="simple_toc_options[custom_title]" value="<?php echo esc_attr($custom_title); ?>" class="regular-text" placeholder="Table Of Contents" />
        <p class="description">Enter your custom title for the table of contents.</p>
        
        <div style="margin-top: 10px;">
            <strong>Popular Examples:</strong>
            <ul style="margin-left: 20px; margin-top: 5px;">
                <li><code>Contents</code> - Simple and clean</li>
                <li><code>On This Page</code> - Clear navigation indicator</li>
                <li><code>Jump To:</code> - Action-oriented</li>
                <li><code>Quick Navigation</code> - Descriptive</li>
                <li><code>In This Article</code> - Blog-friendly</li>
                <li><code>Topics Covered</code> - Educational content</li>
                <li><code>📖 Contents</code> - With emoji</li>
                <li><code>What's Inside</code> - Casual tone</li>
            </ul>
        </div>
        <?php
    }
    
    public function background_color_callback() {
        $color = isset($this->options['background_color']) ? $this->options['background_color'] : '#7cb894';
        ?>
        <input type="text" name="simple_toc_options[background_color]" value="<?php echo esc_attr($color); ?>" class="color-picker" />
        <p class="description">The main background color of the TOC container.</p>
        <?php
    }
    
    public function text_color_callback() {
        $color = isset($this->options['text_color']) ? $this->options['text_color'] : '#2d5a3d';
        ?>
        <input type="text" name="simple_toc_options[text_color]" value="<?php echo esc_attr($color); ?>" class="color-picker" />
        <p class="description">Color for the title text and arrow icon.</p>
        <?php
    }
    
    public function link_color_callback() {
        $color = isset($this->options['link_color']) ? $this->options['link_color'] : '#2d5a3d';
        ?>
        <input type="text" name="simple_toc_options[link_color]" value="<?php echo esc_attr($color); ?>" class="color-picker" />
        <p class="description">Default color for TOC links.</p>
        <?php
    }
    
    public function link_hover_color_callback() {
        $color = isset($this->options['link_hover_color']) ? $this->options['link_hover_color'] : '#1a3d26';
        ?>
        <input type="text" name="simple_toc_options[link_hover_color]" value="<?php echo esc_attr($color); ?>" class="color-picker" />
        <p class="description">Color for links when hovering over them.</p>
        <?php
    }
    
    public function border_color_callback() {
        $color = isset($this->options['border_color']) ? $this->options['border_color'] : '#6da582';
        ?>
        <input type="text" name="simple_toc_options[border_color]" value="<?php echo esc_attr($color); ?>" class="color-picker" />
        <p class="description">Color for borders and header hover effect.</p>
        <?php
    }
    
    public function bullet_color_callback() {
        $color = isset($this->options['bullet_color']) ? $this->options['bullet_color'] : '#4a7c59';
        ?>
        <input type="text" name="simple_toc_options[bullet_color]" value="<?php echo esc_attr($color); ?>" class="color-picker" />
        <p class="description">Color for the list bullets/markers in the table of contents.</p>
        <?php
    }
    
    public function font_family_callback() {
        $font_family = isset($this->options['font_family']) ? $this->options['font_family'] : 'inherit';
        ?>
        <select name="simple_toc_options[font_family]">
            <option value="inherit" <?php selected($font_family, 'inherit'); ?>>Inherit from theme</option>
            <option value="Arial, sans-serif" <?php selected($font_family, 'Arial, sans-serif'); ?>>Arial</option>
            <option value="Helvetica, Arial, sans-serif" <?php selected($font_family, 'Helvetica, Arial, sans-serif'); ?>>Helvetica</option>
            <option value="Georgia, serif" <?php selected($font_family, 'Georgia, serif'); ?>>Georgia</option>
            <option value="'Times New Roman', serif" <?php selected($font_family, "'Times New Roman', serif"); ?>>Times New Roman</option>
            <option value="Verdana, sans-serif" <?php selected($font_family, 'Verdana, sans-serif'); ?>>Verdana</option>
            <option value="Tahoma, sans-serif" <?php selected($font_family, 'Tahoma, sans-serif'); ?>>Tahoma</option>
            <option value="'Trebuchet MS', sans-serif" <?php selected($font_family, "'Trebuchet MS', sans-serif"); ?>>Trebuchet MS</option>
            <option value="'Courier New', monospace" <?php selected($font_family, "'Courier New', monospace"); ?>>Courier New</option>
            <option value="'Lucida Sans Unicode', sans-serif" <?php selected($font_family, "'Lucida Sans Unicode', sans-serif"); ?>>Lucida Sans Unicode</option>
            <option value="Impact, sans-serif" <?php selected($font_family, 'Impact, sans-serif'); ?>>Impact</option>
            <option value="'Comic Sans MS', cursive" <?php selected($font_family, "'Comic Sans MS', cursive"); ?>>Comic Sans MS</option>
        </select>
        <p class="description">Choose a font family for the entire TOC.</p>
        <?php
    }
    
    public function title_font_size_callback() {
        $size = isset($this->options['title_font_size']) ? $this->options['title_font_size'] : '16';
        ?>
        <input type="number" name="simple_toc_options[title_font_size]" value="<?php echo esc_attr($size); ?>" min="10" max="30" step="1" />
        <span class="description">px (range: 10-30)</span>
        <p class="description">Font size for the "Table Of Contents" title.</p>
        <?php
    }
    
    public function title_font_weight_callback() {
        $weight = isset($this->options['title_font_weight']) ? $this->options['title_font_weight'] : '500';
        ?>
        <select name="simple_toc_options[title_font_weight]">
            <option value="100" <?php selected($weight, '100'); ?>>100 - Thin</option>
            <option value="200" <?php selected($weight, '200'); ?>>200 - Extra Light</option>
            <option value="300" <?php selected($weight, '300'); ?>>300 - Light</option>
            <option value="400" <?php selected($weight, '400'); ?>>400 - Normal</option>
            <option value="500" <?php selected($weight, '500'); ?>>500 - Medium</option>
            <option value="600" <?php selected($weight, '600'); ?>>600 - Semi Bold</option>
            <option value="700" <?php selected($weight, '700'); ?>>700 - Bold</option>
            <option value="800" <?php selected($weight, '800'); ?>>800 - Extra Bold</option>
            <option value="900" <?php selected($weight, '900'); ?>>900 - Black</option>
        </select>
        <p class="description">Font weight for the TOC title.</p>
        <?php
    }
    
    public function link_font_size_callback() {
        $size = isset($this->options['link_font_size']) ? $this->options['link_font_size'] : '14';
        ?>
        <input type="number" name="simple_toc_options[link_font_size]" value="<?php echo esc_attr($size); ?>" min="10" max="24" step="1" />
        <span class="description">px (range: 10-24)</span>
        <p class="description">Font size for the TOC links.</p>
        <?php
    }
    
    public function link_font_weight_callback() {
        $weight = isset($this->options['link_font_weight']) ? $this->options['link_font_weight'] : '400';
        ?>
        <select name="simple_toc_options[link_font_weight]">
            <option value="100" <?php selected($weight, '100'); ?>>100 - Thin</option>
            <option value="200" <?php selected($weight, '200'); ?>>200 - Extra Light</option>
            <option value="300" <?php selected($weight, '300'); ?>>300 - Light</option>
            <option value="400" <?php selected($weight, '400'); ?>>400 - Normal</option>
            <option value="500" <?php selected($weight, '500'); ?>>500 - Medium</option>
            <option value="600" <?php selected($weight, '600'); ?>>600 - Semi Bold</option>
            <option value="700" <?php selected($weight, '700'); ?>>700 - Bold</option>
            <option value="800" <?php selected($weight, '800'); ?>>800 - Extra Bold</option>
            <option value="900" <?php selected($weight, '900'); ?>>900 - Black</option>
        </select>
        <p class="description">Font weight for the TOC links.</p>
        <?php
    }
}

// Initialize the plugin
new SimpleTOC();

/**
 * Activation hook
 */
register_activation_hook(__FILE__, 'simple_toc_activate');
function simple_toc_activate() {
    // Set default options including new typography options
    $default_options = array(
        'list_type' => 'ul',
        'default_state' => 'expanded',
        'heading_levels' => array('h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
        'background_color' => '#7cb894',
        'text_color' => '#2d5a3d',
        'link_color' => '#2d5a3d',
        'link_hover_color' => '#1a3d26',
        'border_color' => '#6da582',
        'bullet_color' => '#4a7c59',
        'font_family' => 'inherit',
        'title_font_size' => '16',
        'title_font_weight' => '500',
        'link_font_size' => '14',
        'link_font_weight' => '400',
        'custom_title' => 'Table Of Contents'
    );
    
    add_option('simple_toc_options', $default_options);
}

/**
 * Deactivation hook
 */
register_deactivation_hook(__FILE__, 'simple_toc_deactivate');
function simple_toc_deactivate() {
    // Clean up if needed
}

?>