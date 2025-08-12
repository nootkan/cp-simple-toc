# Simple Table of Contents

A lightweight, highly customizable table of contents plugin for ClassicPress that automatically generates TOCs from your page headings with extensive styling options and live preview functionality.

## Features

### Core Functionality
- **Automatic TOC Generation** - Scans your content for headings and creates navigation links
- **Customizable Heading Levels** - Choose which heading levels (H1-H6) to include
- **Collapsible Interface** - Users can expand/collapse the TOC
- **Smooth Scrolling** - Seamless navigation to page sections
- **Custom Template Support** - Works with custom page templates and themes

### Design Customization
- **Complete Color Control** - Background, text, links, hover states, borders, and bullet colors
- **Typography Options** - Font family, sizes, and weights for title and links
- **Custom Titles** - Set your own TOC title with popular examples provided
- **List Types** - Choose between numbered (ordered) or bulleted (unordered) lists
- **Live Preview** - Real-time preview of changes in admin settings
- **Mobile Responsive** - Optimized display across all device sizes

### Advanced Features
- **Reset to Defaults** - One-click restoration of original settings
- **Shortcode Flexibility** - Override settings on individual pages
- **Accessibility Support** - ARIA attributes and keyboard navigation
- **Active Section Highlighting** - Current section highlighting while scrolling
- **Multiple Installation Methods** - Works from plugins directory or theme folder

## Installation

### Method 1: Plugin Directory (Recommended)
1. Upload the `simple-table-of-contents` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in ClassicPress
3. Go to Settings > Simple TOC to configure

### Method 2: Theme Integration
1. Upload the plugin folder to your active theme directory
2. The plugin will automatically detect the theme installation
3. Access settings through Settings > Simple TOC

## Basic Usage

### Quick Start
Add the shortcode wherever you want a table of contents to appear:

```
[toc]
```

That's it! The plugin will automatically:
- Scan your page for headings
- Generate a table of contents
- Add navigation links
- Apply your custom styling

### Shortcode Options

#### Basic Options
```
[toc]                           // Uses all default settings
[toc list_type="ol"]           // Numbered list (1, 2, 3...)
[toc list_type="ul"]           // Bulleted list (default)
[toc collapsed="true"]         // Start collapsed
[toc collapsed="false"]        // Start expanded (default)
```

#### Combined Options
```
[toc list_type="ol" collapsed="true"]  // Numbered list, starts collapsed
```

## Configuration

### Basic Settings

#### List Type
- **Unordered List (bullets)** - Uses bullet points for navigation
- **Ordered List (numbers)** - Uses sequential numbering

#### Default State
- **Expanded** - TOC shows content by default
- **Collapsed** - TOC starts minimized, users click to expand

#### Heading Levels
Select which heading levels to include:
- **H1** - Main Headings
- **H2** - Major Sections  
- **H3** - Subsections
- **H4** - Sub-subsections
- **H5** - Minor Headings
- **H6** - Small Headings

#### Custom Title
Set your own TOC title. Popular examples:
- `Contents` - Simple and clean
- `On This Page` - Clear navigation indicator
- `Jump To:` - Action-oriented
- `Quick Navigation` - Descriptive
- `In This Article` - Blog-friendly
- `Topics Covered` - Educational content
- `📖 Contents` - With emoji
- `What's Inside` - Casual tone

### Color Customization

#### Available Color Options
- **Background Color** - Main container background
- **Text Color** - Title text and toggle arrow
- **Link Color** - Default TOC link color
- **Link Hover Color** - Color when hovering over links
- **Border/Hover Color** - Header hover effect and borders
- **Bullet Color** - List marker/bullet color

#### Color Picker Interface
- Click any color box to open the color picker
- Changes appear immediately in the live preview
- Use hex color codes for precise control

### Typography Settings

#### Font Family Options
- Inherit from theme (recommended)
- Arial, Helvetica, Georgia, Times New Roman
- Verdana, Tahoma, Trebuchet MS
- Courier New, Lucida Sans Unicode
- Impact, Comic Sans MS

