<?php 
/**
 * RUSAC_Settings
 * Settings for the plugin
 */
class RUSAC_Settings {
	
	function __construct(){
		add_action( 'admin_init', array($this, 'rusac_register_settings' ) );
        add_action('admin_menu', array($this, 'rusac_register_options_page') );
        add_filter( 'plugin_action_links_'.RUSAC_BASENAME, array($this, 'rusac_add_action_links') );
        add_action('wp_ajax_rusac_clear_the_debug_log', array($this, 'rusac_clear_the_debug_log') );
	}

    function rusac_clear_the_debug_log() {
        $file_path = RUSAC_DIR.'/inc/RUSAC_Logger.log';
        unlink($file_path);
        wp_send_json_success();
    }

	function rusac_register_settings() {
        $api_url = get_option('rusac_api_url');
        if(empty($api_url)){ 
            add_option( 'rusac_api_url', '');
        }
        $api_key = get_option('rusac_api_key');
        if(empty($api_key)) {
            add_option( 'rusac_api_key', '');
        }
        $list_id = get_option('rusac_list_id');
        if(empty($list_id)) {
            add_option( 'rusac_list_id', '1');
        }
        $sync_switch = get_option('rusac_sync_switch');
        if(empty($sync_switch)) {
            add_option( 'rusac_sync_switch', 0);
        }
        $rusac_no_users_per_run = get_option('rusac_no_users_per_run');
        if(empty($rusac_no_users_per_run)) {
            add_option( 'rusac_no_users_per_run', 5);
        }
        $rusac_allowed_roles = get_option('rusac_allowed_roles');
        if (empty($rusac_allowed_roles)) {
            add_option( 'rusac_allowed_roles' , array('subscriber'));
        }
        $rusac_default_tags = get_option('rusac_default_tags');
        if (empty($rusac_default_tags)) {
            add_option( 'rusac_default_tags' , '');
        }
        $rusac_sync_debug = get_option('rusac_sync_debug');
        if (empty($rusac_sync_debug)) {
            add_option( 'rusac_sync_debug' , 0);
        }
        $rusac_sync_schedule = get_option('rusac_sync_schedule');
        if (empty($rusac_sync_schedule)) {
            add_option( 'rusac_sync_schedule' , 1);
        }
        
        register_setting( 'rusac_options_group', 'rusac_api_url', 'rusac_callback' );
        register_setting( 'rusac_options_group', 'rusac_api_key', 'rusac_callback' );
        register_setting( 'rusac_options_group', 'rusac_list_id', 'rusac_callback' );
        register_setting( 'rusac_options_group', 'rusac_sync_switch', 'rusac_callback' );
        register_setting( 'rusac_options_group', 'rusac_no_users_per_run', 'rusac_callback' );
        register_setting( 'rusac_options_group', 'rusac_allowed_roles', 'rusac_callback' );
        register_setting( 'rusac_options_group', 'rusac_default_tags', 'rusac_callback' );
        register_setting( 'rusac_options_group', 'rusac_sync_debug', 'rusac_callback' );
        register_setting( 'rusac_options_group', 'rusac_sync_schedule', 'rusac_callback' );
        //
    }
    function rusac_register_options_page() {
        add_options_page('RU Active Campaigns Settings', 'RU Active Campaigns Settings', 'manage_options', 'rusac-settings', array($this,'rusac_options_page'));
    }

