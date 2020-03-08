<?php
/*
Plugin Name: Registered User Sync ActiveCampaign
Plugin URI: https://wordpress.org/plugins/rus-activecampaign/
Description: Allows you to sync registered users to the Active Campaigns Email Marketing app.
Author: Pravin Durugkar
Version: 1.2.5
Requires PHP: 5.6
Author URI: https://profiles.wordpress.org/pravind
Text Domain: rus-activecampaign
Domain Path: /languages
*/

define( 'RUSAC_DIR',  plugin_dir_path( __FILE__ ) );
define( 'RUSAC_URI', plugin_dir_url( __FILE__ ), true );
define( 'RUSAC_BASENAME', plugin_basename(__FILE__) );

class RUSActiveCampaign {
    public $debug = false;
    function __construct(){
        $sync_switch = get_option('rusac_sync_switch',0);
        $this->debug = get_option('rusac_sync_debug',false);

        add_action('init',array($this, '_rusac_include_files'));
        add_action('rusac_add_new_address', array($this, 'rusac_send_data_to_ac'));
        add_action('rusac_edit_address', array($this, 'rusac_update_profile_trigger'));

        //edit user's data
        add_filter('email_change_email', array($this, 'rusac_update_active_campaign_data'), 10, 3);
        //add_action('profile_update', array($this, 'rusac_update_profile_trigger'), 10, 2);
        add_action('profile_update', array($this, 'rusac_reset_for_sync_trigger'), 10, 2);
        if ($sync_switch) { 
            $this->rusac_set_sync_with_activecampaign();
            // the registered cron hook, that'll fire your function
            add_action('rusac_sync_users_with_active_campaign', array($this, 'sync_active_campaign_list'));
        }else{
            $this->rusac_clear_sync_with_activecampaign();
        }
    }

    function _rusac_include_files() { 
        include RUSAC_DIR.'/inc/debug_logger.php';
        include RUSAC_DIR.'/inc/rusac_settings.php';
    }

    function rusac_load_api_details($api_action = 'contact_add', $api_output = 'serialize') {
        $api_key = get_option('rusac_api_key');
        $api_url = get_option('rusac_api_url');
        //$api_output = 'serialize';
        //$api_action = 'contact_add';

        $params = array(

            // the API Key can be found on the "Your Settings" page under the "API" tab.
            // replace this with your API Key
            //'api_key'      => '875007a60a311ade8c1f038b1d918bd437af9970aeab03584e2ab3580e3def928fd71868',
            'api_key'      => $api_key,

            // this is the action that adds a contact
            'api_action'   => $api_action,

            // define the type of output you wish to get back
            // possible values:
            // - 'xml'  :      you have to write your own XML parser
            // - 'json' :      data is returned in JSON format and can be decoded with
            //                 json_decode() function (included in PHP since 5.2.0)
            // - 'serialize' : data is returned in a serialized format and can be decoded with
            //                 a native unserialize() function
            'api_output'   => $api_output,
            'api_url'     => $api_url
        );
        return apply_filters('rusac_fetch_api_details' ,$params);
    }

    function rusac_fetch_general_settings() {
        $settings = array();
        $settings['autoresponder'] = 1;
        $settings['tags'] = get_option('rusac_default_tags', '');
        $settings['status'] = 1;

        $primary_list_id = get_option('rusac_list_id');
        
        $settings['primary_list_id'] = ($primary_list_id != '') ? $primary_list_id : 1;
        return apply_filters('rusac_load_settings', $settings);
    }

