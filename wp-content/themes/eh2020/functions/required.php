<?php
/* ==========================================================================
    Required
  ========================================================================== */

function ypd_custom_logo_setup()
{
    $defaults = array(
        'height'      => 300,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
    );
    add_theme_support('custom-logo', $defaults);

    // allow the title tag in theme
    add_theme_support('title-tag');

    add_theme_support('align-wide');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_theme_support('dark-editor-style');
    add_editor_style('custom-editor-style.css');
    add_theme_support('wp-block-styles');
    add_theme_support('responsive-embeds');

    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('Small', '_s'),
            'size' => 12,
            'slug' => 'small'
        ),
        array(
            'name' => __('Normal', '_s'),
            'size' => 16,
            'slug' => 'normal'
        ),
        array(
            'name' => __('Large', '_s'),
            'size' => 36,
            'slug' => 'large'
        ),
        array(
            'name' => __('Huge', '_s'),
            'size' => 50,
            'slug' => 'huge'
        )
    ));

    add_theme_support('editor-color-palette', array(
        array(
            'name' => __('red', '_s'),
            'slug' => 'red',
            'color' => '#f32b2c',
        ),
        array(
            'name' => __('blue', '_s'),
            'slug' => 'blue',
            'color' => '#228aff',
        ),
        array(
            'name' => __('very light gray', '_s'),
            'slug' => 'very-light-gray',
            'color' => '#eee',
        ),
        array(
            'name' => __('very dark gray', '_s'),
            'slug' => 'very-dark-gray',
            'color' => '#444',
        ),
    ));

    register_nav_menu('plastic-diet-menu', __('Plastic diet Menu'));
    register_nav_menu('country-menu', __('Country Menu'));
}
add_action('after_setup_theme', 'ypd_custom_logo_setup');


// queue required assets
function queue_theme_assets()
{
    $min = '';
    if (defined('PROD') && PROD && !isset($_GET['test'])) {
        $min = '.min';
    }

    wp_enqueue_style('style-bundle', get_template_directory_uri() . '/dist/app' . $min . '.css', array(), '1.9.9');
    wp_enqueue_script('manifest-bundle', get_template_directory_uri() . '/dist/manifest' . $min . '.js', array(), '1.9.9', true);
    wp_enqueue_script('vendor-bundle', get_template_directory_uri() . '/dist/vendor' . $min . '.js', array(), '1.3', true);
    wp_enqueue_script('script-bundle', get_template_directory_uri() . '/dist/app' . $min . '.js', array(), '1.9.10', true);
};
add_action('wp_enqueue_scripts', 'queue_theme_assets');

// Register Custom Navigation Walker for function.php
require_once('wp_select_navwalker.php');

function ypd_wwf_block_category($categories, $post)
{
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'wwf',
                'title' => __('WWF', '_s'),
            ),
        )
    );
}
add_filter('block_categories', 'ypd_wwf_block_category', 10, 2);

function register_acf_block_types()
{
    // register a testimonial block.
    acf_register_block_type(array(
        'name'              => 'plastic-diet-test',
        'title'             => __('Plastic Diet Test'),
        'description'       => __('Add plastic diet test block.'),
        'render_template'   => 'template-parts/blocks/plastic-diet-test/plastic-diet-test.php',
        'category'          => 'wwf',
        'icon'              => 'admin-comments',
        'keywords'          => array('plastic', 'test', 'diet'),
    ));
}

// Check if function exists and hook into setup.
if (function_exists('acf_register_block_type')) {
    add_action('acf/init', 'register_acf_block_types');
}


