# Digital Prophets Stage Slider - Installation Guide

## Quick Start (5 Minutes)

### Step 1: Install Required Plugins

1. **Install Sonaar MP3 Audio Player**
   - Go to WordPress Admin → Plugins → Add New
   - Search for "Sonaar MP3 Audio Player"
   - Click "Install Now" and then "Activate"
   - (Optional) Purchase Sonaar Pro for advanced features

2. **Install Digital Prophets Stage Slider**
   - Download the plugin ZIP file
   - Go to WordPress Admin → Plugins → Add New → Upload Plugin
   - Choose `digital-prophets-stage-slider.zip`
   - Click "Install Now" and then "Activate"

3. **(Optional) Install Slider Revolution**
   - Purchase from https://www.sliderrevolution.com/
   - Install and activate
   - Enter your license key

### Step 2: Configure Sonaar

1. Go to **WordPress Admin → Sonaar → Settings**
2. Set up your audio player preferences
3. Create your first album/playlist:
   - Posts → Add New (look for Sonaar post types)
   - Upload album artwork
   - Add audio tracks
   - Publish

### Step 3: Configure Stage Slider

1. Go to **WordPress Admin → Stage Slider → Settings**
2. Configure appearance:
   - Choose default theme: `concert-hall` recommended
   - Set slider height: `800px` recommended
   - Enable parallax effects: ✓
   - Enable Ken Burns effect: ✓
   - Enable spotlights: ✓

3. Configure behavior:
   - Auto advance: `15` seconds
   - Auto-select category: Leave empty for manual selection
   - Fallback to newest: ✓

4. Click **"Save Settings"**

### Step 4: Create Your First Song of the Day

1. **Edit an Existing Sonaar Post** or create new:
   - Go to your Sonaar posts (Albums, Playlists, etc.)
   - Click Edit on the post you want to feature

2. **Find the Stage Slider Settings Box** (right sidebar):
   - Check ✓ "Set as Song of the Day"
   - Featured Date: (auto-fills with today)
   - Stage Theme: Choose "Concert Hall"
   - Click "Update"

3. **Preview Your Slider**:
   - Go to Stage Slider → Preview
   - You should see your song in the stage slider!

### Step 5: Add to Your Website

Choose one of these methods:

#### Method A: Shortcode in Page/Post
1. Edit any page or post
2. Add a Shortcode block (Gutenberg) or insert directly
3. Paste: `[dp_song_stage]`
4. Publish and view!

#### Method B: Elementor Widget
1. Edit page with Elementor
2. Add "Shortcode" widget
3. Paste: `[dp_song_stage theme="concert-hall" height="800"]`
4. Update and view!

#### Method C: Widget in Sidebar
1. Go to Appearance → Widgets
2. Add "Song of the Day" widget to sidebar
3. Configure title and options
4. Save

---

## Detailed Configuration

### Sonaar Post Types

The plugin automatically detects these Sonaar post types:
- `podcast` - Podcast episodes
- `album` - Music albums
- `sr_playlist` - Sonaar playlists
- `playlist` - Generic playlists
- `episode` - Episode posts

You can configure which to use in **Stage Slider → Settings → Integration**.

### Stage Themes Explained

#### Default Stage
Classic concert stage with warm lighting. Good for all music types.

#### Concert Hall
Elegant auditorium with sophisticated ambiance. Perfect for classical, jazz, acoustic performances.

#### Club Lights
Vibrant nightclub atmosphere with colorful lighting. Great for EDM, DJ sets, dance music.

#### Outdoor Festival
Open-air summer festival vibes. Ideal for indie, rock, folk music.

#### Minimal
Clean, modern presentation without overlay effects. Best for podcasts, spoken word.

#### Custom
Upload your own PNG overlay image:
1. Edit song post
2. Stage Theme: Select "Custom"
3. Click "Upload Custom Overlay"
4. Choose transparent PNG (1920x800px recommended)

### Shortcode Reference

#### Basic Usage
```php
[dp_song_stage]
```

#### With All Options
```php
[dp_song_stage 
    height="800" 
    theme="concert-hall" 
    autoplay="false" 
    controls="true"]
```

#### Multiple Shortcodes on Same Page
```php
<!-- Full width hero slider -->
[dp_song_stage height="1000" theme="outdoor-festival"]

<!-- Sidebar player -->
[dp_song_of_day layout="minimal" show_player="true"]

<!-- Latest songs grid -->
[dp_latest_songs count="9" columns="3" category="featured"]
```

### Category Organization

Create song collections for better organization:

1. **Go to Stage Slider → Song Collections → Add New**
2. Create categories like:
   - `featured` - Your best tracks
   - `new-releases` - Latest drops
   - `throwback` - Classic hits
   - `seasonal` - Holiday/seasonal music

3. **Assign songs to categories**:
   - Edit Sonaar post
   - Find "Song Collections" box
   - Check relevant categories

4. **Use in shortcodes**:
   ```php
   [dp_latest_songs category="featured" count="6"]
   ```

### Auto-Selection Rules

The plugin uses this priority for "Song of the Day":

1. **Manual Selection**: Song with "Set as Song of the Day" checked
2. **Category Selection**: If no manual, pulls from auto-select category (newest first)
3. **Newest Fallback**: If no category, uses newest Sonaar post (if enabled)

