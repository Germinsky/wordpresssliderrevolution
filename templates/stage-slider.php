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
            <a href="<?php echo esc_url($song_data['permalink']); ?>" 
               class="dpss-play-button"
               onclick="return dpssPlaySong(<?php echo esc_js($song_data['id']); ?>);">
                ▶ PLAY SONG OF THE DAY
            </a>
            <?php endif; ?>
            
            <div class="dpss-sonaar-player" data-song-id="<?php echo esc_attr($song_data['id']); ?>">
                <?php 
                // Always show Sonaar player for integration
                echo do_shortcode('[sonaar_audioplayer albums="' . $song_data['id'] . '" 
                    hide_artwork="false" 
                    show_playlist="false" 
                    show_album_market="false"
                    player_layout="skin_button"
                    autoplay="' . $atts['autoplay'] . '"]'); 
                ?>
            </div>
        </div>
    </div>
    
    <script>
    function dpssPlaySong(songId) {
        // Multiple fallback methods to play Sonaar
        
        // Method 1: Try IRON.sonaar API
        if (typeof IRON !== 'undefined' && IRON.sonaar && IRON.sonaar.player) {
            try {
                IRON.sonaar.player.play();
                return false;
            } catch(e) {
                console.log('IRON.sonaar.player.play() failed:', e);
            }
        }
        
        // Method 2: Try clicking the Sonaar play button
        var playButton = document.querySelector('.sonaar-play-pause, .sr-play-button, .iron-audioplayer .play-btn');
        if (playButton) {
            playButton.click();
            return false;
        }
        
        // Method 3: Try finding player by album ID
        var playerDiv = document.querySelector('[data-album-id="' + songId + '"] .play-btn');
        if (playerDiv) {
            playerDiv.click();
            return false;
        }
        
        // Method 4: Look for any Sonaar player and click it
        var anyPlayer = document.querySelector('.sonaar-player .play-btn, .iron-audioplayer button');
        if (anyPlayer) {
            anyPlayer.click();
            return false;
        }
        
        // If all else fails, follow the link
        return true;
    }
    
    // Add parallax effect on mouse move
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.querySelector('.dpss-stage-wrapper');
        const background = wrapper.querySelector('.dpss-stage-background');
        const content = wrapper.querySelector('.dpss-stage-content');
        
        wrapper.addEventListener('mousemove', function(e) {
            const xPos = (e.clientX / window.innerWidth - 0.5) * 30;
            const yPos = (e.clientY / window.innerHeight - 0.5) * 30;
            
            background.style.transform = `translate(${xPos}px, ${yPos}px) scale(1.15)`;
            content.style.transform = `translate(${xPos * 0.3}px, ${yPos * 0.3}px)`;
        });
    });
    </script>
</body>
</html>