// Add field key of the repeater
add_filter('acf/load_value/key=field_5ce78e460139c',  'afc_load_my_repeater_value', 10, 3);
function afc_load_my_repeater_value($value, $post_id, $field)
{

    //Optional: Check for post_status otherwise published values will be changed.
    if (get_post_status($post_id) === 'auto-draft') {

        //Optional: Check for post_type.
        // if( get_post_type( $post_id ) == 'cpt_type_1' ){
        $value  = array();

        // Add field key for the field you would to put a default value (text field in this case)
        $value[] = array(
            'field_5ce78e520139d' => "WHAT’S THIS ABOUT?",
            'field_5ce78e8b0139e' => "#whats-this-about"
        );
        $value[] = array(
            'field_5ce78e520139d' => "TAKE THE PLASTIC TEST",
            'field_5ce78e8b0139e' => "#take-the-plastic-test"
        );
        $value[] = array(
            'field_5ce78e520139d' => "WHAT CAN I DO?",
            'field_5ce78e8b0139e' => "#what-can-i-do"
        );
        $value[] = array(
            'field_5ce78e520139d' => "DISCOVER MORE",
            'field_5ce78e8b0139e' => "#faq"
        );
        // }
    }

    return $value;
}


// add_filter('parse_query', 'set_custom_isvars');
function set_custom_isvars($query)
{
    if (is_admin() && !isset($_GET['test']) and $query->query['post_type'] == 'signature') {
        $qv = &$query->query_vars;
        $qv['meta_query'] = array();

        $user = wp_get_current_user();
        $roles = (array) $user->roles;

        $add = array(
            'relation' => 'OR',
        );

        foreach ($roles as $role) {
            if ($role == 'all') {
                array_push(
                    $add,
                    array(
                        'key'     => 'country',
                        'compare' => 'EXISTS',
                    )
                );
            } elseif ($role == 'int') {
                array_push(
                    $add,
                    array(
                        'key'     => 'country',
                        'value'   => ['af', 'al', 'dz', 'ad', 'ao', 'ai', 'ag', 'am', 'aw', 'at', 'az', 'bs', 'bh', 'bd', 'bb', 'by', 'be', 'bj', 'bm', 'bt', 'bo', 'bq', 'bw', 'br', 'bn', 'bg', 'bf', 'bi', 'kh', 'cm', 'ca', 'cv', 'ky', 'cf', 'td', 'cn', 'cx', 'km', 'cd', 'ck', 'cr', 'ci', 'cu', 'cw', 'cy', 'cz', 'dj', 'dm', 'do', 'tl', 'eg', 'sv', 'er', 'ee', 'et', 'fo', 'fj', 'gf', 'pf', 'ga', 'gm', 'ge', 'gh', 'gi', 'gr', 'gl', 'gd', 'gp', 'gu', 'gg', 'gn', 'gw', 'gy', 'ht', 'hu', 'is', 'in', 'id', 'ir', 'iq', 'iq', 'ie', 'il', 'it', 'jm', 'je', 'jo', 'kz', 'ke', 'ki', 'xk', 'kw', 'kg', 'la', 'lv', 'lb', 'ls', 'lr', 'ly', 'li', 'lt', 'lu', 'mo', 'mk', 'mg', 'mw', 'mv', 'ml', 'mt', 'mh', 'mq', 'mr', 'mu', 'fm', 'md', 'mc', 'mn', 'me', 'ms', 'ma', 'mz', 'mm', 'na', 'nr', 'np', 'nl', 'nc', 'ni', 'ne', 'ng', 'nu', 'no', 'om', 'ps', 'pg', 'py', 'pe', 'pl', 'pr', 'qa', 'ro', 'ru', 'rw', 'bl', 'sh', 'kn', 'vc', 'ws', 'sa', 'sn', 'sc', 'sl', 'sx', 'sk', 'sb', 'so', 'za', 'lk', 'lc', 'sd', 'sr', 'sz', 'ch', 'sy', 'tw', 'tj', 'tz', 'th', 'tg', 'to', 'tt', 'tn', 'tm', 'tc', 'tv', 'ae', 'gb', 'gb', 'gb', 'gb', 'us', 'ug', 'ua', 'uy', 'uz', 'vu', 'va', 've', 'vn', 'wf', 'ye', 'zm', 'zw'],
                        'compare' => 'IN',
                    )
                );
            } elseif ($role == 'es') {
                array_push(
                    $add,
                    array(
                        'key'     => 'country',
                        'value'   => ['ar', 'bo', 'br', 'cl', 'co', 'cr', 'cu', 'do', 'ec', 'sv', 'gf', 'gp', 'gt', 'ht', 'hn', 'mq', 'mx', 'ni', 'pa', 'py', 'pe', 'pr', 'mf', 'uy', 've'],
                        'compare' => 'IN',
                    )
                );
            } else {
                array_push(
                    $add,
                    array(
                        'key'     => 'country',
                        'value'   => strtolower($role),
                        'compare' => '=',
                    )
                );
            }
        }
        $qv['meta_query'][] = $add;

        // if (!empty($_GET['orderby']) and $_GET['orderby'] == 'event_date') {
        //     $qv['orderby'] = 'meta_value';
        //     $qv['meta_key'] = '_bs_meta_event_date';
        //     $qv['order'] = strtoupper($_GET['order']);
        // }
    }
}

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts()
{
    echo '<style>
    .actions.ac-search {
        display: none;
    }
    .ac-hide-export-button .ac-table-actions .ac-table-actions-buttons .ac-table-button.-export {
        display: block;
    }
  </style>';
}


