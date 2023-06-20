<?php
if( ! class_exists( 'Gwsscs_Social_Custom_Share_Shortcode' ) ) {
	class Gwsscs_Social_Custom_Share_Shortcode{

        public function __construct() {
			add_shortcode( 'gwsscs_tweeter_share', array($this,'social_tweeter_shortcode') );
			add_shortcode( 'gwsscs_facebook_share', array($this,'social_facebook_shortcode') );
		}

        public function social_tweeter_shortcode($atts){
            $twitter_options = get_option( 'gwsscs_twitter_options' );
            $handle_name = '';
            $share_page_url = false;
            $short_url = false;
            $add_nofollow = false;
            $show_style = 'style_default';
            $style_image = '';
            
            if(isset($twitter_options['short_url'])){
                $short_url = $twitter_options['short_url'];
            }

            if(isset($twitter_options['share_page_url'])){
                $share_page_url = $twitter_options['share_page_url'];
            }

            if(isset($twitter_options['show_style'])){
                $show_style = $twitter_options['show_style'];
            }

            if($show_style != 'style_default'){
                $style_image = GWSSCS_PLUGIN_URL.'/assets/images/'.esc_attr($show_style).'_design_bg.png';
            }

            if(isset($twitter_options['add_nofollow'])){
                $add_nofollow = $twitter_options['add_nofollow'];
            }
            
            if(isset($twitter_options['twitter_handle'])){
                $handle_name = sanitize_text_field($twitter_options['twitter_handle']);
            }
            
            $atts = shortcode_atts( array(
                'tweet'    => !empty( get_the_ID() ) ? get_the_title( get_the_ID() ) : '',
            ), $atts, 'gwsscs_tweet_share' );

            if ( function_exists( 'mb_internal_encoding' ) ) {
                $handle_length = ( 6 + mb_strlen( $handle_name ) );
            } else {
                $handle_length = ( 6 + strlen( $handle_name ) );
            }
        
            if ( $handle_name != '' ) {
                $via = $handle_name;
                $related = $handle_name;
            } else {
                $via = null;
                $related = '';
            }
        
            $text = $atts['tweet'];
        
            if ( $share_page_url != false ) {
                $short = $this->shorten_share_tweet_text( $text, ( 253 - ( $handle_length ) ) );
                if( $short_url != false ){
                    $page_url  = wp_get_shortlink();
                }
                else{
                    $page_url = get_permalink();
                }
            } else {
                $short = $this->shorten_share_tweet_text( $text, ( 280 - ( $handle_length ) ) );
                $page_url = null;
            }

            if ( $add_nofollow != false ) {
                $rel = ' nofollow';
            } else {
                $rel = '';
            }
        
            $twitter_url  = add_query_arg(  array(
                'url'     => rawurlencode( $page_url ),
                'text'    => rawurlencode( html_entity_decode( $short ) ),
                'via'     => $via,
                'related' => $related,
            ), 'https://twitter.com/intent/tweet' );

            return '<div class="gwsscs-social-custom-box back-img gwsscs-'.esc_attr($show_style).'_design" style="background-image: url('.esc_url($style_image).');">
                    <p class="gwsscs-sharing-text">'.esc_html($short).'</p>
                    <p><a href="'.esc_url($twitter_url).'" target="_blank" rel="noopener noreferrer'.esc_attr($rel).'" class="gwsscs-social-custom-btn">Click To Tweet<span class="gwsscs-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="498" height="402.001" viewBox="0 0 498 402.001">
                                <defs>
                                <filter id="logo-twitter" x="0" y="0" width="498" height="402.001" filterUnits="userSpaceOnUse">
                                    <feOffset dy="3" input="SourceAlpha"/>
                                    <feGaussianBlur stdDeviation="3" result="blur"/>
                                    <feFlood flood-opacity="0.161"/>
                                    <feComposite operator="in" in2="blur"/>
                                    <feComposite in="SourceGraphic"/>
                                </filter>
                                </defs>
                                <g transform="matrix(1, 0, 0, 1, 0, 0)" filter="url(#logo-twitter)">
                                <path id="logo-twitter-2" data-name="logo-twitter" d="M496,109.5a201.8,201.8,0,0,1-56.55,15.3,97.51,97.51,0,0,0,43.33-53.6,197.74,197.74,0,0,1-62.56,23.5A99.14,99.14,0,0,0,348.31,64c-54.42,0-98.46,43.4-98.46,96.9a93.209,93.209,0,0,0,2.54,22.1,280.7,280.7,0,0,1-203-101.3A95.69,95.69,0,0,0,36,130.4c0,33.6,17.53,63.3,44,80.7A97.5,97.5,0,0,1,35.22,199v1.2c0,47,34,86.1,79,95a100.761,100.761,0,0,1-25.94,3.4,94.38,94.38,0,0,1-18.51-1.8c12.51,38.5,48.92,66.5,92.05,67.3A199.59,199.59,0,0,1,39.5,405.6,203,203,0,0,1,16,404.2,278.68,278.68,0,0,0,166.74,448c181.36,0,280.44-147.7,280.44-275.8,0-4.2-.11-8.4-.31-12.5A198.48,198.48,0,0,0,496,109.5Z" transform="translate(-7 -58)" fill="#55acee"/>
                                </g>
                            </svg>
                        </span>
                    </a></p>
                   
            </div>';
        }

        public function social_facebook_shortcode($atts){
            $facebook_options = get_option( 'gwsscs_facebook_options' );
            $short_url = false;
            $add_nofollow_facebook = false;
            $show_style = 'style_default';
            $style_image = '';

            if(isset($facebook_options['short_url_facebook'])){
                $short_url = sanitize_text_field($facebook_options['short_url_facebook']);
            }

            $atts = shortcode_atts( array(
                'text' => !empty( get_the_ID() ) ? get_the_title( get_the_ID() ) : ''
            ), $atts, 'gwsscs_tweet_share' );

            if(isset($facebook_options['show_style_facebook'])){
                $show_style = $facebook_options['show_style_facebook'];
            }

            if(isset($facebook_options['add_nofollow_facebook'])){
                $add_nofollow_facebook = $facebook_options['add_nofollow_facebook'];
            }

            if($show_style != 'style_default'){
                $style_image = GWSSCS_PLUGIN_URL.'/assets/images/'.esc_attr($show_style).'_design_bg.png';
            }

            if ( $short_url != false ) {
                $page_url  = wp_get_shortlink();
            } else {
                $page_url = get_permalink();
            }

            $text = $atts['text'];

            $short = $this->shorten_share_tweet_text( $text, 500 ); 

            if ( $add_nofollow_facebook != false ) {
                $rel = ' nofollow';
            } else {
                $rel = '';
            }
            $page_url = 'https://geekcodelab.com/';


            $facebook_share_url  = add_query_arg(  array(
                'u'     => rawurlencode( $page_url ),
                'quote'    => rawurlencode( html_entity_decode( $short ) )
            ), 'https://www.facebook.com/sharer.php' );

            return '<div class="gwsscs-social-custom-box back-img gwsscs-fb-style-box gwsscs-'.esc_attr($show_style).'_design" style="background-image: url('.esc_attr($style_image).');">
                        <p class="gwsscs-sharing-text">'.esc_html($short).'</p>
                        <p><a href="'.esc_url($facebook_share_url).'" target="_blank" class="gwsscs-social-custom-btn" rel="noopener noreferrer'.esc_attr($rel).'">Share On Facebook<span class="gwsscs-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="530" height="530" viewBox="0 0 530 530">
                                <defs>
                                <filter id="rect2987" x="0" y="0" width="530" height="530" filterUnits="userSpaceOnUse">
                                    <feOffset dy="3" input="SourceAlpha"/>
                                    <feGaussianBlur stdDeviation="3" result="blur"/>
                                    <feFlood flood-opacity="0.161"/>
                                    <feComposite operator="in" in2="blur"/>
                                    <feComposite in="SourceGraphic"/>
                                </filter>
                                </defs>
                                <g id="logo-facebook" transform="translate(9 6)">
                                <g transform="matrix(1, 0, 0, 1, -9, -6)" filter="url(#rect2987)">
                                    <rect id="rect2987-2" data-name="rect2987" width="512" height="512" rx="64" transform="translate(9 6)" fill="#3b5998"/>
                                </g>
                                <path id="f_1_" d="M286.968,456V273.538h61.244l9.17-71.1H286.969V157.04c0-20.588,5.721-34.619,35.235-34.619l37.655-.011v-63.6C353.35,57.934,331,56,304.992,56c-54.288,0-91.45,33.146-91.45,94v52.437h-61.4v71.1h61.4V456h73.427Z" fill="#fff"/>
                                </g>
                            </svg>       
                        </span>
                        </a></p>    
                    </div>';
        }

        public function shorten_share_tweet_text( $input, $length, $ellipsis = true, $strip_html = true ) {

            if ( $strip_html ) {
                $input = strip_tags( $input );
            }

            if ( function_exists( 'mb_internal_encoding' ) ) {
                if ( mb_strlen( $input ) <= $length ) {
                    return $input;
                }
                $last_space = mb_strripos( mb_substr( $input, 0, $length ), ' ' );
                $trimmed_text = mb_substr( $input, 0, $last_space );
        
                if ( $ellipsis ) {
                    $trimmed_text .= "…";
                }
        
                return $trimmed_text;
        
            } else {
        
                if ( strlen( $input ) <= $length ) {
                    return $input;
                }
        
                $last_space = strripos( substr( $input, 0, $length ), ' ' );
                $trimmed_text = substr( $input, 0, $last_space );
        
                if ( $ellipsis ) {
                    $trimmed_text .= "…";
                }
        
                return $trimmed_text;
            }
        }
        

    }
    new Gwsscs_Social_Custom_Share_Shortcode();
}