    function rusac_prepare_registered_user_data($user_id) {
        // here we define the data we are posting in order to perform an update
        $user_obj = get_userdata($user_id);

        $settings = $this->rusac_fetch_general_settings();
        
        $first_name = get_user_meta( $user_id, 'first_name', true );
        $last_name  = get_user_meta( $user_id, 'last_name', true );
        $mobile     = get_user_meta( $user_id, 'mobile', true );
        
        $user_data = array(
            'email'                    => $user_obj->user_email,
            //'first_name'               => $first_name,
            //'last_name'                => $last_name,
            //'phone'                    => $mobile,
            //'orgname'                  => 'Acme, Inc.',
            'tags'                     => $settings['tags'],
            //'ip4'                    => '127.0.0.1',

            // any custom fields
            //'field[345,0]'           => 'field value', // where 345 is the field ID
            //'field[%PERS_1%,0]'      => 'field value', // using the personalization tag instead (make sure to encode the key)

            // assign to lists:
            'p['.$settings['primary_list_id'].']' => $settings['primary_list_id'], // example list ID (REPLACE '123' WITH ACTUAL LIST ID, IE: p[5] = 5)
            'status['.$settings['status'].']'     => 1, // 1: active, 2: unsubscribed (REPLACE '123' WITH ACTUAL LIST ID, IE: status[5] = 1)
            //'form'          => 1001, // Subscription Form ID, to inherit those redirection settings
            //'noresponders[123]'      => 1, // uncomment to set "do not send any future responders"
            //'sdate[123]'             => '2009-12-07 06:00:00', // Subscribe date for particular list - leave out to use current date/time
            // use the folowing only if status=1
            'instantresponders['.$settings['autoresponder'].']' => 1, // set to 0 to if you don't want to sent instant autoresponders
            //'lastmessage[123]'       => 1, // uncomment to set "send the last broadcast campaign"

            //'p[]'                    => 345, // some additional lists?
            //'status[345]'            => 1, // some additional lists?
        );
        if (isset($first_name)) {
            $user_data['first_name'] = $first_name;
        }
        if (isset($last_name)) {
            $user_data['last_name'] = $last_name;
        }
        if(isset($mobile)) {
            $user_data['phone'] = $mobile;
        }

        return apply_filters('rusac_fetch_registered_user_data' ,$user_data, $user_id);
    }

    function rusac_send_data_to_ac($user_id){

        // By default, this sample code is designed to get the result from your ActiveCampaign installation and print out the result
        $params = $this->rusac_load_api_details();
        $params['api_action'] = 'contact_add';//'contact_sync';

        $url    = !empty($params['api_url']) ? $params['api_url'] : 'http://account.api-us1.com';
        unset($params['api_url']);
        $post   = $this->rusac_prepare_registered_user_data($user_id);
        // This section takes the input fields and converts them to the proper format
        $query = "";
        foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
        $query = rtrim($query, '& ');

        // This section takes the input data and converts it to the proper format
        $data = "";
        foreach( $post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
        $data = rtrim($data, '& ');

        // clean up the url
        $url = rtrim($url, '/ ');

        // This sample code uses the CURL library for php to establish a connection,
        // submit your request, and show (print out) the response.
        if ( !function_exists('curl_init') ){ 
            echo ('CURL not supported. (introduced in PHP 4.0.2)');
            return;
        }

        // If JSON is used, check if json_decode is present (PHP 5.2.0+)
        if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
            echo ('JSON not supported. (introduced in PHP 5.2.0)');
            return;
        }

        // define a final API request - GET
        $api = $url . '/admin/api.php?' . $query;
        
        do_action('rusac_before_send_data_to_ac', $api, $user_id);

        $request = curl_init($api); // initiate curl object
        curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
        curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

        $response = (string)curl_exec($request); // execute curl post and store results in $response

        curl_close($request); // close curl object

        if ( !$response ) {
            echo('Nothing was returned. Do you have a connection to Email Marketing server?');
            return;
        }

        // unserializer
        $result = unserialize($response);

        if ($this->debug) {
            DebugLogger::writeLog("Insert Post:".print_r($post, true));
            DebugLogger::writeLog("Result For Inser:".print_r($result, true)); //$updated_user
        }

        do_action('rusac_after_sent_data_to_ac', $result);
        return $result;
    }

