<?php

/**
 * Register Signature Post Type
 * 
 * @return null
 */
function Signature_Post_type()
{
    $labels = array(
        'name'                  => _x('Open letters', 'Post Type General Name', '_s'),
        'singular_name'         => _x('Open letter', 'Post Type Singular Name', '_s'),
        'menu_name'             => __('Open letters', '_s'),
        'name_admin_bar'        => __('Open letter', '_s'),
        'archives'              => __('Open letter Archives', '_s'),
        'attributes'            => __('Open letter Attributes', '_s'),
        'parent_item_colon'     => __('Parent Item:', '_s'),
        'all_items'             => __('All Items', '_s'),
        'add_new_item'          => __('Add New Item', '_s'),
        'add_new'               => __('Add New', '_s'),
        'new_item'              => __('New Item', '_s'),
        'edit_item'             => __('Edit Item', '_s'),
        'update_item'           => __('Update Item', '_s'),
        'view_item'             => __('View Item', '_s'),
        'view_items'            => __('View Items', '_s'),
        'search_items'          => __('Search Item', '_s'),
        'not_found'             => __('Not found', '_s'),
        'not_found_in_trash'    => __('Not found in Trash', '_s'),
        'featured_image'        => __('Featured Image', '_s'),
        'set_featured_image'    => __('Set featured image', '_s'),
        'remove_featured_image' => __('Remove featured image', '_s'),
        'use_featured_image'    => __('Use as featured image', '_s'),
        'insert_into_item'      => __('Insert into item', '_s'),
        'uploaded_to_this_item' => __('Uploaded to this item', '_s'),
        'items_list'            => __('Items list', '_s'),
        'items_list_navigation' => __('Items list navigation', '_s'),
        'filter_items_list'     => __('Filter items list', '_s'),
    );

    $rewrite = array(
        'slug'                  => 'open-letter',
        'with_front'            => true,
        'pages'                 => false,
        'feeds'                 => false,
    );
    $args = array(
        'label'                 => __('Open letter', '_s'),
        'description'           => __('Post Type Description', '_s'),
        'labels'                => $labels,
        'supports'              => array('title', 'thumbnail'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
        'rewrite' => $rewrite
    );
    register_post_type('signature', $args);
}
add_action('init', 'Signature_Post_type', 0);


/**
 * AJAX function returning image id
 * 
 * @return json
 */
function getSignature()
{
    global $validationerror;
    $validationerror = [];
    global $returnvariable;

    $nonce = $_POST['data']['_nonce'];
    if (!wp_verify_nonce($nonce, 'voice_form')) {
        echo json_encode([
            'errors' => 'Invalid nonce',
        ]);
        die();
    }

    check_ajax_referer('voice_form', '_nonce');

    if ($_POST['data']['form']['name']) {
        echo json_encode([
            'errors' => 'Invalid form input',
        ]);
        die();
    }

    if (empty($validationerror)) {

        // START - Save on ActiveCampaign
        $addedPost = array(
            'first_name' => $_POST['data']['form']['first_name'],
            'last_name'  => $_POST['data']['form']['last_name'],
            'email'      => $_POST['data']['form']['email'],
            'phone'      => $_POST['data']['form']['phone'],
            'age'        => $_POST['data']['form']['age'],
            'citizen'    => $_POST['data']['form']['citizen'],
            'postalcode' => $_POST['data']['form']['postalcode'],
            'country'    => $_POST['data']['form']['country'],
            'health'     => $_POST['data']['form']['issues']['health'],
            'health_1'   => $_POST['data']['form']['issues']['health_1'],
            'health_2'   => $_POST['data']['form']['issues']['health_2'],
            'future'     => $_POST['data']['form']['issues']['future'],
            'future_1'   => $_POST['data']['form']['issues']['future_1'],
            'future_2'   => $_POST['data']['form']['issues']['future_2'],
            'qualityOfLiving'   => $_POST['data']['form']['issues']['qualityOfLiving'],
            'qualityOfLiving_1' => $_POST['data']['form']['issues']['qualityOfLiving_1'],
            'qualityOfLiving_2' => $_POST['data']['form']['issues']['qualityOfLiving_2'],
            'custom_issue' => $_POST['data']['form']['issues']['custom_issue'],
            'check_pdpc' => $_POST['data']['form']['check_pdpc'],
            'locale' => $_POST['data']['form']['locale'],
            'share_url' => $_POST['data']['shareUrl'],
            'image_url' => $_POST['data']['shareImage'],
            'utm_campaign' => $_POST['data']['form']['utm_campaign'],
            'utm_source' => $_POST['data']['form']['utm_source'],
            'utm_medium' => $_POST['data']['form']['utm_medium'],
            'utm_content' => $_POST['data']['form']['utm_content'],
            'utm_term' => $_POST['data']['form']['utm_term'],
        );

        // @todo make the feelings work
        $addedPost['feelings'] = $_POST['data']['form']['feelings'];

        $signature_count = _getSignatureCount($addedPost['country']);

        $signature = array(
            'post_title'    => $addedPost['first_name'],
            'post_status'   => 'publish',
            'post_type' => 'signature',
            'post_name' => md5($_POST['data']['form']['first_name'] . $_POST['data']['form']['country'] . '-' . time())
        );

        $s_id = wp_insert_post($signature);
        $addedPost['s_id'] = $s_id;

        update_field('first_name', $addedPost['first_name'], $s_id);
        update_field('last_name', $addedPost['last_name'], $s_id);
        update_field('email', $addedPost['email'], $s_id);
        update_field('phone', $addedPost['phone'], $s_id);
        update_field('age', $addedPost['age'], $s_id);
        update_field('citizen', $addedPost['citizen'], $s_id);
        update_field('postalcode', $addedPost['postalcode'], $s_id);
        update_field('country', $addedPost['country'], $s_id);
        update_field('feelings', $addedPost['feelings'], $s_id);
        update_field('health', $addedPost['health'], $s_id);
        update_field('health_1', $addedPost['health_1'], $s_id);
        update_field('health_2', $addedPost['health_2'], $s_id);
        update_field('future', $addedPost['future'], $s_id);
        update_field('future_1', $addedPost['future_1'], $s_id);
        update_field('future_2', $addedPost['future_2'], $s_id);
        update_field('qualityOfLiving', $addedPost['qualityOfLiving'], $s_id);
        update_field('qualityOfLiving_1', $addedPost['qualityOfLiving_1'], $s_id);
        update_field('qualityOfLiving_2', $addedPost['qualityOfLiving_2'], $s_id);
        update_field('custom_issue', $addedPost['custom_issue'], $s_id);
        update_field('check_pdpc', $addedPost['check_pdpc'], $s_id);
        update_field('locale', $addedPost['locale'], $s_id);
        update_field('utm_campaign', $addedPost['utm_campaign'], $s_id);
        update_field('utm_source', $addedPost['utm_source'], $s_id);
        update_field('utm_medium', $addedPost['utm_medium'], $s_id);
        update_field('utm_content', $addedPost['utm_content'], $s_id);
        update_field('utm_term', $addedPost['utm_term'], $s_id);

        $path = 'https://www.earthhour.sg/wp-content/uploads/2020/03/openletter.png';

        // $path = generate_image(
        //     $custom_text = $addedPost['custom_issue'],
        //     $user_name = $addedPost['first_name'] . ' ' . $addedPost['last_name'],
        //     $feelings = $addedPost['feelings'],
        //     $health_1 = $addedPost['health_1'],
        //     $health_2 = $addedPost['health_2'],
        //     $future_1 = $addedPost['future_1'],
        //     $future_2 = $addedPost['future_2'],
        //     $qualityOfLiving_1 = $addedPost['qualityOfLiving_1'],
        //     $qualityOfLiving_2 = $addedPost['qualityOfLiving_2']
        // );

        $imgUrl = $addedPost['image_url'] = $path;

        // $addedPost['image_url'] = Generate_Featured_Image($file = $path, $post_id = $s_id, $desc = '');
        update_field('image_url', $addedPost['image_url'], $s_id);
        // $imgUrl = wp_get_attachment_url($addedPost['image_url']);

        if (isset($_POST['data']['form']['email']) && $_POST['data']['form']['email']) {
            addActiveCampaign($addedPost);
            sendEmail_sg($addedPost);
        }

        if ($s_id <= 0) {
            $returnvariable['redirect'] = "We are sorry. Something went wrong with you signature. Please reload and try again.";
            error_log($returnvariable['redirect']);

            echo json_encode([
                'errors' => $returnvariable['redirect'],
            ]);
            die();
        }
    } else {

        $returnvariable['errors'] = "<ul class='errordiv'>";

        foreach ($validationerror as $error) {
            $returnvariable['errors'] .= "<li>";
            $returnvariable['errors'] .= $error;
            $returnvariable['errors'] .= "</li>";
        }

        $returnvariable['errors'] .= "</ul>";
    }

    echo json_encode([
        's_id' => $s_id,
        'image_url' => $imgUrl,
        'share_url' => get_permalink($s_id),
        'signatureCount' => $signature_count
    ]);
    die();
}
add_action('wp_ajax_getSignature', 'getSignature');
add_action('wp_ajax_nopriv_getSignature', 'getSignature');


function _getSignatureCount($country = 'all')
{
    global $post, $wpdb;

    $count = [];
    $country = strtolower($country);

    $count['total'] = wp_count_posts('signature')->publish;

    // @todo use default post query with meta
    // $sql = "SELECT count(meta_id) AS 'count' FROM wp_postmeta WHERE `meta_key` = 'email' AND `meta_value` = ''";
    // $results = $wpdb->get_var($sql);

    // $count['plastic_test']['global'] = $results;
    // $count['global'] = ($count['total'] - $count['plastic_test']['global']) + (1588248);
    // $count['global'] = number_format($count['global']);

    // $sql = "SELECT meta_value, count(meta_id) AS 'count' FROM wp_postmeta WHERE `meta_key` = 'country' GROUP BY `meta_value`";
    // $results = $wpdb->get_results($sql);
    // foreach ($results as $value) {
    //     $c_id = strtolower($value->meta_value);
    //     $count[$c_id] = $value->count;
    // }
    // if ($country != 'all') {
    //     if (isset($count[$country]) && $count[$country]) {
    //         $count['local'] =  $count[$country];
    //     } else {
    //         $count['local'] = 0;
    //     }
    // } else {
    // $count['local'] = 0;
    // }

    // $count['local'] = 0;

    return $count;
}


/**
 * 
 */
function getSignatureCount()
{

    if (isset($_REQUEST['country'])) {
        $country = $_REQUEST['country'];
    } else {
        $country = 'all';
    }


    echo json_encode(_getSignatureCount($country));
    die();
}
add_action('wp_ajax_getSignatureCount', 'getSignatureCount');
add_action('wp_ajax_nopriv_getSignatureCount', 'getSignatureCount');


function addActiveCampaign($signature)
{
    // By default, this sample code is designed to get the result from your ActiveCampaign installation and print out the result
    // $url = 'https://wwfsingapore297.api-us1.com';
    // $api_key = 'd0f002f5c5ba903b3d74478a2952ca91acf04c5d72b4c75702a8051fc30296d62c3aec29';
    // $list_id = '90';
    $url = 'https://wwf-worldwidefundfornaturesingaporelimited1552298160.api-us1.com';
    $api_key = '15921cac81a99f6986315e1921a0882febb222405c7313e41a523ef16d289327ff2ab62d';
    $list_id = '1';
    $tags = array_merge(['OL', 'OnlineLead'], [$signature['utm_campaign'], $signature['utm_source'], $signature['utm_medium'], $signature['utm_content'], $signature['utm_term'],]);
    if ($signature['health_1']) array_push($tags, 'OL_health', 'OL_haze');
    if ($signature['health_2']) array_push($tags, 'OL_health', 'OL_plastic');
    if ($signature['future_1']) array_push($tags, 'OL_quality', 'OL_wildlife');
    if ($signature['future_2']) array_push($tags, 'OL_quality', 'OL_food');
    if ($signature['qualityOfLiving_1']) array_push($tags, 'OL_prosperity', 'OL_climate');
    if ($signature['qualityOfLiving_2']) array_push($tags, 'OL_prosperity', 'OL_future');
    $tags = implode(', ', array_filter($tags));

    $params = array(

        // the API Key can be found on the "Your Settings" page under the "API" tab.
        // replace this with your API Key
        'api_key'      => $api_key,

        // this is the action that adds a contact
        'api_action'   => 'contact_sync',

        // define the type of output you wish to get back
        // possible values:
        // - 'xml'  :      you have to write your own XML parser
        // - 'json' :      data is returned in JSON format and can be decoded with
        //                 json_decode() function (included in PHP since 5.2.0)
        // - 'serialize' : data is returned in a serialized format and can be decoded with
        //                 a native unserialize() function
        'api_output'   => 'serialize',
    );

    // here we define the data we are posting in order to perform an update
    $post = array(
        'email'                    => $signature['email'],
        'first_name'               => $signature['first_name'],
        'last_name'               => $signature['last_name'],
        //'ip4'                    => '127.0.0.1',
        'phone'                    => $signature['phone'],
        // 'orgname'                  => 'Acme, Inc.',
        'tags'                     => $tags,

        // any custom fields
        //'field[345,0]'           => 'field value', // where 345 is the field ID
        //'field[%PERS_1%,0]'      => 'field value', // using the personalization tag instead
        // "field[%PLASTIC_DIET_PLASTIC_VALUE%, 0]" => $signature['plastic_value'],
        // "field[%PLASTIC_DIET_PLASTIC_NAME%, 0]" => $signature['plastic_name'],

        // assign to lists:
        'p[$list_id]'              => $list_id, // example list ID (REPLACE '123' WITH ACTUAL LIST ID, IE: p[5] = 5)
        'status[$list_id]'         => 1, // 1: active, 2: unsubscribed (REPLACE '123' WITH ACTUAL LIST ID, IE: status[5] = 1)
        //'form'          => 1001, // Subscription Form ID, to inherit those redirection settings
        //'noresponders[123]'      => 1, // uncomment to set "do not send any future responders"
        //'sdate[123]'             => '2009-12-07 06:00:00', // Subscribe date for particular list - leave out to use current date/time
        // use the folowing only if status=1
        'instantresponders[$list_id]' => 1, // set to 0 to if you don't want to sent instant autoresponders
        //'lastmessage[123]'       => 1, // uncomment to set "send the last broadcast campaign"

        //'p[]'                    => 345, // some additional lists?
        //'status[345]'            => 1, // some additional lists?
    );

    // This section takes the input fields and converts them to the proper format
    $query = "";
    foreach ($params as $key => $value) $query .= urlencode($key) . '=' . urlencode($value) . '&';
    $query = rtrim($query, '& ');

    // This section takes the input data and converts it to the proper format
    $data = "";
    foreach ($post as $key => $value) $data .= urlencode($key) . '=' . urlencode($value) . '&';
    $data = rtrim($data, '& ');

    // clean up the url
    $url = rtrim($url, '/ ');

    // This sample code uses the CURL library for php to establish a connection,
    // submit your request, and show (print out) the response.
    if (!function_exists('curl_init')) die('CURL not supported. (introduced in PHP 4.0.2)');

    // If JSON is used, check if json_decode is present (PHP 5.2.0+)
    if ($params['api_output'] == 'json' && !function_exists('json_decode')) {
        die('JSON not supported. (introduced in PHP 5.2.0)');
    }

    // define a final API request - GET
    $api = $url . '/admin/api.php?' . $query;

    $request = curl_init($api); // initiate curl object
    curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
    curl_setopt($request, CURLOPT_POSTFIELDS, $data); // use HTTP POST to send form data
    //curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment if you get no gateway response and are using HTTPS
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

    $response = (string) curl_exec($request); // execute curl post and store results in $response

    // additional options may be required depending upon your server configuration
    // you can find documentation on curl options at http://www.php.net/curl_setopt
    curl_close($request); // close curl object

    if (!$response) {
        die('Nothing was returned. Do you have a connection to Email Marketing server?');
    }

    // This line takes the response and breaks it into an array using:
    // JSON decoder
    //$result = json_decode($response);
    // unserializer
    $result = unserialize($response);
    // XML parser...
    // ...

    // Result info that is always returned
    // echo 'Result: ' . ($result['result_code'] ? 'SUCCESS' : 'FAILED') . '<br />';
    // echo 'Message: ' . $result['result_message'] . '<br />';

    if ($result['result_code']) {
        update_field('on_active_campaign', $result['subscriber_id'], $signature['s_id']);
    } else {
        error_log($signature['s_id']);
        error_log($result['result_message']);
    }
}


function sendEmail_sg($user)
{
    global $localDrivePath;

    if (empty($user)) {
        error_log('Customer ID was not provided');
    }

    $custEmail = $user['email'];
    $custFname = $user['first_name'];
    $plastic = $user['plastic_name'];
    $content = '';

    if (empty($custEmail)) {
        error_log('#' . $user . ' customer donesn\'t have an email address.');
    }

    $headers = array(
        // "From: Janissa and Michael, WWF-Singapore <no-reply@earthhour.sg/>",
        "Content-type: text/html; charset=UTF-8",
        "Reply-To: Janissa and Michael, WWF-Singapore <commsteam@wwf.sg>"
    );
    $subject = 'Thank you for helping to write your future';
    $template = file_get_contents(get_template_directory() . '/functions/template/email_template.html');

    $replace = array();
    $replace['content'] = $content;
    $replace['user_name'] = $custFname;
    $replace['plastic_name'] = $plastic;

    foreach ($replace as $key => $value) {
        $template = str_replace('{{ ' . $key . ' }}', $value, $template);
    }

    // if (isset($EMAIL_RECIPIENT_OVERRIDE)) {
    // $custEmail = $EMAIL_RECIPIENT_OVERRIDE;
    // }

    $emailSuccess = wp_mail($custEmail, $subject, $template, $headers);

    return $emailSuccess;
}


function ac_sync_contact()
{
    // query all the signatures with on_active_campaign field
    $loop = new WP_Query(array(
        'post_type' => 'signature',
        'post_status' => 'publish',
        'posts_per_page' => 400,
        'order' => 'DESC',
        'meta_query' => array(
            'relation'    => 'AND',
            array(
                'key'     => 'country',
                'value'   => 'SG',
            ),
            array(
                'key' => 'on_active_campaign',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => 'email',
                'compare' => 'EXISTS'
            ),
            array(
                'key'     => 'email',
                'value'   => '',
                'compare' => '!='
            ),
        )
    ));


    // echo $loop->request;
    // echo "<br>";
    // echo "<br>";
    // echo "<br>";


    if ($loop->have_posts()) :
        while ($loop->have_posts()) :
            $loop->the_post();
            $s_id = get_the_ID();


            $signature['s_id'] = $s_id;
            $signature['name'] = get_field('name', $s_id);
            $signature['email'] = get_field('email', $s_id);
            $signature['phone'] = get_field('phone', $s_id);
            $signature['country'] = get_field('country', $s_id);
            $signature['plastic_value'] = get_field('plastic_value', $s_id);
            $signature['plastic_name'] = get_field('plastic_name', $s_id);
            $signature['plastic_image'] = get_field('plastic_image', $s_id);
            $signature['utm_campaign'] = get_field('utm_campaign', $s_id);
            $signature['utm_source'] = get_field('utm_source', $s_id);
            $signature['utm_medium'] = get_field('utm_medium', $s_id);
            $signature['utm_content'] = get_field('utm_content', $s_id);
            $signature['utm_term'] = get_field('utm_term', $s_id);

            if ('SG' == $signature['country']) {
                addActiveCampaign($signature);
            }

            wp_reset_postdata();
        endwhile;
    endif;
}

function cron_cron_ac_sync_contact_15af8da1()
{
    ac_sync_contact();
}
add_action('cron_ac_sync_contact', 'cron_cron_ac_sync_contact_15af8da1', 10, 0);


if (is_admin() && isset($_GET['test'])) {
    ac_sync_contact();
    die();
}