// define the nav_menu_item_title callback 
function filter_nav_menu_item_title($title, $item, $args, $depth)
{
    $icon = strtolower(get_field('country_flag', $item));
    $out = "<span class='flag-icon flag-icon-" . $icon . " mr-2'></span><span>" . $title . "</span>";
    return $out;
};

// add the filter 
add_filter('nav_menu_item_title', 'filter_nav_menu_item_title', 10, 4);


// define the nav_menu_link_attributes callback 
function filter_nav_menu_link_attributes($atts, $item, $args, $depth)
{

    if (strpos($atts['href'], '?') !== false) {
        $atts['href'] = $atts['href'] . '&no_redirect';
    } else {
        $atts['href'] = $atts['href'] . '?no_redirect';
    }
    return $atts;
};

// add the filter 
add_filter('nav_menu_link_attributes', 'filter_nav_menu_link_attributes', 10, 4);




// META BOX
/**
 * Generated by the WordPress Meta Box generator
 * at http://jeremyhixon.com/tool/wordpress-meta-box-generator/
 */

function iframe_code_get_meta($value)
{
    global $post;

    return "<iframe scrolling='no' src='" . get_permalink($post) . "' style='width: 100%; border: 0;'></iframe><script src='" . get_template_directory_uri() . "/dist/iframeResizer.min.js'></script><script>iFrameResize({log:true})</script>";

    $field = get_post_meta($post->ID, $value, true);

    if (!empty($field)) {
        return is_array($field) ? stripslashes_deep($field) : stripslashes(wp_kses_decode_entities($field));
    } else {
        return "<script src='' data-url='" . get_permalink($post) . "'></script>";
    }
}

function iframe_code_add_meta_box()
{
    global $post;

    if ('page-templates/plastic-diet-ifram.php' == get_post_meta($post->ID, '_wp_page_template', true)) {
        add_meta_box(
            'iframe_code-iframe-code',
            __('iFrame code', 'iframe_code'),
            'iframe_code_html',
            'page',
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'iframe_code_add_meta_box');

function iframe_code_html($post)
{
    wp_nonce_field('_iframe_code_nonce', 'iframe_code_nonce'); ?>

    <p>
        <textarea rows='4' style="width: 100%;" readonly name="iframe_code_code" id="iframe_code_code"><?php echo iframe_code_get_meta('iframe_code_code'); ?></textarea>
    </p>
<?php
}

function iframe_code_save($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['iframe_code_nonce']) || !wp_verify_nonce($_POST['iframe_code_nonce'], '_iframe_code_nonce')) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['iframe_code_code']))
        update_post_meta($post_id, 'iframe_code_code', esc_attr($_POST['iframe_code_code']));
}
add_action('save_post', 'iframe_code_save');

function is_backend()
{
    return (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false);
}

function is_login()
{
    return (strpos($_SERVER['REQUEST_URI'], 'wp-login') !== false);
}

function is_rest()
{
    return (strpos($_SERVER['REQUEST_URI'], '/wp-json/') !== false);
}



// if (isset($_GET['test'])) {
//     // var_dump(is_front_page());
// }




