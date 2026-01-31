# Digital Prophets Stage Slider

A WordPress plugin that creates stunning stage-themed sliders for your music content, integrating Sonaar MP3 Audio Player with Slider Revolution.

## Description

Digital Prophets Stage Slider transforms your music showcase with dynamic, visually stunning stage presentations. Perfect for musicians, DJs, podcasters, and music blogs, this plugin automatically pulls your latest tracks from Sonaar and displays them in a cinematic stage environment with parallax effects, Ken Burns animations, and interactive spotlights.

## Features

### 🎵 **Song of the Day**
- Automatically or manually select daily featured songs
- Schedule songs with date-specific features
- Fallback to newest song if no selection made
- Custom taxonomy for organizing song collections

### 🎨 **Stage Themes**
- **Default Stage**: Classic concert lighting
- **Concert Hall**: Elegant auditorium ambiance
- **Club Lights**: Vibrant nightclub atmosphere
- **Outdoor Festival**: Open-air summer vibes
- **Minimal**: Clean, modern presentation
- **Custom**: Upload your own stage overlays

### ✨ **Visual Effects**
- **Parallax Mouse Effects**: Interactive 3D depth
- **Ken Burns Animation**: Smooth zoom and pan on images
- **Animated Spotlights**: Pulsing stage lighting effects
- **Particle Systems**: Floating golden particles
- **Gradient Overlays**: Professional photo treatments

### 🎛️ **Integration**
- **Sonaar MP3 Player**: Seamless audio playback
- **Slider Revolution**: Professional slider management
- **Elementor Compatible**: Drop-in widget support
- **WordPress Widgets**: Sidebar and widget areas
- **Shortcodes**: Flexible placement anywhere

## Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- [Sonaar MP3 Audio Player](https://wordpress.org/plugins/sonaar-music/) (Free or Pro)
- [Slider Revolution](https://www.sliderrevolution.com/) (Optional but recommended)

## Installation

1. **Upload the Plugin**
   - Download `digital-prophets-stage-slider.zip`
   - Go to WordPress Admin → Plugins → Add New → Upload Plugin
   - Choose the zip file and click "Install Now"
   - Activate the plugin

2. **Install Dependencies**
   - Install and activate Sonaar MP3 Audio Player
   - (Optional) Install and activate Slider Revolution for enhanced features

3. **Initial Setup**
   - Go to WordPress Admin → Stage Slider → Settings
   - Configure your default theme and preferences
   - Select auto-selection category (optional)

## Usage

### Setting Up Song of the Day

1. **Edit Any Sonaar Post** (Album, Playlist, Podcast, etc.)
2. Look for the **"Stage Slider Settings"** meta box
3. Check **"Set as Song of the Day"**
4. Choose a **Stage Theme**
5. (Optional) Set a specific **Featured Date**
6. Publish or Update

### Using Shortcodes

#### Main Stage Slider
```php
[dp_song_stage height="800" theme="concert-hall" autoplay="false"]
```

**Parameters:**
- `height` - Slider height in pixels (default: 800)
- `theme` - Stage theme (default, concert-hall, club-lights, outdoor-festival, minimal)
- `autoplay` - Auto-play audio (true/false, default: false)
- `controls` - Show play button (true/false, default: true)

#### Song of the Day Card
```php
[dp_song_of_day layout="card" show_player="true" show_artwork="true"]
```

**Parameters:**
- `layout` - Display layout (card, minimal, default: card)
- `show_player` - Include audio player (true/false, default: true)
- `show_artwork` - Show album artwork (true/false, default: true)
- `show_info` - Show song information (true/false, default: true)

#### Latest Songs Grid
```php
[dp_latest_songs count="6" columns="3" category="featured"]
```

**Parameters:**
- `count` - Number of songs to display (default: 6)
- `columns` - Grid columns (2, 3, 4, default: 3)
- `category` - Song collection slug (optional)
- `orderby` - Sort by (date, title, random, default: date)
- `order` - Sort order (ASC, DESC, default: DESC)

#### Sonaar Player Enhanced
```php
[dp_sonaar_player song_id="123" autoplay="false" theme="stage"]
```

**Parameters:**
- `song_id` - Specific song ID (optional, uses Song of the Day if empty)
- `autoplay` - Auto-play audio (true/false, default: false)
- `theme` - Visual theme (default, stage)
- `controls` - Player controls to show (all, minimal, default: all)

### Using Widgets

#### Song of the Day Widget
1. Go to Appearance → Widgets
2. Add **"Song of the Day"** widget to desired area
3. Configure:
   - Title
   - Layout (Card or Minimal)
   - Show/hide player
   - Show/hide artwork

#### Latest Songs Widget
1. Go to Appearance → Widgets
2. Add **"Latest Songs"** widget to desired area
3. Configure:
   - Title
   - Number of songs
   - Columns
   - Category filter

### Elementor Integration

The shortcodes work perfectly in Elementor:

1. Add a **Shortcode Widget**
2. Paste any of the shortcodes above
3. Customize in Elementor's visual editor

## Settings

### Appearance Settings
- **Default Stage Theme**: Choose default theme for new sliders
- **Slider Height**: Default height in pixels (300-2000)
- **Enable Parallax Effects**: Interactive mouse movement
- **Enable Ken Burns Effect**: Smooth zoom animation
- **Enable Spotlight Effects**: Animated stage lights

### Behavior Settings
- **Auto Advance**: Seconds before auto-advancing (0 = disable)
- **Auto-Select from Category**: Pull from specific category
- **Fallback to Newest Song**: Use latest if no selection

### Integration Settings
- **Sonaar Post Types**: Which post types to use for songs
- **Revolution Slider Alias**: Slider alias for RevSlider integration

## Customization

### Custom Stage Overlays

You can upload custom stage overlay images:

1. Edit a song post
2. In Stage Slider Settings, choose **"Custom"** theme
3. Click **"Upload Custom Overlay"**
4. Select your PNG image (transparent areas recommended)
5. The image will overlay your album artwork

### CSS Customization

Add custom styles via Appearance → Customize → Additional CSS:

```css
/* Change primary color */
:root {
    --dpss-primary: #your-color;
    --dpss-secondary: #your-secondary-color;
}

/* Customize song title */
.dpss-song-title {
    font-family: 'Your Font', sans-serif;
    font-size: 100px;
}

/* Adjust spotlight colors */
.dpss-spotlight {
    background: radial-gradient(circle, rgba(your-rgb, 0.3), transparent);
}
```

## Troubleshooting

### Slider Not Showing
- Verify Sonaar plugin is active
- Ensure at least one song is marked as "Song of the Day"
- Check that shortcode is correctly formatted
- Clear cache if using caching plugin

### Sonaar Player Not Working
- Update Sonaar to latest version
- Check that audio files are properly uploaded
- Verify Sonaar post type is selected in settings
- Test with Sonaar's native shortcode first

### Revolution Slider Issues
- RevSlider is optional - plugin works without it
- If using RevSlider, ensure alias matches in settings
- Check RevSlider is activated and licensed

### Styling Conflicts
- Try changing theme in settings
- Disable parallax/Ken Burns effects if causing issues
- Check for JavaScript errors in browser console
- Ensure jQuery is loaded

## Frequently Asked Questions

**Q: Do I need Slider Revolution?**
A: No, the plugin works standalone with beautiful CSS3 animations. RevSlider adds advanced options but is optional.

**Q: Can I use this with other audio players?**
A: The plugin is designed specifically for Sonaar, but you can adapt it with custom code.

**Q: How do I change songs daily automatically?**
A: Set "Auto-Select from Category" in settings, then add new songs to that category. The newest will be featured automatically.

**Q: Can I have multiple sliders on one page?**
A: Yes, use the shortcode multiple times or mix with widgets.

**Q: Is this mobile responsive?**
A: Yes, fully responsive with optimized layouts for all screen sizes.

**Q: Can I translate the plugin?**
A: Yes, it's translation-ready. Use Loco Translate or similar plugin.

## Changelog

### 1.0.0 (2024-01-15)
- Initial release
- Song of the Day functionality
- 5 built-in stage themes
- Sonaar integration
- Revolution Slider integration
- 4 shortcodes
- 2 widgets
- Admin settings panel
- Visual effects (parallax, Ken Burns, spotlights)

## Support

For support, feature requests, or bug reports:
- Email: support@digitalprophets.blog
- Website: https://digitalprophets.blog

## Credits

**Developed by**: Digital Prophets
**License**: GPL v2 or later
**Compatible with**:
- Sonaar MP3 Audio Player
- Slider Revolution
- Elementor
- Gutenberg

## License

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
