
<h2 class="">WWF SG Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'wwfsg_settings' ); ?>
    <?php do_settings_sections( 'wwfsg_settings' ); ?>

    <?php $isLiveMode = get_option('wwfsg_mode_is_live')?>
    <?php $infoLogEnabled = get_option('wwfsg_enable_info_log')?>
    <?php $curlLogEnabled = get_option('wwfsg_enable_curl_log')?>
    <table class="form-table">

        <tr valign="top">
        	<th scope="row">Is LIVE Mode?</th>
        	<td>
        		<div style="display:inline;margin-right:20px;">
	        		<input type="radio" name="wwfsg_mode_is_live" value="1" <?php checked( $isLiveMode, 1, TRUE ); ?>/>
	        		<label>Yes</label>
	        	</div>
        		<div style="display:inline">
	        		<input type="radio" name="wwfsg_mode_is_live" value="0" <?php checked( $isLiveMode, 0, TRUE ); ?>/>
	        		<label>No</label>
	        	</div>
        	</td>
        </tr>

        <tr valign="top" class="live-mode">
        	<th scope="row">LIVE ActiveCampaign API URL</th>
        	<td>
        		<div style="width:50%;">
	        		<input type="text" class="large-text" name="wwfsg_live_active_campaign_api_url" value="<?= esc_attr( wwfsg_get_ac_api_url('live') ); ?>" />
	        	</div>
        	</td>
        </tr>
        <tr valign="top" class="live-mode">
        	<th scope="row">LIVE ActiveCampaign API Key</th>
        	<td>
        		<div style="width:50%;">
	        		<input type="text" class="large-text" name="wwfsg_live_active_campaign_api_key" value="<?= esc_attr( wwfsg_get_ac_api_key('live') ); ?>" />
	        	</div>
        	</td>
        </tr>
        <tr valign="top" class="live-mode">
        	<th scope="row">LIVE ActiveCampaign Signature List ID</th>
        	<td>
        		<div style="width:50%;">
	        		<input type="text" class="large-text" name="wwfsg_live_active_campaign_signature_list_id" value="<?= esc_attr( wwfsg_get_ac_signature_list_id('live') ); ?>" />
	        	</div>
        	</td>
        </tr>
        <tr valign="top" class="live-mode">
            <th scope="row">LIVE ActiveCampaign Organisation Signup List ID</th>
            <td>
                <div style="width:50%;">
                    <input type="text" class="large-text" name="wwfsg_live_active_campaign_organisation_list_id" value="<?= esc_attr( wwfsg_get_ac_organisation_list_id('live') ); ?>" />
                </div>
            </td>
        </tr>
        <tr valign="top" class="live-mode">
            <th scope="row">LIVE ActiveCampaign Organisation Signup List Tags</th>
            <td>
                <div style="width:50%;">
                    <input type="text" class="large-text" name="wwfsg_live_active_campaign_organisation_additional_tags" value="<?= esc_attr( wwfsg_get_ac_organisation_additional_tags('live') ); ?>" />
                    <p class="description">This information will be used for adding contact to Active Campaign with tags. When key in multiple Tags, separate them with comma. </p>
                </div>
            </td>
        </tr>


        <tr valign="top" class="sandbox-mode">
        	<th scope="row">TESTING ActiveCampaign API URL</th>
        	<td>
        		<div style="width:50%;">
	        		<input type="text" class="large-text" name="wwfsg_sandbox_active_campaign_api_url" value="<?= esc_attr( wwfsg_get_ac_api_url('sandbox') ); ?>" />
	        	</div>
        	</td>
        </tr>
        <tr valign="top" class="sandbox-mode">
        	<th scope="row">TESTING ActiveCampaign API Key</th>
        	<td>
        		<div style="width:50%;">
	        		<input type="text" class="large-text" name="wwfsg_sandbox_active_campaign_api_key" value="<?= esc_attr( wwfsg_get_ac_api_key('sandbox') ); ?>" />
	        	</div>
        	</td>
        </tr>
        <tr valign="top" class="sandbox-mode">
            <th scope="row">TESTING ActiveCampaign Signature List ID</th>
            <td>
                <div style="width:50%;">
                    <input type="text" class="large-text" name="wwfsg_sandbox_active_campaign_signature_list_id" value="<?= esc_attr( wwfsg_get_ac_signature_list_id('sandbox') ); ?>" />
                </div>
            </td>
        </tr>
        <tr valign="top" class="sandbox-mode">
            <th scope="row">TESTING ActiveCampaign Organisation Signup List ID</th>
            <td>
                <div style="width:50%;">
                    <input type="text" class="large-text" name="wwfsg_sandbox_active_campaign_organisation_list_id" value="<?= esc_attr( wwfsg_get_ac_organisation_list_id('sandbox') ); ?>" />
                </div>
            </td>
        </tr>
        <tr valign="top" class="sandbox-mode">
            <th scope="row">TESTING ActiveCampaign Organisation Signup List Tags</th>
            <td>
                <div style="width:50%;">
                    <input type="text" class="large-text" name="wwfsg_sandbox_active_campaign_organisation_additional_tags" value="<?= esc_attr( wwfsg_get_ac_organisation_additional_tags('sandbox') ); ?>" />
                    <p class="description">This information will be used for adding contact to Active Campaign with tags. When key in multiple Tags, separate them with comma. </p>
                </div>
            </td>
        </tr>


        <tr valign="top">
            <th scope="row">Enable Info Log?</th>
            <td>
                <div style="display:inline;margin-right:20px;">
                    <input type="radio" name="wwfsg_enable_info_log" value="1" <?php checked( $infoLogEnabled, 1, TRUE ); ?>/>
                    <label>Yes</label>
                </div>
                <div style="display:inline">
                    <input type="radio" name="wwfsg_enable_info_log" value="0" <?php checked( $infoLogEnabled, 0, TRUE ); ?>/>
                    <label>No</label>
                </div>
                <p class="description">This can be used in conjunction with Log Viewer plugin.<br>Logged messages can be viewed at Tools > Log Viewer and the file name is `wwfsg-info.log`.<br>The log file is located at `/wp-content/wwfsg-info.log`</p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row">Enable cUrl Log?</th>
            <td>
                <div style="display:inline;margin-right:20px;">
                    <input type="radio" name="wwfsg_enable_curl_log" value="1" <?php checked( $curlLogEnabled, 1, TRUE ); ?>/>
                    <label>Yes</label>
                </div>
                <div style="display:inline">
                    <input type="radio" name="wwfsg_enable_curl_log" value="0" <?php checked( $curlLogEnabled, 0, TRUE ); ?>/>
                    <label>No</label>
                </div>
                <p class="description">This can be used in conjunction with Log Viewer plugin.<br>Logged cUrl activities can be viewed at Tools > Log Viewer and the file name is `wwfsg-curl.log`.<br>The log file is located at `/wp-content/wwfsg-curl.log`</p>
            </td>
        </tr>

    </table>

    <?php submit_button(); ?>
</form>

<?php //wwfsg_info_log( ['chak!', 'key' => 'mumble jumble' ], [ 'file'=>__FILE__, 'function'=>__FUNCTION__, 'line'=>__LINE__ ] )?>

<script>
	jQuery(function($){
		$("input[name='wwfsg_mode_is_live']").on('click', function() {
			if ($(this).val()==1) {
				$('.sandbox-mode').hide();
				$('.live-mode').show();
			} else if ($(this).val()==0) {
				$('.sandbox-mode').show();
				$('.live-mode').hide();
			}
		}).each(function(i,v) {
			if ($(this).is(':checked')) {
				if ($(this).val()==1) {
					$('.sandbox-mode').hide();
					$('.live-mode').show();
				} else if ($(this).val()==0) {
					$('.sandbox-mode').show();
					$('.live-mode').hide();
				}
			}
		});
	});
</script>