function register_stats_menu_page()
{
    add_submenu_page(
        'index.php',
        'Stats',
        'Stats',
        'manage_options',
        'ypd-stats',
        'stats_callback'
    );
}
add_action('admin_menu', 'register_stats_menu_page');


function stats_callback()
{
    /*
	SELECT 
		m1.meta_value as meta_value, count(m2.meta_value) as count
	FROM 
		wp_posts as p
	JOIN
		wp_postmeta as m1 ON m1.post_id = p.ID
	JOIN wp_postmeta as m2 ON m1.post_id = m2.post_id
	WHERE 
		p.post_type = 'signature'
	    AND (p.post_date BETWEEN '2019-06-17 00:00:00' AND '2019-06-25 00:00:00')
	    AND m1.meta_key = 'country'
	    AND m1.meta_value = 'SG'
	    AND m2.meta_key = 'phone' 
	    AND m2.meta_value != ''
	GROUP BY  m1.meta_value
	LIMIT 10
	 */
    global $post, $wpdb;
    date_default_timezone_set('Asia/Singapore');
    $stats = [];
    $count = 0;
    $countries_with_codes = [
        'sx' => 'Sint Maarten',
        'ss' => 'South Sudan',
        'kp' => 'North Korea',
        'af' => 'Afghanistan',
        'ax' => 'Aland Islands',
        'al' => 'Albania',
        'dz' => 'Algeria',
        'as' => 'American Samoa',
        'ad' => 'Andorra',
        'ao' => 'Angola',
        'ai' => 'Anguilla',
        'aq' => 'Antarctica',
        'ag' => 'Antigua And Barbuda',
        'ar' => 'Argentina',
        'am' => 'Armenia',
        'aw' => 'Aruba',
        'au' => 'Australia',
        'at' => 'Austria',
        'az' => 'Azerbaijan',
        'bs' => 'Bahamas',
        'bh' => 'Bahrain',
        'bd' => 'Bangladesh',
        'bb' => 'Barbados',
        'by' => 'Belarus',
        'be' => 'Belgium',
        'bz' => 'Belize',
        'bj' => 'Benin',
        'bm' => 'Bermuda',
        'bt' => 'Bhutan',
        'bo' => 'Bolivia',
        'ba' => 'Bosnia And Herzegovina',
        'bw' => 'Botswana',
        'bv' => 'Bouvet Island',
        'br' => 'Brazil',
        'io' => 'British Indian Ocean Territory',
        'bn' => 'Brunei Darussalam',
        'bg' => 'Bulgaria',
        'bf' => 'Burkina Faso',
        'bi' => 'Burundi',
        'kh' => 'Cambodia',
        'cm' => 'Cameroon',
        'ca' => 'Canada',
        'cv' => 'Cape Verde',
        'ky' => 'Cayman Islands',
        'cf' => 'Central African Republic',
        'td' => 'Chad',
        'cl' => 'Chile',
        'cn' => 'China',
        'cx' => 'Christmas Island',
        'cc' => 'Cocos (Keeling) Islands',
        'co' => 'Colombia',
        'km' => 'Comoros',
        'cg' => 'Congo',
        'cd' => 'Congo, Democratic Republic',
        'ck' => 'Cook Islands',
        'cr' => 'Costa Rica',
        'ci' => 'Cote D\'Ivoire',
        'hr' => 'Croatia',
        'cu' => 'Cuba',
        'cy' => 'Cyprus',
        'cz' => 'Czech Republic',
        'dk' => 'Denmark',
        'dj' => 'Djibouti',
        'dm' => 'Dominica',
        'do' => 'Dominican Republic',
        'ec' => 'Ecuador',
        'eg' => 'Egypt',
        'sv' => 'El Salvador',
        'gq' => 'Equatorial Guinea',
        'er' => 'Eritrea',
        'ee' => 'Estonia',
        'et' => 'Ethiopia',
        'fk' => 'Falkland Islands (Malvinas)',
        'fo' => 'Faroe Islands',
        'fj' => 'Fiji',
        'fi' => 'Finland',
        'fr' => 'France',
        'gf' => 'French Guiana',
        'pf' => 'French Polynesia',
        'tf' => 'French Southern Territories',
        'ga' => 'Gabon',
        'gm' => 'Gambia',
        'ge' => 'Georgia',
        'de' => 'Germany',
        'gh' => 'Ghana',
        'gi' => 'Gibraltar',
        'gr' => 'Greece',
        'gl' => 'Greenland',
        'gd' => 'Grenada',
        'gp' => 'Guadeloupe',
        'gu' => 'Guam',
        'gt' => 'Guatemala',
        'gg' => 'Guernsey',
        'gn' => 'Guinea',
        'gw' => 'Guinea-Bissau',
        'gy' => 'Guyana',
        'ht' => 'Haiti',
        'hm' => 'Heard Island & Mcdonald Islands',
        'va' => 'Holy See (Vatican City State)',
        'hn' => 'Honduras',
        'hk' => 'Hong Kong',
        'hu' => 'Hungary',
        'is' => 'Iceland',
        'in' => 'India',
        'id' => 'Indonesia',
        'ir' => 'Iran, Islamic Republic Of',
        'iq' => 'Iraq',
        'ie' => 'Ireland',
        'im' => 'Isle Of Man',
        'il' => 'Israel',
        'it' => 'Italy',
        'jm' => 'Jamaica',
        'jp' => 'Japan',
        'je' => 'Jersey',
        'jo' => 'Jordan',
        'kz' => 'Kazakhstan',
        'ke' => 'Kenya',
        'ki' => 'Kiribati',
        'kr' => 'Korea',
        'kw' => 'Kuwait',
        'kg' => 'Kyrgyzstan',
        'la' => 'Lao People\'s Democratic Republic',
        'lv' => 'Latvia',
        'lb' => 'Lebanon',
        'ls' => 'Lesotho',
        'lr' => 'Liberia',
        'ly' => 'Libyan Arab Jamahiriya',
        'li' => 'Liechtenstein',
        'lt' => 'Lithuania',
        'lu' => 'Luxembourg',
        'mo' => 'Macao',
        'mk' => 'Macedonia',
        'mg' => 'Madagascar',
        'mw' => 'Malawi',
        'my' => 'Malaysia',
        'mv' => 'Maldives',
        'ml' => 'Mali',
        'mt' => 'Malta',
        'mh' => 'Marshall Islands',
        'mq' => 'Martinique',
        'mr' => 'Mauritania',
        'mu' => 'Mauritius',
        'yt' => 'Mayotte',
        'mx' => 'Mexico',
        'fm' => 'Micronesia, Federated States Of',
        'md' => 'Moldova',
        'mc' => 'Monaco',
        'mn' => 'Mongolia',
        'me' => 'Montenegro',
        'ms' => 'Montserrat',
        'ma' => 'Morocco',
        'mz' => 'Mozambique',
        'mm' => 'Myanmar',
        'na' => 'Namibia',
        'nr' => 'Nauru',
        'np' => 'Nepal',
        'nl' => 'Netherlands',
        'an' => 'Netherlands Antilles',
        'nc' => 'New Caledonia',
        'nz' => 'New Zealand',
        'ni' => 'Nicaragua',
        'ne' => 'Niger',
        'ng' => 'Nigeria',
        'nu' => 'Niue',
        'nf' => 'Norfolk Island',
        'mp' => 'Northern Mariana Islands',
        'no' => 'Norway',
        'om' => 'Oman',
        'pk' => 'Pakistan',
        'pw' => 'Palau',
        'ps' => 'Palestinian Territory, Occupied',
        'pa' => 'Panama',
        'pg' => 'Papua New Guinea',
        'py' => 'Paraguay',
        'pe' => 'Peru',
        'ph' => 'Philippines',
        'pn' => 'Pitcairn',
        'pl' => 'Poland',
        'pt' => 'Portugal',
        'pr' => 'Puerto Rico',
        'qa' => 'Qatar',
        're' => 'Reunion',
        'ro' => 'Romania',
        'ru' => 'Russian Federation',
        'rw' => 'Rwanda',
        'bl' => 'Saint Barthelemy',
        'sh' => 'Saint Helena',
        'kn' => 'Saint Kitts And Nevis',
        'lc' => 'Saint Lucia',
        'mf' => 'Saint Martin',
        'pm' => 'Saint Pierre And Miquelon',
        'vc' => 'Saint Vincent And Grenadines',
        'ws' => 'Samoa',
        'sm' => 'San Marino',
        'st' => 'Sao Tome And Principe',
        'sa' => 'Saudi Arabia',
        'sn' => 'Senegal',
        'rs' => 'Serbia',
        'sc' => 'Seychelles',
        'sl' => 'Sierra Leone',
        'sg' => 'Singapore',
        'sk' => 'Slovakia',
        'si' => 'Slovenia',
        'sb' => 'Solomon Islands',
        'so' => 'Somalia',
        'za' => 'South Africa',
        'gs' => 'South Georgia And Sandwich Isl.',
        'es' => 'Spain',
        'lk' => 'Sri Lanka',
        'sd' => 'Sudan',
        'sr' => 'Suriname',
        'sj' => 'Svalbard And Jan Mayen',
        'sz' => 'Swaziland',
        'se' => 'Sweden',
        'ch' => 'Switzerland',
        'sy' => 'Syrian Arab Republic',
        'tw' => 'Taiwan',
        'tj' => 'Tajikistan',
        'tz' => 'Tanzania',
        'th' => 'Thailand',
        'tl' => 'Timor-Leste',
        'tg' => 'Togo',
        'tk' => 'Tokelau',
        'to' => 'Tonga',
        'tt' => 'Trinidad And Tobago',
        'tn' => 'Tunisia',
        'tr' => 'Turkey',
        'tm' => 'Turkmenistan',
        'tc' => 'Turks And Caicos Islands',
        'tv' => 'Tuvalu',
        'ug' => 'Uganda',
        'ua' => 'Ukraine',
        'ae' => 'United Arab Emirates',
        'gb' => 'United Kingdom',
        'us' => 'United States',
        'um' => 'United States Outlying Islands',
        'uy' => 'Uruguay',
        'uz' => 'Uzbekistan',
        'vu' => 'Vanuatu',
        've' => 'Venezuela',
        'vn' => 'Viet Nam',
        'vg' => 'Virgin Islands, British',
        'vi' => 'Virgin Islands, U.S.',
        'wf' => 'Wallis And Futuna',
        'eh' => 'Western Sahara',
        'ye' => 'Yemen',
        'zm' => 'Zambia',
        'zw' => 'Zimbabwe',
    ];


    var_dump($countries_with_codes['sg']);


    $stats = get_transient('ypd_stats');
    if (false === $stats || isset($_GET['refresh'])) {
        // Total number of signatures
        $stats['total'] = wp_count_posts('signature')->publish;

        // 1. Just plastic test
        $sql = "SELECT count(meta_id) AS 'count' FROM wp_postmeta WHERE `meta_key` = 'email' AND `meta_value` = ''";
        $stats['taken_just_plastic_test'] = $wpdb->get_var($sql);

        // 2. Taken plastic test
        $sql = "SELECT count(m3.meta_value) as count FROM wp_postmeta as m3 WHERE m3.meta_key = 'plastic_value' AND m3.meta_value != '0.01'";
        $stats['taken_plastic_test'] = $wpdb->get_var($sql);

        // 3. Taken cta
        $stats['taken_cta'] = $stats['total'] - $stats['taken_just_plastic_test'];


        // Taken just CTA
        $sql = "SELECT count(m3.meta_value) as count FROM wp_postmeta as m2 JOIN wp_postmeta as m3 ON m2.post_id = m3.post_id WHERE  m2.meta_key = 'email' AND  m2.meta_value != '' AND m3.meta_key = 'plastic_value' AND m3.meta_value = '0.01'";
        $stats['cta_only'] = $wpdb->get_var($sql);


        // Get country total data - used for validating numbers
        $sql = "SELECT meta_value, count(meta_value) as count from wp_postmeta where meta_key = 'country' GROUP BY meta_value";
        $countries = $wpdb->get_results($sql);
        foreach ($countries as $country) {
            $country_name = $country->meta_value;
            $country_count = $country->count;
            $stats['countries'][$country_name]['total'] = $country_count;
        }

        // Taken just plastic test
        $sql = "SELECT 
                    m1.meta_value as meta_value, 
                    count(m2.meta_value) as count 
                FROM  
                    wp_postmeta as m1  
                    JOIN  
                        wp_postmeta as m2  
                            ON  
                            m1.post_id = m2.post_id  
                WHERE  
                    m1.meta_key = 'country' AND 
                    m2.meta_key = 'email' AND 
                    m2.meta_value = '' 
                GROUP BY  
                    m1.meta_value";
        $countries = $wpdb->get_results($sql);
        foreach ($countries as $country) {
            $country_name = $country->meta_value;
            $country_count = $country->count;
            $stats['countries'][$country_name]['taken_just_plastic_test'] = $country_count;
        }

        // Taken plastic test
        $sql = "SELECT 
                    m1.meta_value as meta_value, 
                    count(m2.meta_value) as count 
                FROM  
                    wp_postmeta as m1  
                    JOIN 
                        wp_postmeta as m2  
                            ON  
                            m1.post_id = m2.post_id  
                WHERE  
                    m1.meta_key = 'country' AND 
                    m2.meta_key = 'plastic_value' AND 
                    m2.meta_value != '0.01' 
                GROUP BY  
                    m1.meta_value";
        $countries = $wpdb->get_results($sql);
        foreach ($countries as $country) {
            $country_name = $country->meta_value;
            $country_count = $country->count;
            $stats['countries'][$country_name]['taken_plastic_test'] = $country_count;
        }

        $sql = "SELECT m1.meta_value as meta_value, count(m2.meta_value) as count FROM  wp_postmeta as m1  JOIN  wp_postmeta as m2  ON  m1.post_id = m2.post_id  WHERE  m1.meta_key = 'country' AND m2.meta_key = 'email' AND m2.meta_value != '' GROUP BY  m1.meta_value";
        $countries = $wpdb->get_results($sql);
        foreach ($countries as $country) {
            $country_name = $country->meta_value;
            $country_count = $country->count;
            $stats['countries'][$country_name]['taken_cta'] = $country_count;
        }

        // Taken just CTA
        $sql = "SELECT 
                    m1.meta_value as meta_value,
                    count(m3.meta_value) as count 
                FROM  
                    wp_postmeta as m1  
                    JOIN  
                        wp_postmeta as m2  
                            ON  
                            m1.post_id = m2.post_id  
                    JOIN  
                        wp_postmeta as m3  
                            ON  
                            m2.post_id = m3.post_id  
                WHERE  
                    m1.meta_key = 'country' AND 
                    m2.meta_key = 'email' AND 
                    m2.meta_value != '' AND
                    m3.meta_key = 'plastic_value' AND
                    m3.meta_value = '0.01'
                GROUP BY  
                    m1.meta_value";
        $countries = $wpdb->get_results($sql);
        foreach ($countries as $country) {
            $country_name = $country->meta_value;
            $country_count = $country->count;
            $stats['countries'][$country_name]['cta_only'] = $country_count;
        }


        $stats['last_update'] = date('jS F, h:i:s A');
        set_transient('ypd_stats', $stats, HOUR_IN_SECONDS);
    }

?>
    <style>
        .ypd-stats table thead tr {
            display: block;
        }

        .ypd-stats table th.width,
        .ypd-stats table td.width {
            width: 200px;
        }


        .ypd-stats table.country tbody {
            display: block;
            height: 500px;
            overflow: auto;
        }

        .ypd-stats tbody tr:nth-child(even) {
            background: #eee;
        }
    </style>
    <div class="wrap ypd-stats">
        <h1>Stats <small>(last updated: <?= $stats['last_update'] ?>)</small></h1>
        <br>
        <div>
            <table class="widefat" cellspacing="0">
                <tbody>
                    <tr>
                        <th>Label (description)</th>
                        <th>Global</th>
                        <th>Singapore</th>
                    </tr>
                    <tr>
                        <td>Taken Just Plastic Test <small>How many people have taken just the Plastic Test and didn't take pledge?</small></td>
                        <td><?= $stats['taken_just_plastic_test'] ?></td>
                        <td><?= $stats['countries']['SG']['taken_just_plastic_test'] ?></td>
                    </tr>
                    <tr>
                        <td>Taken Plastic Test <small>How many people have taken the Plastic Test?</small></td>
                        <td><?= $stats['taken_plastic_test'] ?></td>
                        <td><?= $stats['countries']['SG']['taken_plastic_test'] ?></td>
                    </tr>
                    <tr>
                        <td>Pledged After Test <small>Of those people, how many people actually pledged (gave their email?)</small></td>
                        <td><?= ($stats['taken_cta'] - $stats['cta_only']) ?></td>
                        <td><?= ($stats['countries']['SG']['taken_cta'] - $stats['countries']['SG']['cta_only']) ?></td>
                    </tr>
                    <tr>
                        <td>Pledged Without Taking Test <small>Pledged Without Taking Test, Separately, how many people pledged directly (i.e. they didn't take the Plastic Test, but instead went to the 'What Can I Do' page)</small></td>
                        <td><?= $stats['cta_only'] ?></td>
                        <td><?= $stats['countries']['SG']['cta_only'] ?></td>
                    </tr>
                    <tr>
                        <td>Total <small>The sum of 'Pledged After Test' and 'Pledged Without Taking Test'</small></td>
                        <td><?= $stats['taken_cta'] ?></td>
                        <td><?= $stats['countries']['SG']['taken_cta'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br><br>
        <table class="widefat country" cellspacing="0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th class="width">Country name</th>
                    <th class="width">Taken Just Plastic Test</th>
                    <th class="width">Taken Plastic Test</th>
                    <th class="width">Pledged After Test</th>
                    <th class="width">Pledged Without Taking Test</th>
                    <th class="width">Total</th>
                    <!-- <th class="width">conversion</th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stats['countries'] as $key => $country) { ?>
                    <tr>
                        <td><?= $count += 1 ?></td>
                        <td><?= $key ?></td>
                        <td class="width"><?= $countries_with_codes[strtolower($key)] ?></td>
                        <td class="width">
                            <?= isset($country['taken_just_plastic_test']) ? $country['taken_just_plastic_test'] : ''; ?>
                        </td>
                        <td class="width">
                            <?= isset($country['taken_plastic_test']) ? $country['taken_plastic_test'] : ''; ?>
                        </td>
                        <td class="width">
                            <?= isset($country['taken_cta']) ? ($country['taken_cta'] - (isset($country['cta_only']) ? $country['cta_only'] : 0)) : ''; ?>
                        </td>
                        <td class="width">
                            <?= isset($country['cta_only']) ? $country['cta_only'] : ''; ?>
                        </td>
                        <td class="width"><?= isset($country['taken_cta']) ? $country['taken_cta'] : '' ?></td>
                        <!-- <td class="width"><?= (isset($country['taken_cta']) && isset($country['taken_just_plastic_test'])) ? floor(($country['taken_cta'] / $country['taken_just_plastic_test']) * 100) . '%' : ''; ?></td> -->
                    </tr>
                <?php } ?>
            </tbody>
            <thead>

                <tr>
                    <th>&nbsp; &nbsp; </th>
                    <th>&nbsp; &nbsp; </th>
                    <th class="width">Total</th>
                    <th class="width"><?= $stats['taken_just_plastic_test'] ?></th>
                    <th class="width"><?= $stats['taken_plastic_test'] ?></th>
                    <th class="width"><?= $stats['taken_cta'] ?></th>
                    <td class="width"><?= $stats['cta_only'] ?></td>
                    <th class="width"><?= $stats['taken_cta'] ?></th>
                    <!-- <th class="width"><?= floor(($stats['taken_cta'] / $stats['taken_just_plastic_test']) * 100) . '%' ?></th> -->
                </tr>
            </thead>
        </table>
    </div>
<?php
}