    function rusac_set_sync_with_activecampaign() {
        // add a 60 second cron schedule
        $rusac_sync_schedule = get_option('rusac_sync_schedule', 1);
        $rusac_sync_schedule = (intval($rusac_sync_schedule) <= 0) ? 1 : $rusac_sync_schedule;

        $rusac_sync_schedule = 60 * intval($rusac_sync_schedule);
        add_filter('cron_schedules', function ( $schedules ) {
            global $rusac_sync_schedule;
            $rusac_sync_schedule = ($rusac_sync_schedule <= 0) ? 60 : $rusac_sync_schedule;
            $schedules['everyminute'] = array(
                'interval' => $rusac_sync_schedule,
                'display' => __('Every Minute')
            );
            return $schedules;
        });

        // register your cronjob
        add_action('wp', function () {
            if ( !wp_next_scheduled( 'rusac_sync_users_with_active_campaign' ) )
                wp_schedule_event(time(), 'everyminute', 'rusac_sync_users_with_active_campaign');
        });

        /*// the registered cron hook, that'll fire your function
        add_action('my_task_sync_cronjob', 'my_task_sync_method');*/
    }

    function rusac_query_to_active_campaign($params, $post=array()){
        // This section takes the input fields and converts them to the proper format
        $query = "";
        $data = "";

        $url = !empty($params['api_url']) ? $params['api_url'] : 'http://account.api-us1.com';
        unset($params['api_url']);

        // clean up the url
        $url = rtrim($url, '/ ');

        foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
        $query = rtrim($query, '& ');

        if (!empty($post)) {
            foreach( $post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
            $data = rtrim($data, '& ');
        }        

        // This sample code uses the CURL library for php to establish a connection,
        // submit your request, and show (print out) the response.
        if ( !function_exists('curl_init') ) die('CURL not supported. (introduced in PHP 4.0.2)');

        // If JSON is used, check if json_decode is present (PHP 5.2.0+)
        if ( $params['api_output'] == 'json' && !function_exists('json_decode') ) {
            die('JSON not supported. (introduced in PHP 5.2.0)');
        }

        // define a final API request - GET
        $api = $url . '/admin/api.php?' . $query;

        $request = curl_init($api); // initiate curl object
        curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
        //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
        if (!empty($data)) {
            curl_setopt($request, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

        $response = (string)curl_exec($request); // execute curl fetch and store results in $response

        // additional options may be required depending upon your server configuration
        // you can find documentation on curl options at http://www.php.net/curl_setopt
        curl_close($request); // close curl object

        if ( !$response ) {
            die('Nothing was returned. Do you have a connection to Email Marketing server?');
        }

        // This line takes the response and breaks it into an array using:
        // JSON decoder
        //$result = json_decode($response);
        // unserializer
        $result = unserialize($response);
        return $result;
    }

    function rusac_update_active_campaign_data($email_to_email, $old_user, $updated_user) {
        $old_email = '';
        if (isset($email_to_email['to'])) {
            $old_email = $email_to_email['to'];
        }else{
            //$old_email = $updated_user['user_email'];
            $old_email = $updated_user['data']->user_email;
        }
        
        //$new_email = $updated_user['user_email']; //new email
        $user_id   = $updated_user['ID'];
        
        //DebugLogger::writeLog("Old Email:".$old_email);
        //DebugLogger::writeLog("New Email:".$new_email);

        $contact_id = get_user_meta( $user_id, '_rusac_synced_user_id', true);
        if (empty($contact_id)) {
            $params = $this->rusac_load_api_details();
            $params['api_action'] = 'contact_view_email';
            $params['email'] = $old_email;
            $result = $this->rusac_query_to_active_campaign($params);
            $contact_id = $result['id'];
            update_user_meta( $user_id, '_rusac_synced_user_id', $result['id']);
        }

        if(!empty($contact_id)){
            $params = $this->rusac_load_api_details();
            $params['api_action'] = 'contact_edit';

            $post = $this->rusac_prepare_registered_user_data($user_id);
            
            //Remove contact tags first
            if (isset($post['remove_tags'])) {
                $tags = $post['remove_tags'];
                if (!is_array($tags)) {
                    $tags = explode(',', $tags);
                }
                $this->rusac_remove_contact_tag($contact_id, $tags);
                unset($post['remove_tags']);
            }

            $post_fields['id'] = $contact_id; // example contact ID to modify
            $post = array_merge($post_fields, $post);

            $result = $this->rusac_query_to_active_campaign($params, $post);
            
            if ($this->debug) {
                DebugLogger::writeLog("Edited Post:".print_r($post,true));
                DebugLogger::writeLog("Result For Edits:".print_r($result,true)); //$updated_user
            }

            if (!empty($result['result_code']) && $result['result_code'] == 1) {
                update_user_meta( $user_id, '_rusac_synced_user', $result);
            }
        }
        return $email_to_email;
    }

    function rusac_update_profile_trigger($user_id, $old_user=array()) {
        $user_obj = get_user_by( "ID", $user_id );   
        $this->rusac_update_active_campaign_data(array(), (Array)$old_user, (Array)$user_obj);
    }

    function rusac_clear_sync_with_activecampaign() {
        // add a 60 second cron schedule
        wp_clear_scheduled_hook( 'rusac_sync_users_with_active_campaign' );
    }

    function sync_active_campaign_list() {
        //sync logic   //_rusac_synced_user

        //fetch user setting
        $number_of_users = get_option('rusac_no_users_per_run', 5);
        $allowed_roles = get_option('rusac_allowed_roles');
        $args = array(
                    //'role' => 'subscriber',
                    'meta_query' => array(
                                        array(
                                             'key' => '_rusac_synced_user',
                                             'compare'  => 'NOT EXISTS'
                                        )
                                    ),
                    'role__in' => $allowed_roles
                );
        $args = apply_filters('rusac_user_query_args', $args);
        $users = get_users( $args );

        $count = 0;
        if(!empty($users)){
            foreach ( $users as $user ) {
                $result = $this->rusac_send_data_to_ac($user->ID);
                
                if (!empty($result['result_code']) && $result['result_code'] == 1) {
                    update_user_meta( $user->ID, '_rusac_synced_user', $result);
                }elseif(stripos($result['result_message'] , 'list that does not allow duplicates') !== false ){
                    //var_dump( $user->ID );
                    $active_list_id = $result[0]['id'];
                    update_user_meta( $user->ID, '_rusac_synced_user_id', $active_list_id);
                    $this->rusac_update_profile_trigger($user->ID);
                    //update_user_meta( $user->ID, '_rusac_synced_user', $result);
                }
                if(++$count >= $number_of_users) { break; }
            }
        }
    }

    function rusac_reset_for_sync_trigger($user_id, $old_user) {
        update_user_meta( $user_id, '_rusac_synced_user', '');
        delete_user_meta( $user_id, '_rusac_synced_user');
    }
    
    function convert_user_id($user) {
        return $user->ID;
    }

    function rusac_remove_contact_tag($id, $tags = array()){
        $params = $this->rusac_load_api_details();
        $params['api_action'] = 'contact_tag_remove';
        foreach ($tags as $tag) {
            $post = array(
                    //'email' => 'test@test.com', // contact email address (pass this OR the contact ID)
                    'id' => $id, // contact ID (pass this OR the contact email address)
                    'tags' => $tag
                );

            $result = $this->rusac_query_to_active_campaign($params, $post);
            
            if ($this->debug) {
                DebugLogger::writeLog("Tag Remove:".print_r($post, true));
                DebugLogger::writeLog("Tag Remove Result:".print_r($result, true)); //$updated_user
            }
        }
        
        if (isset($result['result_code']) && $result['result_code'] == 1) {
            return true;
        }
        return false;
    }
}

// create an instance of rusacTestimonials
if ( !isset($RUSActiveCampaign) ) {
    $RUSActiveCampaign = new RUSActiveCampaign();
}