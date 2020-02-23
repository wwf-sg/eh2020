<?php
require_once('vendor/autoload.php');

use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

//=============================
// START IMAGE GENERATION
//=============================


function generate_image_do($text, $user_name, $plastic_value = '00.01', $plastic_name = 'button', $image = null)
{
    $url_base = 'https://yourplasticdiet.org/wp-content/themes/your-plastic-diet/functions/generate-image/';
    $generated_image_url = get_stylesheet_directory() . '/functions/generate-image/generated-images/';
    $generated_image_name = md5(strtolower($user_name) . '-' . $image . '-' . time()) . '.png';
    $generated_image_full = $generated_image_url . $generated_image_name;

    $url = $url_base . 'image-generator.php?text=' . $text . '&user_name=' . $user_name . '&plastic_value=' . $plastic_value . '&plastic_name=' . $plastic_name . '&image=' . $image;

    $curl_url = "http://157.230.44.162/?url=" . urlencode($url) . "&name=" . $generated_image_name;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $curl_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    
    $return = json_decode($data);

    error_log($return->url);
    // return $return->url;
}

function generate_image($text, $user_name, $plastic_value = '00.01', $plastic_name = 'button', $image = null)
{

    ////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////


    $puppeteer = new Puppeteer([
        // 'executable_path' => '/Users/mlhalugona/.nvm/versions/node/v8.15.0/bin/node',
        'executable_path' => '/usr/bin/node'
    ]);
    // to fix windows 10 issue, it needs args
    $browser = $puppeteer->launch([
        'args' => ['--no-sandbox', '--disable-setuid-sandbox'],
    ]);

    $url_base = 'https://yourplasticdiet.org/wp-content/themes/your-plastic-diet/functions/generate-image/';
    $generated_image_url = get_stylesheet_directory() . '/functions/generate-image/generated-images/';
    $generated_image_name = md5(strtolower($user_name) . '-' . $image . '-' . time()) . '.png';
    $generated_image_full = $generated_image_url . $generated_image_name;

    $page = $browser->newPage();
    $url = $url_base . 'image-generator.php?text=' . $text . '&user_name=' . $user_name . '&plastic_value=' . $plastic_value . '&plastic_name=' . $plastic_name . '&image=' . $image;
    $page->goto($url);

    $page->screenshot(
        [
            'path' => $generated_image_full,
            'clip' => [
                'x' => 0,
                'y' => 0,
                'width' => 1200,
                'height' => 600
            ]
        ]
    );
    $browser->close();

    // return $generated_image_full;
    return $url_base . 'generated-images/' . $generated_image_name;
}

// $path = generate_image("{{ user_name }} consumes approximately {{ plastic_value }} of plastics a week.", "Fransiska Amelia", 0.74, "button");

// echo $path;


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

    $country_info = (object)[
        'country' => $json_details->geoplugin_countryName,
        'countryCode' => $json_details->geoplugin_countryCode,
    ];

    return $country_info;
}

// print_r(get_user_country_info());

    //=============================
    // END DETECT USER COUNTRY
    //=============================
