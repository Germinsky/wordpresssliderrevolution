# Digital Prophets Stage Slider - Quick Reference

## 🚀 Quick Start (2 Minutes)

1. **Activate Plugin**
2. **Edit Sonaar Post** → Check "Set as Song of the Day"
3. **Add to Page**: `[dp_song_stage]`
4. **Done!** 🎉

---

## 📝 Shortcode Cheat Sheet

### Main Stage Slider
```php
[dp_song_stage]
[dp_song_stage height="1000" theme="concert-hall"]
[dp_song_stage theme="club-lights" autoplay="true"]
```

### Song Card
```php
[dp_song_of_day]
[dp_song_of_day layout="minimal" show_player="false"]
```

### Songs Grid
```php
[dp_latest_songs count="6" columns="3"]
[dp_latest_songs category="featured" count="9"]
```

### Enhanced Player
```php
[dp_sonaar_player]
[dp_sonaar_player song_id="123" autoplay="true"]
```

---

## 🎨 Available Themes

- `default` - Classic concert stage
- `concert-hall` - Elegant auditorium
- `club-lights` - Vibrant nightclub
- `outdoor-festival` - Summer festival
- `minimal` - Clean modern
- `custom` - Upload your own

---

## ⚙️ Settings Location

**Main Settings**: WordPress Admin → Stage Slider → Settings

**Song Settings**: Edit any Sonaar post → "Stage Slider Settings" box

**Preview**: WordPress Admin → Stage Slider → Preview

---

## 📍 File Locations (for FTP upload)

```
/wp-content/plugins/digital-prophets-stage-slider/
```

To upload:
1. Compress folder to ZIP
2. WordPress Admin → Plugins → Add New → Upload
3. OR upload via FTP to plugins directory

---

## 🔧 Common Tasks

### Change Default Theme
```
Stage Slider → Settings → Appearance → Default Stage Theme
```

### Set Song of the Day
```
Edit Sonaar Post → Stage Slider Settings → ✓ Set as Song of the Day
```

### Create Category
```
Stage Slider → Song Collections → Add New
```

### Add to Sidebar
```
Appearance → Widgets → Song of the Day widget
```

### Add to Elementor
```
Add Shortcode Widget → Paste: [dp_song_stage]
```

---

## 🐛 Troubleshooting Quick Fixes

**No song showing?**
→ Check a Sonaar post has "Set as Song of the Day" checked

**Player not working?**
→ Verify Sonaar plugin is active

**Styling broken?**
→ Clear cache (browser + WordPress)

**JavaScript errors?**
→ Check jQuery is loaded

**Images not loading?**
→ Check Featured Image is set on post

---

## 💻 Developer Hooks

### Filters
```php
// Change default height
add_filter('dpss_default_slider_height', function() {
    return 1200;
});

// Modify song data
add_filter('dpss_song_data', function($data, $post_id) {
    $data['custom_field'] = 'value';
    return $data;
}, 10, 2);

// Add post types
add_filter('dpss_sonaar_post_types', function($types) {
    $types[] = 'my_custom_type';
    return $types;
});
```

### Actions
```php
// Before slider renders
add_action('dpss_before_slider', function($song_data) {
    // Your code
});

// After slider renders
add_action('dpss_after_slider', function($song_data) {
    // Your code
});
```

---

## 📱 Responsive Breakpoints

- **Desktop**: 1200px+
- **Laptop**: 1024px - 1199px
- **Tablet**: 768px - 1023px
- **Mobile**: 320px - 767px

All effects automatically adjust for screen size.

---

## ⚡ Performance Tips

1. **Compress images** to <500KB
2. **Use WebP format** when possible
3. **Enable caching** plugin
4. **Use CDN** for images
5. **Disable unused effects** in settings

---

## 🎯 Best Practices

### Images
- Size: 1920x1080px minimum
- Format: JPG or WebP
- Quality: 80-85%
- File size: <500KB

### Content
- Update Song of the Day daily
- Use high-quality artwork
- Write compelling descriptions
- Organize with categories

### SEO
- Add alt text to images
- Use descriptive titles
- Include artist/album in meta
- Link to full album pages

---

## 📊 Feature Matrix

| Feature | Free | Premium |
|---------|------|---------|
| Song of the Day | ✅ | ✅ |
| 5 Stage Themes | ✅ | ✅ |
| Parallax Effects | ✅ | ✅ |
| 4 Shortcodes | ✅ | ✅ |
| 2 Widgets | ✅ | ✅ |
| Admin Settings | ✅ | ✅ |
| Sonaar Integration | ✅ | ✅ |
| Custom Overlays | ✅ | ✅ |
| Video Backgrounds | ❌ | 🔜 |
| Playlist Mode | ❌ | 🔜 |
| Analytics | ❌ | 🔜 |
| A/B Testing | ❌ | 🔜 |

---

## 📞 Quick Links

- **Documentation**: README.md
- **Installation Guide**: INSTALLATION.md
- **Technical Docs**: STRUCTURE.md
- **Support**: support@digitalprophets.blog
- **Website**: https://digitalprophets.blog

---

## ✅ Pre-Launch Checklist

- [ ] Plugin uploaded to `/wp-content/plugins/`
- [ ] Sonaar plugin installed and active
- [ ] At least one Sonaar post created
- [ ] One song marked as "Song of the Day"
- [ ] Settings configured (Stage Slider → Settings)
- [ ] Test shortcode on page
- [ ] Check mobile responsive
- [ ] Clear all caches
- [ ] Test audio playback
- [ ] Verify images load

---

**Ready to Rock! 🎸**

Your Digital Prophets Stage Slider is ready to showcase your music in style!
