/**
 * Digital Prophets Stage Slider - Frontend JavaScript
 */

(function($) {
    'use strict';
    
    const DPSS = {
        
        init: function() {
            this.initStageSlider();
            this.initParallax();
            this.initPlayerIntegration();
            this.initAutoAdvance();
        },
        
        /**
         * Initialize stage slider effects
         */
        initStageSlider: function() {
            const $sliders = $('.dpss-stage-wrapper');
            
            $sliders.each(function() {
                const $slider = $(this);
                const songId = $slider.data('song-id');
                
                // Add particle effects
                DPSS.addParticleEffects($slider);
                
                // Preload images
                DPSS.preloadImages($slider);
            });
        },
        
        /**
         * Initialize parallax effects
         */
        initParallax: function() {
            const $sliders = $('.dpss-stage-wrapper');
            
            $sliders.on('mousemove', function(e) {
                const $slider = $(this);
                const $background = $slider.find('.dpss-stage-background');
                const $content = $slider.find('.dpss-stage-content');
                
                const xPos = (e.pageX / $(window).width() - 0.5) * 30;
                const yPos = (e.pageY / $(window).height() - 0.5) * 30;
                
                $background.css({
                    transform: `translate(${xPos}px, ${yPos}px) scale(1.15)`
                });
                
                $content.css({
                    transform: `translate(${xPos * 0.3}px, ${yPos * 0.3}px)`
                });
            });
        },
        
        /**
         * Add particle effects
         */
        addParticleEffects: function($slider) {
            const $particles = $('<div class="dpss-particles"></div>');
            
            for (let i = 0; i < 20; i++) {
                const $particle = $('<div class="dpss-particle"></div>');
                $particle.css({
                    left: Math.random() * 100 + '%',
                    animationDelay: Math.random() * 8 + 's',
                    animationDuration: (8 + Math.random() * 4) + 's'
                });
                $particles.append($particle);
            }
            
            $slider.append($particles);
        },
        
        /**
         * Preload images
         */
        preloadImages: function($slider) {
            const $images = $slider.find('img');
            
            $images.each(function() {
                const img = new Image();
                img.src = $(this).attr('src');
            });
        },
        
        /**
         * Initialize Sonaar player integration
         */
        initPlayerIntegration: function() {
            // Wait for Sonaar player to be ready
            $(document).on('sonaar/player/ready', function() {
                DPSS.syncPlayerWithSlider();
            });
            
            // Handle play button clicks
            $('.dpss-play-button').on('click', function(e) {
                e.preventDefault();
                const songId = $(this).closest('.dpss-stage-wrapper').data('song-id');
                DPSS.playSong(songId);
            });
        },
        
        /**
         * Play song via Sonaar player
         */
        playSong: function(songId) {
            if (typeof IRON !== 'undefined' && IRON.sonaar) {
                // Find the album/track
                const player = IRON.sonaar.player;
                
                // Try to play
                if (player && typeof player.play === 'function') {
                    player.play();
                } else {
                    // Fallback to clicking the play button
                    $('.sonaar-play-button').first().trigger('click');
                }
            }
        },
        
        /**
         * Sync player state with slider
         */
        syncPlayerWithSlider: function() {
            if (typeof IRON !== 'undefined' && IRON.sonaar) {
                const player = IRON.sonaar.player;
                
                // Update slider when song plays
                $(player).on('play', function() {
                    $('.dpss-play-button').addClass('playing').text('⏸ NOW PLAYING');
                });
                
                // Update slider when song pauses
                $(player).on('pause', function() {
                    $('.dpss-play-button').removeClass('playing').text('▶ PLAY SONG OF THE DAY');
                });
            }
        },
        
        /**
         * Initialize auto-advance functionality
         */
        initAutoAdvance: function() {
            const $sliders = $('.dpss-stage-wrapper[data-auto-advance]');
            
            $sliders.each(function() {
                const $slider = $(this);
                const seconds = parseInt($slider.data('auto-advance'));
                
                if (seconds > 0) {
                    setTimeout(function() {
                        DPSS.advanceSlider($slider);
                    }, seconds * 1000);
                }
            });
        },
        
        /**
         * Advance to next slider
         */
        advanceSlider: function($slider) {
            // This would advance to the next song
            // Implementation depends on whether using Revolution Slider or custom solution
            if (typeof revapi !== 'undefined') {
                revapi.revnext();
            }
        },
        
        /**
         * AJAX: Fetch latest song
         */
        fetchLatestSong: function(callback) {
            $.ajax({
                url: dpssData.ajaxurl,
                type: 'POST',
                data: {
                    action: 'dpss_get_latest_song',
                    nonce: dpssData.nonce
                },
                success: function(response) {
                    if (response.success && callback) {
                        callback(response.data);
                    }
                }
            });
        },
        
        /**
         * Update slider content dynamically
         */
        updateSliderContent: function(songData) {
            const $slider = $('.dpss-stage-wrapper');
            
            // Update background
            $slider.find('.dpss-stage-background').css({
                'background-image': 'url(' + songData.featured_image + ')'
            });
            
            // Update title
            $slider.find('.dpss-song-title').text(songData.title);
            
            // Update artist
            $slider.find('.dpss-song-artist').text(songData.artist ? 'by ' + songData.artist : '');
            
            // Update album art
            $slider.find('.dpss-album-art img').attr('src', songData.featured_image);
            
            // Trigger animations
            $slider.addClass('dpss-refreshing');
            setTimeout(function() {
                $slider.removeClass('dpss-refreshing');
            }, 1000);
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        DPSS.init();
    });
    
    // Expose to global scope
    window.DPSS = DPSS;
    window.dpssPlaySong = DPSS.playSong;
    
})(jQuery);