Configure in **Stage Slider → Settings → Behavior**.

---

## Advanced Setup

### Custom CSS

Add to **Appearance → Customize → Additional CSS**:

```css
/* Larger title on desktop */
@media (min-width: 1200px) {
    .dpss-song-title {
        font-size: 120px !important;
    }
}

/* Custom gradient colors */
.dpss-song-of-day {
    background: linear-gradient(135deg, #your-color1 0%, #your-color2 100%);
}

/* Custom spotlight color */
.dpss-spotlight {
    background: radial-gradient(circle, rgba(255, 0, 128, 0.3) 0%, transparent 70%);
}

/* Hide play button on mobile */
@media (max-width: 768px) {
    .dpss-play-button {
        display: none;
    }
}
```

### PHP Customization

Add to your theme's `functions.php`:

```php
// Change default slider height
add_filter('dpss_default_slider_height', function() {
    return 1000; // pixels
});

// Customize which post types to use
add_filter('dpss_sonaar_post_types', function($types) {
    $types[] = 'your_custom_post_type';
    return $types;
});

// Modify song data before display
add_filter('dpss_song_data', function($data, $post_id) {
    // Add custom meta
    $data['custom_field'] = get_post_meta($post_id, 'your_meta_key', true);
    return $data;
}, 10, 2);
```

### Revolution Slider Integration

If using Slider Revolution:

1. **Auto-Create Slider**:
   - Go to Stage Slider → Settings
   - Click "Create RevSlider"
   - Slider alias: `dp-song-stage` (default)

2. **Manual Setup**:
   - Create new slider in RevSlider
   - Add slide
   - Use dynamic content from Sonaar posts
   - Save with alias `dp-song-stage`

3. **Use in Shortcode**:
   ```php
   [rev_slider alias="dp-song-stage"]
   ```

### Scheduled Posts

Schedule songs to be featured on specific dates:

1. **Create Multiple Posts**
2. **For Each Post**:
   - Check "Set as Song of the Day"
   - Featured Date: Set future date (e.g., 2024-01-20)
3. **Plugin Auto-Switches** when date arrives

### AJAX Auto-Refresh

Add auto-refresh to update slider without page reload:

```javascript
// Add to your theme's custom.js
setInterval(function() {
    DPSS.fetchLatestSong(function(songData) {
        DPSS.updateSliderContent(songData);
    });
}, 300000); // Refresh every 5 minutes
```

---

## Troubleshooting

### Slider Shows "No song available"

**Causes:**
- No song marked as "Song of the Day"
- No Sonaar posts published
- Wrong post type selected in settings

**Solutions:**
1. Edit a Sonaar post
2. Check "Set as Song of the Day"
3. Publish post
4. Refresh page with slider

### Audio Player Not Showing

**Causes:**
- Sonaar plugin not activated
- No audio files uploaded
- Conflict with theme/plugins

**Solutions:**
1. Verify Sonaar is active: Plugins → Installed Plugins
2. Edit song, check "Audio Tracks" section has files
3. Test Sonaar shortcode alone: `[sonaar_audioplayer]`
4. Deactivate other plugins one-by-one to find conflict

### Styles Look Wrong

**Causes:**
- Theme CSS conflicts
- Cache not cleared
- Missing CSS file

**Solutions:**
1. Clear all caches (browser, plugin, server)
2. Check browser console for CSS errors (F12)
3. Try different stage theme
4. Deactivate theme customizer temporarily

### Parallax/Effects Not Working

**Causes:**
- JavaScript disabled
- jQuery conflict
- Effects disabled in settings

**Solutions:**
1. Check browser console for JS errors (F12)
2. Ensure jQuery is loaded (view source, search "jquery")
3. Verify effects enabled: Stage Slider → Settings → Appearance
4. Try disabling other JS-heavy plugins temporarily

### Images Not Loading

**Causes:**
- No featured image set
- Image URLs broken
- SSL mixed content

**Solutions:**
1. Edit song post, set Featured Image
2. Re-upload image if showing broken
3. If HTTP/HTTPS mixed content, regenerate thumbnails
4. Check Media Library for corrupted files

---

## Performance Optimization

### Image Optimization
- Use 1920x1080px images for backgrounds
- Compress with TinyPNG or similar (aim for <500KB)
- Use WebP format for modern browsers
- Enable lazy loading in WordPress settings

### Caching
- Compatible with WP Rocket, W3 Total Cache, etc.
- Exclude AJAX endpoints from cache if using auto-refresh
- Clear cache after plugin updates

### Speed Tips
1. Disable effects you don't need (parallax, Ken Burns)
2. Reduce particle count via custom CSS
3. Use CDN for images
4. Minify CSS/JS via plugin like Autoptimize

---

## Next Steps

✅ **Plugin installed and configured**
✅ **First song of the day created**
✅ **Slider displayed on site**

Now:
1. Create more song posts in Sonaar
2. Organize with Song Collections
3. Experiment with different stage themes
4. Add widgets to sidebar
5. Use shortcodes in different pages
6. Customize CSS to match your brand

**Need help?** Visit https://digitalprophets.blog/support

Enjoy your new Stage Slider! 🎵🎸
