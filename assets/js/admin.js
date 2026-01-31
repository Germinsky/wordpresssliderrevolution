/**
 * Digital Prophets Stage Slider - Admin JavaScript
 */

(function($) {
    'use strict';
    
    const DPSSAdmin = {
        
        init: function() {
            this.initMetaBox();
            this.initPreview();
            this.initThemeSelector();
            this.initSliderCreator();
        },
        
        /**
         * Initialize meta box functionality
         */
        initMetaBox: function() {
            // Handle "Set as Song of the Day" checkbox
            $('#dpss_is_song_of_day').on('change', function() {
                const $dateField = $('#dpss_featured_date_field');
                
                if ($(this).is(':checked')) {
                    $dateField.slideDown();
                    // Set today's date if empty
                    if (!$('#dpss_featured_date').val()) {
                        const today = new Date().toISOString().split('T')[0];
                        $('#dpss_featured_date').val(today);
                    }
                } else {
                    $dateField.slideUp();
                }
            }).trigger('change');
            
            // Handle stage theme selection
            $('#dpss_stage_theme').on('change', function() {
                const $customField = $('#dpss_custom_overlay_field');
                
                if ($(this).val() === 'custom') {
                    $customField.slideDown();
                } else {
                    $customField.slideUp();
                }
            }).trigger('change');
            
            // Preview theme
            $('.dpss-theme-option').on('click', function() {
                const theme = $(this).data('theme');
                $('#dpss_stage_theme').val(theme).trigger('change');
                
                $('.dpss-theme-option').removeClass('selected');
                $(this).addClass('selected');
            });
        },
        
        /**
         * Initialize live preview
         */
        initPreview: function() {
            $('#dpss-preview-button').on('click', function(e) {
                e.preventDefault();
                DPSSAdmin.showPreviewModal();
            });
        },
        
        /**
         * Show preview modal
         */
        showPreviewModal: function() {
            const songId = $('#post_ID').val();
            
            if (!songId) {
                alert('Please save the post first.');
                return;
            }
            
            // Create modal
            const $modal = $('<div class="dpss-preview-modal"></div>');
            const $overlay = $('<div class="dpss-preview-overlay"></div>');
            const $content = $('<div class="dpss-preview-content"></div>');
            const $close = $('<button class="dpss-preview-close">&times;</button>');
            const $iframe = $('<iframe></iframe>');
            
            // Build modal
            $content.append($close);
            $content.append('<h2>Stage Slider Preview</h2>');
            $content.append($iframe);
            $modal.append($overlay);
            $modal.append($content);
            
            // Add to page
            $('body').append($modal);
            
            // Load preview
            const previewUrl = dpssAdmin.previewUrl + '?song_id=' + songId;
            $iframe.attr('src', previewUrl);
            
            // Close handlers
            $close.on('click', function() {
                $modal.fadeOut(300, function() {
                    $modal.remove();
                });
            });
            
            $overlay.on('click', function() {
                $close.trigger('click');
            });
            
            // Show modal
            $modal.fadeIn(300);
        },
        
        /**
         * Initialize theme selector with previews
         */
        initThemeSelector: function() {
            const themes = {
                'default': 'Default Stage',
                'concert-hall': 'Concert Hall',
                'club-lights': 'Club Lights',
                'outdoor-festival': 'Outdoor Festival',
                'minimal': 'Minimal'
            };
            
            // Add visual theme previews if not already present
            if ($('.dpss-theme-preview').length === 0) {
                const $preview = $('<div class="dpss-theme-preview"></div>');
                
                $.each(themes, function(key, label) {
                    const $option = $('<div class="dpss-theme-option" data-theme="' + key + '"></div>');
                    $option.append('<img src="' + dpssAdmin.pluginUrl + 'assets/images/theme-' + key + '.jpg" alt="' + label + '">');
                    $option.append('<div class="theme-name">' + label + '</div>');
                    
                    if ($('#dpss_stage_theme').val() === key) {
                        $option.addClass('selected');
                    }
                    
                    $preview.append($option);
                });
                
                $('#dpss_stage_theme').after($preview);
            }
        },
        
        /**
         * Initialize slider creator
         */
        initSliderCreator: function() {
            $('#dpss-create-slider').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                $button.prop('disabled', true).text('Creating...');
                
                $.ajax({
                    url: dpssAdmin.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'dpss_create_slider',
                        nonce: dpssAdmin.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            DPSSAdmin.showNotice('Slider created successfully!', 'success');
                        } else {
                            DPSSAdmin.showNotice('Failed to create slider: ' + response.data, 'error');
                        }
                    },
                    error: function() {
                        DPSSAdmin.showNotice('An error occurred. Please try again.', 'error');
                    },
                    complete: function() {
                        $button.prop('disabled', false).text('Create Slider');
                    }
                });
            });
        },
        
        /**
         * Show admin notice
         */
        showNotice: function(message, type) {
            const $notice = $('<div class="dpss-notice ' + type + '"></div>');
            $notice.text(message);
            
            $('.dpss-admin-header').after($notice);
            
            setTimeout(function() {
                $notice.fadeOut(300, function() {
                    $notice.remove();
                });
            }, 5000);
        },
        
        /**
         * Validate settings form
         */
        validateSettings: function() {
            let valid = true;
            
            // Validate slider height
            const height = $('#dpss_slider_height').val();
            if (height < 300 || height > 2000) {
                DPSSAdmin.showNotice('Slider height must be between 300 and 2000 pixels.', 'error');
                valid = false;
            }
            
            return valid;
        }
    };
    
    // Initialize on document ready
    $(document).ready(function() {
        DPSSAdmin.init();
    });
    
    // Expose to global scope
    window.DPSSAdmin = DPSSAdmin;
    
})(jQuery);

/**
 * Media uploader for custom overlays
 */
jQuery(document).ready(function($) {
    
    $('#dpss_upload_overlay_button').on('click', function(e) {
        e.preventDefault();
        
        const mediaUploader = wp.media({
            title: 'Select Stage Overlay Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#dpss_custom_overlay').val(attachment.url);
            $('#dpss_overlay_preview').html('<img src="' + attachment.url + '" style="max-width:200px;">');
        });
        
        mediaUploader.open();
    });
    
    $('#dpss_remove_overlay_button').on('click', function(e) {
        e.preventDefault();
        $('#dpss_custom_overlay').val('');
        $('#dpss_overlay_preview').empty();
    });
    
});