#### Font Sizing
- **Title Font Size** - 10-30px range
- **Link Font Size** - 10-24px range

#### Font Weights
- 100 (Thin) through 900 (Black)
- Common weights: 400 (Normal), 500 (Medium), 700 (Bold)

### Live Preview
- Real-time preview shows changes instantly
- Test different color combinations
- Preview mobile and desktop appearance
- See typography changes immediately

### Reset to Defaults
- One-click restoration of all original settings
- Confirms before resetting
- Restores colors, typography, and titles
- Updates live preview automatically

## Advanced Usage

### Custom Templates
The plugin automatically detects and works with:
- Custom page templates
- Theme-specific templates
- Complex content structures
- Generated content from other plugins

### Accessibility Features
- ARIA attributes for screen readers
- Keyboard navigation support
- Focus indicators for better usability
- Semantic HTML structure

### Mobile Optimization
- Responsive design across all devices
- Touch-friendly interface
- Optimized spacing for mobile screens
- Consistent functionality on all platforms

## File Structure

```
simple-table-of-contents/
├── simple-table-of-contents.php    // Main plugin file
└── assets/
    ├── cp-simple-toc.css           // Frontend styles
    ├── cp-simple-toc.js            // Frontend functionality
    └── cp-simple-toc-admin.js      // Admin interface & live preview
```

## Troubleshooting

### TOC Not Appearing
1. Ensure `[toc]` shortcode is added to your content
2. Check that your content has headings (H1-H6)
3. Verify selected heading levels include your content's headings
4. Try different heading level combinations

### Styling Issues
1. Use the live preview to test changes
2. Save settings after making changes
3. Clear any caching plugins
4. Check for theme CSS conflicts

### Mobile Display Problems
1. Test on actual mobile devices
2. Check responsive breakpoints
3. Verify mobile-specific CSS is loading
4. Clear mobile browser cache

### Custom Template Issues
1. Ensure template has proper heading structure
2. Check for content div classes
3. Verify template compatibility
4. Test with default templates first

## Technical Requirements

### System Requirements
- ClassicPress 1.0 or higher
- PHP 7.4 or higher (PHP 8+ compatible)
- Modern web browser support

### Browser Support
- Chrome, Firefox, Safari, Edge (latest versions)
- Mobile browsers (iOS Safari, Chrome Mobile)
- Graceful degradation for older browsers

### Performance
- Lightweight code with minimal impact
- Optimized for fast loading
- No external dependencies
- Efficient DOM manipulation

## Customization Examples

### Professional Blog
```css
Title: "In This Article"
Font: Georgia, 16px, Medium
Colors: Dark blue theme
List Type: Numbered
```

### Documentation Site
```css
Title: "Contents"
Font: Arial, 14px, Normal  
Colors: Gray scale theme
List Type: Bulleted
```

### Creative Blog
```css
Title: "🎯 Quick Navigation"
Font: Trebuchet MS, 15px, Bold
Colors: Bright accent colors
List Type: Bulleted
```

## Support & Development

### Getting Help
1. Check this README for common solutions
2. Test with default settings first
3. Verify plugin file integrity
4. Check ClassicPress compatibility

### Contributing
- Report bugs with detailed reproduction steps
- Suggest features with use cases
- Test new versions before release
- Provide feedback on usability

## Changelog

### Version 1.2.0
- Added bullet color customization
- Implemented comprehensive null value checking
- Enhanced mobile responsiveness
- Improved custom template support
- Added heading level selection
- Enhanced live preview functionality
- Added reset to defaults feature
- Improved accessibility support

## License

GPL v2 or later - Use freely for personal and commercial projects.

## Credits

**Developed by:** Van Isle Web Solutions  
**Compatible with:** ClassicPress  
**Tested up to:** ClassicPress 2.0  

---

*Simple Table of Contents - Making navigation simple and beautiful.*