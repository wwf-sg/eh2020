<?php
require_once('vendor/autoload.php');

use Nesk\Puphpeteer\Puppeteer;

//=============================
// START IMAGE GENERATION
//=============================

function generate_image(
    $custom_text = '',
    $user_name = 'a panda',
    $feelings = [],
    $health_1 = false,
    $health_2 = false,
    $future_1 = false,
    $future_2 = false,
    $qualityOfLiving_1 = false,
    $qualityOfLiving_2 = false
) {
    $url_base = get_stylesheet_directory_uri() . '/functions/generate-image/';
    $generated_image_url = get_stylesheet_directory() . '/functions/generate-image/generated-images/';
    $generated_image_name = md5(strtolower($user_name) . '-' . time()) . '.png';
    $generated_image_full = $generated_image_url . $generated_image_name;

    $config = ['executable_path' => '/usr/bin/node'];
    if (!defined('PROD'))
        $config = ['executable_path' => '/Users/manojhl/.nvm/versions/node/v12.14.1/bin/node'];

    $puppeteer = new Puppeteer($config);
    // to fix windows 10 issue, it needs args
    $browser = $puppeteer->launch([
        'args' => ['--no-sandbox', '--disable-setuid-sandbox'],
    ]);
    $page = $browser->newPage();

    // $page = (new Puppeteer($config))->launch([
    //     'args' => ['--no-sandbox', '--disable-setuid-sandbox'],
    // ])->newPage();
    // The promise is returned instead of being awaited, due to the "lazy" modifier.
    // $navigationPromise = $page->waitForNavigation();

    $url = 'https://www.earthhour.sg/wp-content/themes/eh2020/functions/generate-image/image-generator.php?custom_text=' . $custom_text .
        '&user_name=' . $user_name .
        '&feelings=' . $feelings .
        '&health_1=' . $health_1 .
        '&health_2=' . $health_2 .
        '&future_1=' . $future_1 .
        '&future_2=' . $future_2 .
        '&qualityOfLiving_1=' . $qualityOfLiving_1 .
        '&qualityOfLiving_2=' . $qualityOfLiving_2;
    $page->goto($url);

    $page->screenshot(
        [
            'path' => $generated_image_full,
            'type' => 'jpeg',
            // 'clip' => [
            //     'x' => 0,
            //     'y' => 0,
            //     'width' => 1200,
            //     'height' => 600
            // ],
            'fullPage' => true,
            // 'encoding' => 'base64'
        ]
    );
    // var_dump($page);

    $browser->close();

    // return $generated_image_full;
    return $url_base . 'generated-images/' . $generated_image_name;
}

// echo "<pre>";
// $path = generate_image("Hello", "Fransiska Amelia");
// echo $path;
// var_dump($path);
// echo "</pre>";


//=============================
// END IMAGE GENERATION
//=============================


//=============================
// START DETECT USER COUNTRY
//=============================
function get_client_ip()
{
    $ip_address = '';
    if (getenv('HTTP_CLIENT_IP')) $ip_address = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR')) $ip_address = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED')) $ip_address = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR')) $ip_address = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED')) $ip_address = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR')) $ip_address = getenv('REMOTE_ADDR');
    else $ip_address = 'UNKNOWN';

    return $ip_address;
}

function ip_details($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function get_user_country_info()
{
    $user_ip_address = get_client_ip();
    $url = 'http://www.geoplugin.net/json.gp?ip=' . $user_ip_address;
    $details = ip_details($url);
    $json_details = json_decode($details);
    $country_info = new stdClass();

    if (empty($json_details->geoplugin_countryName))
        error_log($details);

    $country_info = (object) [
        'country' => $json_details->geoplugin_countryName,
        'countryCode' => $json_details->geoplugin_countryCode,
    ];

    return $country_info;
}

// print_r(get_user_country_info());

    //=============================
    // END DETECT USER COUNTRY
    //=============================
