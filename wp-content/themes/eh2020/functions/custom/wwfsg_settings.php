<?php
/**
 * @author Hanafi Ahmat (hbahmat@wwf.sg)
 */

/**
 * Register wwf sg settings options page.
 */
if ( !function_exists( 'register_wwfsg_settings_options_page' ) ) {
    function register_wwfsg_settings_options_page() {
    	$page_title = 'WWF SG Settings';
    	$menu_title = 'WWF SG Settings';
    	$capability = 'manage_options';
    	$menu_slug  = 'wwfsg-settings';
    	$function   = 'wwfsg_settings';
        $position   = 7;
    	add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function, $position );
    }
    add_action( 'admin_menu', 'register_wwfsg_settings_options_page' );
}

if ( !function_exists( 'register_wwfsg_settings_params' ) ) {
    function register_wwfsg_settings_params() {
    	register_setting( 'wwfsg_settings', 'wwfsg_live_active_campaign_api_url', ['type'=>'string','default'=>'https://wwfsingapore297.api-us1.com'] );
    	register_setting( 'wwfsg_settings', 'wwfsg_live_active_campaign_api_key', ['type'=>'string','default'=>'d0f002f5c5ba903b3d74478a2952ca91acf04c5d72b4c75702a8051fc30296d62c3aec29'] );
    	register_setting( 'wwfsg_settings', 'wwfsg_live_active_campaign_signature_list_id', ['type'=>'number','default'=>'96'] );
        register_setting( 'wwfsg_settings', 'wwfsg_live_active_campaign_organisation_list_id', ['type'=>'number','default'=>'96'] );
        register_setting( 'wwfsg_settings', 'wwfsg_live_active_campaign_organisation_additional_tags', ['type'=>'string','default'=>''] );

    	register_setting( 'wwfsg_settings', 'wwfsg_sandbox_active_campaign_api_url', ['type'=>'string','default'=>'https://wwf-worldwidefundfornaturesingaporelimited1552298160.api-us1.com'] );
    	register_setting( 'wwfsg_settings', 'wwfsg_sandbox_active_campaign_api_key', ['type'=>'string','default'=>'15921cac81a99f6986315e1921a0882febb222405c7313e41a523ef16d289327ff2ab62d'] );
        register_setting( 'wwfsg_settings', 'wwfsg_sandbox_active_campaign_signature_list_id', ['type'=>'number','default'=>'1'] );
        register_setting( 'wwfsg_settings', 'wwfsg_sandbox_active_campaign_organisation_list_id', ['type'=>'number','default'=>'3'] );
        register_setting( 'wwfsg_settings', 'wwfsg_sandbox_active_campaign_organisation_additional_tags', ['type'=>'string','default'=>''] );

    	register_setting( 'wwfsg_settings', 'wwfsg_mode_is_live', ['type'=>'boolean','default'=>1] );
    	register_setting( 'wwfsg_settings', 'wwfsg_enable_info_log', ['type'=>'boolean','default'=>1] );
        register_setting( 'wwfsg_settings', 'wwfsg_enable_curl_log', ['type'=>'boolean','default'=>1] );
    }
    add_action( 'init', 'register_wwfsg_settings_params' );
}

if ( !function_exists( 'wwfsg_get_ac_api_url' ) ) {
    function wwfsg_get_ac_api_url( $env = null ) {
        // this is for settings page
        if ( !is_null( $env ) ) {
            if ( $env=='live' ) {
                return get_option('wwfsg_live_active_campaign_api_url');
            } else {
                return get_option('wwfsg_sandbox_active_campaign_api_url');
            }
        }
        // end for settings page

        if ( get_option('wwfsg_mode_is_live') ) {
        	return get_option('wwfsg_live_active_campaign_api_url');
        } else {
        	return get_option('wwfsg_sandbox_active_campaign_api_url');
        }
    }
}

if ( !function_exists( 'wwfsg_get_ac_api_key' ) ) {
    function wwfsg_get_ac_api_key( $env = null ) {
        if ( !is_null( $env ) ) {
            if ( $env=='live' ) {
                return get_option('wwfsg_live_active_campaign_api_key');
            } else {
                return get_option('wwfsg_sandbox_active_campaign_api_key');
            }
        }

        if ( get_option('wwfsg_mode_is_live') ) {
        	return get_option('wwfsg_live_active_campaign_api_key');
        } else {
        	return get_option('wwfsg_sandbox_active_campaign_api_key');
        }
    }
}

if ( !function_exists( 'wwfsg_get_ac_signature_list_id' ) ) {
    function wwfsg_get_ac_signature_list_id( $env = null ) {
        if ( !is_null( $env ) ) {
            if ( $env=='live' ) {
                return get_option('wwfsg_live_active_campaign_signature_list_id');
            } else {
                return get_option('wwfsg_sandbox_active_campaign_signature_list_id');
            }
        }

        if ( get_option('wwfsg_mode_is_live') ) {
        	return get_option('wwfsg_live_active_campaign_signature_list_id');
        } else {
        	return get_option('wwfsg_sandbox_active_campaign_signature_list_id');
        }
    }
}

if ( !function_exists( 'wwfsg_get_ac_organisation_list_id' ) ) {
    function wwfsg_get_ac_organisation_list_id( $env = null ) {
        if ( !is_null( $env ) ) {
            if ( $env=='live' ) {
                return get_option('wwfsg_live_active_campaign_organisation_list_id');
            } else {
                return get_option('wwfsg_sandbox_active_campaign_organisation_list_id');
            }
        }

        if ( get_option('wwfsg_mode_is_live') ) {
            return get_option('wwfsg_live_active_campaign_organisation_list_id');
        } else {
            return get_option('wwfsg_sandbox_active_campaign_organisation_list_id');
        }
    }
}

if ( !function_exists( 'wwfsg_get_ac_organisation_additional_tags' ) ) {
    function wwfsg_get_ac_organisation_additional_tags( $env = null ) {
        if ( !is_null( $env ) ) {
            if ( $env=='live' ) {
                return get_option('wwfsg_live_active_campaign_organisation_additional_tags');
            } else {
                return get_option('wwfsg_sandbox_active_campaign_organisation_additional_tags');
            }
        }

        if ( get_option('wwfsg_mode_is_live') ) {
            return get_option('wwfsg_live_active_campaign_organisation_additional_tags');
        } else {
            return get_option('wwfsg_sandbox_active_campaign_organisation_additional_tags');
        }
    }
}

if ( !function_exists( 'wwfsg_settings' ) ) {
    function wwfsg_settings() {
    	$file = dirname( __FILE__ ) . '/views/wwfsg-settings.php';
    	$params = [];
    	echo wwfsg_render_view( $file, $params );
    }
}
