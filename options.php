<?php
if( ! class_exists( 'Gwsscs_Social_Custom_Share_Options' ) ) {
	class Gwsscs_Social_Custom_Share_Options{
        
        private $options;

        public function __construct() {
            add_action( 'admin_menu', array($this,'social_add_options_page') );
			add_action( 'admin_init', array($this,'social_settings_init') );
		}
        
        public function social_add_options_page(){
            add_submenu_page(
                'options-general.php',
                __('Social Custom Share','social-custom-share'),
                __('Social Custom Share','social-custom-share'),
                'administrator',
                'gwsscs-social-custom-share',
                array($this, 'social_add_options_page_html')
            );
        }

        public function social_add_options_page_html(){
            ?>
            <h1><?php _e('Social Custom Share','social-custom-share');?></h1>
            <h2 class="nav-tab-wrapper gwsscs-tabs">
                <a href="<?php echo esc_url(admin_url('options-general.php?page=gwsscs-social-custom-share'));?>" class="nav-tab <?php if(!isset($_REQUEST['tab'])){ esc_attr_e('gwsscs_active_tab');} ?>"><span class="dashicons dashicons-twitter"></span> <?php _e('Twitter Settings','social-custom-share');?></a>
                <a href="<?php echo esc_url(admin_url('options-general.php?page=gwsscs-social-custom-share&tab=facebook-settings'));?>" class="nav-tab <?php if(isset($_REQUEST['tab']) && $_REQUEST['tab']=='facebook-settings'){ esc_attr_e('gwsscs_active_tab');} ?>"> <span class="dashicons dashicons-facebook-alt"></span> <?php _e('Facebook Settings','social-custom-share');?></a>
            </h2>
            <?php
            if (isset($_REQUEST['tab']) && $_REQUEST['tab']=='facebook-settings') {
                $this->options = get_option( 'gwsscs_facebook_options' );
                ?>
                <div class="gwsscs-how-use gwsscs-box-container">
                    <h3><?php _e('How To Use','social-custom-share');?></h3>
                    <div class="gwsscs-inside">
						<p><?php _e('To add "Click to Share" functionality in your site, include the Social Custom Share`s shortcode in your page or post.','social-custom-share');?></p>
						<p><?php _e('Here`s how you to use the shortcode:','social-custom-share');?></p>
                        <span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value="[gwsscs_facebook_share text=&quot;Your sharing text here.&quot;]" class="large-text code"></span>
					</div>
                </div>
                <div class="gwsscs-settings gwsscs-box-container">
                    <h3><?php _e('Settings','social-custom-share');?></h3>
                    <form method="post" action="options.php"> 
                        <?php
                        settings_fields( 'gwsscs_facebook_settings' );
                        do_settings_sections( 'gwsscs-social-share-facebook' );
                        ?>
                        <?php submit_button( __( 'Save Settings', 'social-custom-share' ) ); ?>
                    </form>
                </div>
                <?php
            }
            else{
                $this->options = get_option( 'gwsscs_twitter_options' );
                ?>
                <div class="gwsscs-how-use gwsscs-box-container">
                    <h3><?php _e('How To Use','social-custom-share');?></h3>
                    <div class="gwsscs-inside">
                        <p><?php _e('To add "Click To Tweet" functionality in your site, include the Social Custom Share`s shortcode in your page or post.','social-custom-share');?></p>
                        <p><?php _e('Here`s how you to use the shortcode:','social-custom-share');?></p>
                        <span class="shortcode"><input type="text" onfocus="this.select();" readonly="readonly" value="[gwsscs_tweeter_share tweet=&quot;Your tweet text here.&quot;]" class="large-text code"></span>
                        <p><?php _e('Your tweet length is automatically shortened to leave room for the handle and link back to the post.','social-custom-share');?></p>
					</div>
                </div>
                <div class="gwsscs-settings gwsscs-box-container">
                    <h3><?php _e('Settings','social-custom-share');?></h3>
                    <form method="post" action="options.php"> 
                        <?php
                        settings_fields( 'gwsscs_twitter_settings' );
                        do_settings_sections( 'gwsscs-social-custom-share' );
                        ?>
                        <?php submit_button( __( 'Save Settings', 'social-custom-share' ) ); ?>
                    </form>
                </div>
                <?php
            }
        }

        public function social_settings_init(){
            register_setting(
                'gwsscs_twitter_settings',
                'gwsscs_twitter_options',
                array( $this, 'sanitize' )
            );

            add_settings_section(
                'setting_section_id', // ID
                __('Twitter Settings:', 'social-custom-share' ),
                array( ), // Callback
                'gwsscs-social-custom-share' // Page
            );  

            add_settings_field(
                'twitter_handle', // ID
                __('Your Twitter Handle', 'social-custom-share' ), // Title 
                array( $this, 'twitter_handle_callback' ), // Callback
                'gwsscs-social-custom-share', // Page
                'setting_section_id', // Section           
                [
                    'label_for' => 'twitter_handle',
                ]
            );      
    
            add_settings_field(
                'share_page_url', 
                __('Share Page URL?', 'social-custom-share' ), 
                array( $this, 'share_page_url_callback' ), 
                'gwsscs-social-custom-share', 
                'setting_section_id', // Section           
                [
                    'label_for' => 'share_page_url',
                ]
            );

            add_settings_field(
                'short_url', 
                __('Use Short URL?','social-custom-share' ), 
                array( $this, 'short_url_callback' ), 
                'gwsscs-social-custom-share', 
                'setting_section_id', // Section           
                [
                    'label_for' => 'short_url',
                ]
            ); 

            add_settings_field(
                'add_nofollow', 
                __('Add Nofollow in the Link?','social-custom-share' ), 
                array( $this, 'add_nofollow_callback' ), 
                'gwsscs-social-custom-share', 
                'setting_section_id', // Section           
                [
                    'label_for' => 'add_nofollow',
                ]
            ); 

            add_settings_field(
                'show_style', 
                __('Select Style','social-custom-share' ), 
                array( $this, 'show_style_callback' ), 
                'gwsscs-social-custom-share', 
                'setting_section_id' // Section
            );


            register_setting(
                'gwsscs_facebook_settings',
                'gwsscs_facebook_options',
                array( $this, 'sanitize' )
            );

            add_settings_section(
                'setting_facebook_section_id', // ID
                __( 'Facebook Settings:', 'social-custom-share' ),
                array( ), // Callback
                'gwsscs-social-share-facebook' // Page
            ); 

            add_settings_field(
                'short_url_facebook', 
                __( 'Use Short URL?', 'social-custom-share' ), 
                array( $this, 'short_url_facebook_callback' ),
                'gwsscs-social-share-facebook', 
                'setting_facebook_section_id', 
                [
                    'label_for' => 'short_url_facebook',
                ]
            ); 

            add_settings_field(
                'add_nofollow_facebook', 
                __( 'Add Nofollow in the Link?', 'social-custom-share' ),
                array( $this, 'add_nofollow_facebook_callback' ),
                'gwsscs-social-share-facebook', 
                'setting_facebook_section_id', 
                [
                    'label_for' => 'add_nofollow_facebook',
                ]
            );

            add_settings_field(
                'show_style_facebook', 
                __( 'Select Style','social-custom-share' ), 
                array( $this, 'show_style_facebook_callback' ), 
                'gwsscs-social-share-facebook', 
                'setting_facebook_section_id' // Section
            );

        }
        
        public function sanitize( $input ){
            $new_input = array();
            if( isset( $input['twitter_handle'] ) )
                $new_input['twitter_handle'] = sanitize_text_field( str_replace('@','',$input['twitter_handle']) );
            
            if( isset( $input['share_page_url'] ) )
                $new_input['share_page_url'] = boolval( $input['share_page_url'] );

            if( isset( $input['short_url'] ) )
                $new_input['short_url'] = boolval( $input['short_url'] );
            
            if( isset( $input['add_nofollow'] ) )
                $new_input['add_nofollow'] = boolval( $input['add_nofollow'] );

            if( isset( $input['show_style'] ) )
                $new_input['show_style'] = sanitize_text_field( $input['show_style'] );

            if( isset( $input['short_url_facebook'] ) )
                $new_input['short_url_facebook'] = boolval( $input['short_url_facebook'] );

            if( isset( $input['add_nofollow_facebook'] ) )
                $new_input['add_nofollow_facebook'] = boolval( $input['add_nofollow_facebook'] );

            if( isset( $input['show_style_facebook'] ) )
                $new_input['show_style_facebook'] = sanitize_text_field( $input['show_style_facebook'] );
    
            return $new_input;
        }

        public function twitter_handle_callback(){
            printf(
                '<input type="text" id="twitter_handle" name="gwsscs_twitter_options[twitter_handle]" value="%s" />
                <p>'.__('Enter your Twitter handle to add "via @yourhandle" to your tweets. Do not include the @ symbol.','social-custom-share').'</p>',
                isset( $this->options['twitter_handle'] ) ? esc_attr( $this->options['twitter_handle']) : ''
            );
        }

        public function share_page_url_callback(){
            $share_page_url = isset($this->options['share_page_url']) ? $this->options['share_page_url'] : 0;
            printf(
                '<input type="checkbox" id="share_page_url" name="gwsscs_twitter_options[share_page_url]" %s />
                <p>'.__('Checking the box above will force the plugin to show the URL of the current page.','social-custom-share').'</p>',
                checked( $share_page_url, 1, 0 )
            );
        }

        public function short_url_callback(){
            $short_url = isset($this->options['short_url']) ? $this->options['short_url'] : 0;
            printf(
                '<input type="checkbox" id="short_url" name="gwsscs_twitter_options[short_url]" %s />
                <p>'.__('Checking the box above will force the plugin to show the WordPress shortlink in place of the full URL.','social-custom-share').'</p>',
                checked( $short_url, 1, 0 )
            );

        }

        public function add_nofollow_callback(){
            $add_nofollow = isset($this->options['add_nofollow']) ? $this->options['add_nofollow'] : 0;
            printf(
                '<input type="checkbox" id="add_nofollow" name="gwsscs_twitter_options[add_nofollow]" %s />
                <p>'.__('Checking the box above will force the plugin to add nofollow attribute in the "Click to Tweet" Link.','social-custom-share').'</p>',
                checked( $add_nofollow, 1, 0 )
            );
        }

        public function show_style_callback(){
            ?>
            <div class="gwsscs-style-section">
                <?php
                $style_options = array('style_default' => 'Default', 'style_1' => 'Style 1', 'style_2' => 'Style 2', 'style_3' => 'Style 3', 'style_4' => 'Style 4', 'style_5' => 'Style 5', 'style_6' => 'Style 6', 'style_7' => 'Style 7', 'style_8' => 'Style 8', 'style_9' => 'Style 9');
                
                foreach($style_options as $key => $value){
                    printf(
                        '<div class="gwsscs-style-box">
                            <label for="'.esc_attr($key).'" class="gwsscs-tag-lines">
                                <input type="radio" id="'.esc_attr($key).'" class="radio_btn_style" name="gwsscs_twitter_options[show_style]" value="'.esc_attr($key).'" %s>
                                <span>'.esc_attr($value).'</span>
                            </label>
                            <img src="'.esc_url(GWSSCS_PLUGIN_URL).'/assets/images/'.esc_attr($key).'.png" alt="'.esc_attr($key).'">
                        </div>',
                        ( (isset($this->options['show_style']) && ( $this->options['show_style'] == $key)) ? 'checked' : '' )
                    );    
                }
                ?>
            </div>
            <?php
        }

        public function short_url_facebook_callback(){
            $short_url_facebook = isset($this->options['short_url_facebook']) ? $this->options['short_url_facebook'] : 0;
            printf(
                '<input type="checkbox" id="short_url_facebook" name="gwsscs_facebook_options[short_url_facebook]" %s />
                <p>'.__('Checking the box below will force the plugin to show the WordPress shortlink in place of the full URL.','social-custom-share').'</p>',
                checked( $short_url_facebook, 1, 0 )
            );
        }

        public function add_nofollow_facebook_callback(){
            $add_nofollow_facebook = isset($this->options['add_nofollow_facebook']) ? $this->options['add_nofollow_facebook'] : 0;
            printf(
                '<input type="checkbox" id="add_nofollow_facebook" name="gwsscs_facebook_options[add_nofollow_facebook]" %s />
                <p>'.__('Checking the box above will force the plugin to add nofollow attribute in the "Click to Share" Link.','social-custom-share').'</p>',
                checked( $add_nofollow_facebook, 1, 0 )
            );
        }

        public function show_style_facebook_callback(){
            ?>
            <div class="gwsscs-style-section">
                <?php
                $style_options = array('style_default' => 'Default', 'style_1' => 'Style 1', 'style_2' => 'Style 2', 'style_3' => 'Style 3', 'style_4' => 'Style 4', 'style_5' => 'Style 5', 'style_6' => 'Style 6', 'style_7' => 'Style 7', 'style_8' => 'Style 8', 'style_9' => 'Style 9');
                
                foreach($style_options as $key => $value){
                    printf(
                        '<div class="gwsscs-style-box">
                            <label for="'.esc_attr($key).'" class="gwsscs-tag-lines">
                                <input type="radio" id="'.esc_attr($key).'" class="radio_btn_style" name="gwsscs_facebook_options[show_style_facebook]" value="'.esc_attr($key).'" %s>
                                <span>'.esc_attr($value).'</span>
                            </label>
                            <img src="'.esc_url(GWSSCS_PLUGIN_URL).'/assets/images/fb_'.esc_attr($key).'.png" alt="'.esc_attr($key).'">
                        </div>',
                        ( (isset($this->options['show_style_facebook']) && ( $this->options['show_style_facebook'] == $key)) ? 'checked' : '' )
                    );    
                }
                ?>
            </div>
            <?php
        }
    }
    new Gwsscs_Social_Custom_Share_Options();
}