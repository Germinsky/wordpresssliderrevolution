<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        
        .dpss-stage-wrapper {
            position: relative;
            width: 100%;
            height: <?php echo esc_attr($atts['height']); ?>px;
            overflow: hidden;
            background: #000;
        }
        
        .dpss-stage-background {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url('<?php echo esc_url($song_data['featured_image']); ?>');
            background-size: cover;
            background-position: center;
            animation: dpss-ken-burns 20s ease-in-out infinite alternate;
        }
        
        @keyframes dpss-ken-burns {
            0% { transform: scale(1); }
            100% { transform: scale(1.15); }
        }
        
        .dpss-stage-overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
            z-index: 2;
        }
        
        .dpss-spotlight {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,215,0,0.3) 0%, transparent 70%);
            filter: blur(40px);
            z-index: 3;
            animation: dpss-pulse 3s ease-in-out infinite;
        }
        
        .dpss-spotlight-1 {
            top: 50px;
            left: 50px;
            animation-delay: 0s;
        }
        
        .dpss-spotlight-2 {
            top: 50px;
            right: 50px;
            animation-delay: 1s;
        }
        
        .dpss-spotlight-3 {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 2s;
        }
        
        @keyframes dpss-pulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.1); }
        }
        
        .dpss-stage-content {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 10;
            padding: 40px;
            box-sizing: border-box;
        }
        
        .dpss-album-art {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
            margin-bottom: 40px;
            animation: dpss-float 6s ease-in-out infinite;
        }
        
        .dpss-album-art img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        @keyframes dpss-float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .dpss-song-title {
            font-size: 80px;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
            text-shadow: 0 0 30px rgba(255,215,0,0.8), 0 0 60px rgba(255,215,0,0.6);
            margin: 0 0 20px 0;
            animation: dpss-slide-in 1s ease-out;
        }
        
        @keyframes dpss-slide-in {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dpss-song-artist {
            font-size: 32px;
            color: #f0f0f0;
            text-align: center;
            text-shadow: 0 2px 10px rgba(0,0,0,0.7);
            margin: 0 0 40px 0;
            animation: dpss-fade-in 1s ease-out 0.3s both;
        }
        
        @keyframes dpss-fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .dpss-play-button {
            display: inline-block;
            padding: 20px 50px;
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 10px 30px rgba(102,126,234,0.5);
            cursor: pointer;
            transition: all 0.3s;
            animation: dpss-bounce-in 1s ease-out 0.6s both;
        }
        
        .dpss-play-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(102,126,234,0.7);
        }
        
        @keyframes dpss-bounce-in {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .dpss-sonaar-player {
            margin-top: 40px;
            width: 100%;
            max-width: 600px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .dpss-song-title {
                font-size: 48px;
            }
            
            .dpss-song-artist {
                font-size: 24px;
            }
            
            .dpss-album-art {
                width: 200px;
                height: 200px;
            }
            
            .dpss-play-button {
                padding: 15px 35px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="dpss-stage-wrapper" data-song-id="<?php echo esc_attr($song_data['id']); ?>">
        <div class="dpss-stage-background"></div>
        <div class="dpss-stage-overlay"></div>
        
        <!-- Spotlight effects -->
        <div class="dpss-spotlight dpss-spotlight-1"></div>
        <div class="dpss-spotlight dpss-spotlight-2"></div>
        <div class="dpss-spotlight dpss-spotlight-3"></div>
        
        <div class="dpss-stage-content">
            <?php if ($song_data['featured_image']): ?>
            <div class="dpss-album-art">
                <img src="<?php echo esc_url($song_data['featured_image']); ?>" 
                     alt="<?php echo esc_attr($song_data['title']); ?>">
            </div>
            <?php endif; ?>
            
            <h1 class="dpss-song-title">
                <?php echo esc_html($song_data['title']); ?>
            </h1>
            
            <?php if ($song_data['artist']): ?>
            <p class="dpss-song-artist">
                by <?php echo esc_html($song_data['artist']); ?>
            </p>
            <?php endif; ?>
            
            <?php if ($atts['controls'] === 'true'): ?>
            <div style="margin-bottom: 20px;">
                <a href="<?php echo esc_url($song_data['permalink']); ?>" 
                   class="dpss-play-button"
                   id="dpss-main-play-btn"
                   onclick="dpssPlaySong(<?php echo esc_js($song_data['id']); ?>); return false;">
                    ▶ PLAY SONG OF THE DAY
                </a>
                <p style="font-size: 14px; color: #ccc; margin-top: 10px;">
                    Can't play? <a href="<?php echo esc_url($song_data['permalink']); ?>" style="color: #667eea;">Click here to view full song page</a>
                </p>
            </div>
            <?php endif; ?>
            
            <div class="dpss-sonaar-player" data-song-id="<?php echo esc_attr($song_data['id']); ?>" style="margin-top: 40px; width: 100%; max-width: 600px;">
                <?php 
                // Sonaar player - try different shortcode variations
                if (function_exists('sonaar_shortcode_single_player')) {
                    echo do_shortcode('[sonaar_audioplayer albums="' . $song_data['id'] . '" show_playlist="true" show_album_market="false" show_track_market="false"]'); 
                } else {
                    echo do_shortcode('[sonaar_audioplayer post_id="' . $song_data['id'] . '"]');
                }
                ?>
            </div>
        </div>
    </div>
    
    <script>
    // Enhanced play function with multiple Sonaar player selectors
    function dpssPlaySong(songId) {
        try {
            // List of possible Sonaar player button selectors (in order of preference)
            var selectors = [
                '.srp_player_grid .sr_it-play-icon',           // Sonaar grid player
                '.iron-audioplayer .play-pause-btn',           // Iron player
                '.sonaar-player .play-pause-bt',               // Sonaar player button
                '.sr-play-circle',                              // Sonaar play circle
                'button[aria-label*="Play"]',                   // Any play button with aria-label
                '.album-player .play',                          // Album player
                '.sonaar_single_player .playpause',            // Single player
                '#sonaar-player .play-btn',                     // ID-based selector
                '.dpss-sonaar-player button',                   // Any button in our player div
                '.dpss-sonaar-player .fa-play',                // FontAwesome play icon
                '.dpss-sonaar-player [class*="play"]'          // Any element with "play" in class
            ];
            
            // Try each selector
            for (var i = 0; i < selectors.length; i++) {
                var button = document.querySelector(selectors[i]);
                if (button) {
                    console.log('Found play button with selector:', selectors[i]);
                    button.click();
                    return false;
                }
            }
            
            // If no button found, try to trigger Sonaar API
            if (typeof IRON !== 'undefined' && IRON.sonaar) {
                if (IRON.sonaar.player && typeof IRON.sonaar.player.play === 'function') {
                    IRON.sonaar.player.play();
                    return false;
                }
            }
            
            console.warn('No Sonaar player button found. Available buttons:', document.querySelectorAll('button, .play-pause-btn, [class*="play"]'));
        } catch(e) {
            console.error('DPSS play error:', e);
        }
        return true;
    }
    
    // Debug helper - run this in browser console if play isn't working: dpssDebug()
    window.dpssDebug = function() {
        console.log('=== DPSS Debug Info ===');
        console.log('Song ID:', <?php echo json_encode($song_data['id']); ?>);
        console.log('IRON object exists:', typeof IRON !== 'undefined');
        if (typeof IRON !== 'undefined') {
            console.log('IRON.sonaar exists:', !!IRON.sonaar);
            if (IRON.sonaar) {
                console.log('IRON.sonaar.player:', IRON.sonaar.player);
            }
        }
        console.log('All buttons:', document.querySelectorAll('button'));
        console.log('All play-related elements:', document.querySelectorAll('[class*="play"], [class*="Play"]'));
        console.log('Sonaar player div:', document.querySelector('.dpss-sonaar-player'));
        console.log('===================');
    };
    
    // Add parallax effect on mouse move - only if slider exists
    if (document.querySelector('.dpss-stage-wrapper')) {
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const wrapper = document.querySelector('.dpss-stage-wrapper');
                if (!wrapper) return;
                
                const background = wrapper.querySelector('.dpss-stage-background');
                const content = wrapper.querySelector('.dpss-stage-content');
                
                if (!background || !content) return;
                
                wrapper.addEventListener('mousemove', function(e) {
                    const xPos = (e.clientX / window.innerWidth - 0.5) * 30;
                    const yPos = (e.clientY / window.innerHeight - 0.5) * 30;
                    
                    background.style.transform = `translate(${xPos}px, ${yPos}px) scale(1.15)`;
                    content.style.transform = `translate(${xPos * 0.3}px, ${yPos * 0.3}px)`;
                });
            } catch(e) {
                console.log('DPSS parallax error:', e);
            }
        });
    }
    </script>
</body>
</html>
