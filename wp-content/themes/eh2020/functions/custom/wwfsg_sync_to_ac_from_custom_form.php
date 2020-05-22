<?php
/**
 * @author Hanafi Ahmat (hbahmat@wwf.sg)
 */

function wwfsg_add_ac_after_form_submit_success($contact_form)
{
    if ($contact_form->title()!='Listing Request Form') {
        return;
    }

    $ok = true;
    $required = [
        'requestor-name',
        'requestor-email',
        'requestor-phone',
        'requestor-organization',
        'requestor-job',
    ];
    foreach ($required as $req) {
        if (!array_key_exists($req, $_POST)) {
            $ok = false;
            break;
        }
    }
    if (!$ok) {
        wwfsg_info_log("Syncing contact to active campaign but required information is not available. Please check that required attributes were not removed from Listing Contact Form.", ['file'=>__FILE__,'function'=>__FUNCTION__,'line'=>__LINE__]);
        // @todo send alert email to webmaster
        return;
    }

    // By default, this sample code is designed to get the result from your ActiveCampaign installation and print out the result
    $list_id    = wwfsg_get_ac_organisation_list_id();
    $tags       = wwfsg_get_ac_organisation_additional_tags();

    $jobField = '%JOB_TITLE%';
    $orgField = '%ORGANIZATION_NAME%';

    // here we define the data we are posting in order to perform an update
    $customFields = [
        $jobField => [
            'title'         => 'Job Title', // internal field name
            'type'          => 1, // 1 = Text Field, 2 = Text Box, 3 = Checkbox, 4 = Radio, 5 = Dropdown, 6 = Hidden field, 7 = List Box, 9 = Date
            'req'           => 0, // required? 1 or 0
            'perstag'       => $jobField, // unique tag used as a placeholder for dynamic content
            "p[$list_id]"   => $list_id, // for use in lists. use 0 for All lists
        ],
        $orgField => [
            'title'         => 'Organization Name', // internal field name
            'type'          => 1, // 1 = Text Field, 2 = Text Box, 3 = Checkbox, 4 = Radio, 5 = Dropdown, 6 = Hidden field, 7 = List Box, 9 = Date
            'req'           => 0, // required? 1 or 0
            'perstag'       => $orgField, // unique tag used as a placeholder for dynamic content
            "p[$list_id]"   => $list_id, // for use in lists. use 0 for All lists
        ],
    ];

    $listSchema = readActiveCampaignList($list_id);
    foreach ($customFields as $fieldTag => $customField) {
        if (!empty($listSchema[0]['fields'])) {
            $exists = false;
            foreach ($listSchema[0]['fields'] as $field) {
                if ($field['tag']==$fieldTag) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                if (!createActiveCampaignListCustomField($customField)) {
                    wwfsg_info_log("Failed to create ac list custom field $fieldTag", ['file'=>__FILE__,'function'=>__FUNCTION__,'line'=>__LINE__]);
                    return;
                } else {
                    wwfsg_info_log("AC list custom field $fieldTag created sucessfully.", ['file'=>__FILE__,'function'=>__FUNCTION__,'line'=>__LINE__]);
                }
            }
        } else {
            if (!createActiveCampaignListCustomField($customField)) {
                wwfsg_info_log("Failed to create ac list custom field $fieldTag", ['file'=>__FILE__,'function'=>__FUNCTION__,'line'=>__LINE__]);
                return;
            } else {
                wwfsg_info_log("AC list custom field $fieldTag created sucessfully.", ['file'=>__FILE__,'function'=>__FUNCTION__,'line'=>__LINE__]);
            }
        }
    }

    $params = array(
        'api_action'   => 'contact_sync',
    );

    // here we define the data we are posting in order to perform an update
    $post = array(
        'email'                         => sanitize_email(trim($_POST['requestor-email'])),
        'first_name'                    => sanitize_text_field(trim($_POST['requestor-name'])), // just put full name here and leave out `last_name` since name fields are not compulsory
        // 'last_name'                      => $_POST['requestor_last_name'], // this is commented since the form has only one  name field but active campaign separates them
        'phone'                         => sanitize_text_field(str_replace(' ', '', trim($_POST['requestor-phone']))),
        'customer_acct_name'            => sanitize_text_field(trim($_POST['requestor-organization'])),
        "field[$jobField,0]"            => sanitize_text_field(trim($_POST['requestor-job'])), // using the personalization tag instead
        "field[$orgField,0]"            => sanitize_text_field(trim($_POST['requestor-organization'])), // using the personalization tag instead
        'tags'                          => $tags,
        "p[$list_id]"                   => $list_id, // example list ID (REPLACE '123' WITH ACTUAL LIST ID, IE: p[5] = 5)
        "status[$list_id]"              => 1, // 1: active, 2: unsubscribed (REPLACE '123' WITH ACTUAL LIST ID, IE: status[5] = 1)
        "instantresponders[$list_id]"   => 1, // set to 0 to if you don't want to sent instant autoresponders
    );

    $result = doActiveCampaignApiCall($params, $post);
    wwfsg_info_log("\nSync to AC ".($result['result_code'] ? 'Sucessfully' : 'Failed').":".$result['result_message'], ['file'=>__FILE__,'function'=>__FUNCTION__,'line'=>__LINE__]);
}
add_action( 'wpcf7_mail_sent', 'wwfsg_add_ac_after_form_submit_success' );

function readActiveCampaignList($list_id) {
    $params = array(
        'api_action'   => 'list_list',
        'ids'          => $list_id,
        // include global custom fields? by default, it does not
        'global_fields'      => true,
        // whether or not to return ALL data, or an abbreviated portion (set to 0 for abbreviated)
        'full'         => 1,
    );
    return doActiveCampaignApiCall($params);
}

function createActiveCampaignListCustomField(array $post) {
    $params = array(
        'api_action'   => 'list_field_add',
    );
    $result = doActiveCampaignApiCall($params, $post);
    return $result['result_code'];
}

function doActiveCampaignApiCall($params, $post = null) {
    // By default, this sample code is designed to get the result from your ActiveCampaign installation and print out the result
    $api_url    = wwfsg_get_ac_api_url();
    $api_key    = wwfsg_get_ac_api_key();
    $params     = array_merge($params, array(
        'api_key'      => $api_key,
        'api_output'   => 'serialize',
    ));

    // This section takes the input fields and converts them to the proper format
    $query = "";
    foreach( $params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
    $query = rtrim($query, '& ');

    if (!is_null($post)) {
        // This section takes the input data and converts it to the proper format
        $data = "";
        foreach( $post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
        $data = rtrim($data, '& ');
    }

    // clean up the url
    $api_url = rtrim($api_url, '/ ');

    // define a final API request - GET
    $api_url = $api_url . '/admin/api.php?' . $query;

    $request = curl_init($api_url); // initiate curl object
    curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
    if (!is_null($post)) {
        curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
    }

    //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

    wwfsg_info_log("Sending curl request to $api_url", ['file'=>__FILE__,'function'=>__FUNCTION__,'line'=>__LINE__]);
    $response = (string)curl_exec($request); // execute curl post and store results in $response
    curl_close($request); // close curl object

    if ( !$response ) {
        wwfsg_info_log('Nothing was returned from Active Campaign API. Do you have a connection to Email Marketing server?', ['file'=>__FILE__,'function'=>__FUNCTION__,'line'=>__LINE__]);
        // die('Nothing was returned. Do you have a connection to Email Marketing server?');
        // @todo send alert email to webmaster
    }

    return unserialize($response);
}