    function rusac_options_page() {
        global $wp_roles;
    ?>
    <div class="rusac-settings-section">
        <style type="text/css">
        form.rusac-settings input[type="text"]{padding:5px 10px;font-size:14px;width:300px;max-width:100%;}
        form.rusac-settings label{font-size:14px;line-height:30px;}
        form.rusac-settings .rusac-sync-settings label{padding-right:15px;}
        form.rusac-settings .allowed-roles label{padding-right:15px;}
        form.rusac-settings .advanced-settings{padding-top:50px;padding-bottom: 10px;}
        .debug-window pre{overflow-x:hidden;max-height:500px;overflow-y:auto;max-width:800px;}
        .buy-me-coffee{margin-top: 100px; font-style: italic;}
        </style>
        <h2><?php _e('RU Active Campaigns Settings','rus-activecampaign');?></h2>
        <form method="post" class="rusac-settings" action="options.php">
            <?php settings_fields( 'rusac_options_group' ); ?>
           <p></p>
            <table>
                <tr valign="top">
                    <th scope="row"><label for="rusac_api_url"><?php _e('API URL','rus-activecampaign');?></label></th>
                    <td><input type="text" id="rusac_api_url" name="rusac_api_url" value="<?php echo (get_option('rusac_api_url')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="rusac_api_key"><?php _e('API Key','rus-activecampaign');?></label></th>
                    <td><input type="text" id="rusac_api_key" name="rusac_api_key" value="<?php echo (get_option('rusac_api_key')); ?>" /> </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="rusac_list_id"><?php _e('Listing ID','rus-activecampaign');?></label></th>
                    <td><input type="text" id="rusac_list_id" name="rusac_list_id" value="<?php echo (get_option('rusac_list_id')); ?>" /> </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="rusac_sync_switch"><?php _e('Auto Sync','rus-activecampaign');?></label></th>
                    <td class="rusac-sync-settings"><?php $sync_option = get_option('rusac_sync_switch');?>
                        <label for="rusac_sync_switch_on"><input type="radio" id="rusac_sync_switch_on" name="rusac_sync_switch" value="1" <?php if ($sync_option == 1): ?> checked <?php endif ?>> On</label>
                       <label for="rusac_sync_switch_off"> <input type="radio" id="rusac_sync_switch_off" name="rusac_sync_switch" value="0" <?php if ($sync_option == 0): ?> checked <?php endif ?>> Off</label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="rusac_no_users_per_run"><?php _e('Number Of Users Sync per run','rus-activecampaign');?></label></th>
                    <td class="rusac-sync-settings"><input type="number" min=1 max=100 name="rusac_no_users_per_run" id="rusac_no_users_per_run" value="<?php echo get_option('rusac_no_users_per_run'); ?>"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="rusac_default_tags"><?php _e('Default User Tags','rus-activecampaign');?></label>
                        <p class="small"><?php _e('(Separated by ,)','rus-activecampaign');?></p>
                    </th>
                    <td class="rusac-sync-settings"><input type="text" name="rusac_default_tags" id="rusac_default_tags" value="<?php echo get_option('rusac_default_tags'); ?>"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="allowed_user_roles"><?php _e('Allow user roles to be sync','rus-activecampaign');?></label></th>
                    <td class="allowed-roles">
                    <?php $all_roles = $wp_roles->roles;
                    $allowed_roles = get_option('rusac_allowed_roles');
                    foreach ($all_roles as $key => $value) { ?>
                        <label for="role_<?php echo $key;?>"><input type="checkbox" id="role_<?php echo $key;?>" name="rusac_allowed_roles[]" value="<?php echo $key; ?>" <?php if (in_array($key,$allowed_roles)): ?> checked <?php endif ?>> <?php echo $value['name']; ?></label>
                    <?php }
                ?></td></tr>
                <tr><th class="advanced-settings"><?php echo _e('Advanced Settings','rus-activecampaign'); ?></th></tr>
                <tr valign="top">
                    <th scope="row"><label for="rusac_sync_schedule"><?php _e('Schedule Sync','rus-activecampaign');?></label></th>
                    <td class="allowed-roles">
                        <?php $rusac_sync_schedule = get_option('rusac_sync_schedule',1); ?>
                        <label for="rusac_sync_debug"> <?php _e('Sync Users in every','rus-activecampaign');?> <input type="number" id="rusac_sync_schedule" name="rusac_sync_schedule" value="<?php echo $rusac_sync_schedule; ?>"> <?php _e('mins','rus-activecampaign'); ?></label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="allowed_user_roles"><?php _e('Debug','rus-activecampaign');?></label></th>
                    <td class="allowed-roles">
                        <?php $rusac_sync_debug = get_option('rusac_sync_debug',false); ?>
                        <label for="rusac_sync_debug"><input type="checkbox" id="rusac_sync_debug" name="rusac_sync_debug" value="1" <?php if ($rusac_sync_debug): ?> checked <?php endif ?>> ON</label>
                    </td>
                </tr>
            </table>
            <?php  submit_button(); ?>
        </form>
        <?php if ($rusac_sync_debug) { 
            $file_path = RUSAC_DIR.'/inc/RUSAC_Logger.log'; ?>
        <div class="debug-window">
            <h2><?php _e('Debug Window','rus-activecampaign'); ?></h2>
            <?php if(file_exists($file_path)) {?>
            <div class="debug-controls">
                <a href="<?php echo RUSAC_URI;?>/inc/RUSAC_Logger.log" class="button" download><?php _e('Download Debug Log','rus-activecampaign');?></a>
                <a href="javascript:;" class="button" data-action="clear-log"><?php _e('Clear Debug Log','rus-activecampaign');?></a>
            </div>
            <pre><?php echo @file_get_contents($file_path);?></pre>
            <?php } ?>
        </div>
        <?php } ?>

        <div class="buy-me-coffee">
            <h4><?php _e("Donate to Support", 'rus-activecampaign'); ?></h4>
            <p><?php _e("Hey guys If you like this plugin you can buy me a coffee here.", 'rus-activecampaign'); ?></p>
            <p><style>.bmc-button img{width: 35px !important;margin-bottom: 1px !important;box-shadow: none !important;border: none !important;vertical-align: middle !important;}.bmc-button{padding: 7px 10px 7px 10px !important;line-height: 35px !important;height:51px !important;min-width:217px !important;text-decoration: none !important;display:inline-flex !important;color:#ffffff !important;background-color:#FF813F !important;border-radius: 5px !important;border: 1px solid transparent !important;padding: 7px 10px 7px 10px !important;font-size: 28px !important;letter-spacing:0.6px !important;box-shadow: 0px 1px 2px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;margin: 0 auto !important;font-family:'Cookie', cursive !important;-webkit-box-sizing: border-box !important;box-sizing: border-box !important;-o-transition: 0.3s all linear !important;-webkit-transition: 0.3s all linear !important;-moz-transition: 0.3s all linear !important;-ms-transition: 0.3s all linear !important;transition: 0.3s all linear !important;}.bmc-button:hover, .bmc-button:active, .bmc-button:focus {-webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;text-decoration: none !important;box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;opacity: 0.85 !important;color:#ffffff !important;}</style><link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet"><a class="bmc-button" target="_blank" href="https://www.buymeacoffee.com/PravinD"><img src="https://cdn.buymeacoffee.com/buttons/bmc-new-btn-logo.svg" alt="Buy me a coffee"><span style="margin-left:15px;font-size:28px !important;"><?php _e("Buy me a coffee", 'rus-activecampaign'); ?></span></a></p>
        </div>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($){
        jQuery('[data-action="clear-log"]').click(function(event){
            var admin_ajax_url = '<?php echo admin_url('admin-ajax.php');?>';
            var dataObj = {action: 'rusac_clear_the_debug_log', };
            jQuery.ajax({
                type:'post',
                url: admin_ajax_url,
                data: dataObj,
                success: function(resp){
                    if(resp.success){
                        jQuery('[data-action="clear-log"]').remove();
                        jQuery('.debug-window pre').html('');
                    }
                }
            });
        });
    });

    </script>
    <?php
    }
    

    function rusac_add_action_links( $links ) {
        $mylinks = array(
            '<a href="' . admin_url( 'options-general.php?page=rusac-settings' ) .'&token='.time(). '">'.__( 'Settings', 'rus-activecampaign' ).'</a>',
        );
        return array_merge( $mylinks, $links );
    }
}

if ( !isset($RUSAC_Settings) ) {
	$RUSAC_Settings = new RUSAC_Settings();
}