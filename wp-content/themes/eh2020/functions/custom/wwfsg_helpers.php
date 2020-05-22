<?php
/**
 * @author Hanafi Ahmat (hbahmat@wwf.sg)
 */

if ( ! defined( 'WWFSGENV' ) ) {
    error_reporting( E_ALL );
    ini_set( 'display_errors', 1 );

    /**
     * Dynamically detect domain name
     */
    $tldOne = [ '.aero','.asia','.biz','.cat','.com','.coop','.info','.int','.jobs','.mobi','.museum','.name','.net','.org','.post','.pro','.tel','.travel','.mlcee','.xxx','.ac','.ad','.ae','.af','.ag','.ai','.al','.am','.an','.ao','.aq','.ar','.as','.at','.au','.aw','.ax','.az','.ba','.bb','.bd','.be','.bf','.bg','.bh','.bi','.bj','.bl','.bm','.bn','.bo','.bq','.br','.bs','.bt','.bv','.bw','.by','.bz','.ca','.cc','.cd','.cf','.cg','.ch','.ci','.ck','.cl','.cm','.cn','.co','.cr','.cu','.cv','.cw','.cx','.cy','.cz','.de','.dj','.dk','.dm','.do','.dz','.ec','.ee','.eg','.eh','.er','.es','.et','.eu','.fi','.fj','.fk','.fm','.fo','.fr','.ga','.gb','.gd','.ge','.gf','.gg','.gh','.gi','.gl','.gm','.gn','.gp','.gq','.gr','.gs','.gt','.gu','.gw','.gy','.hk','.hm','.hn','.hr','.ht','.hu','.id','.ie','.il','.im','.in','.io','.iq','.ir','.is','.it','.je','.jm','.jo','.jp','.ke','.kg','.kh','.ki','.km','.kn','.kp','.kr','.kw','.ky','.kz','.la','.lb','.lc','.li','.lk','.lr','.ls','.lt','.lu','.lv','.ly','.ma','.mc','.md','.me','.mf','.mg','.mh','.mk','.ml','.mm','.mn','.mo','.mp','.mq','.mr','.ms','.mt','.mu','.mv','.mw','.mx','.my','.mz','.na','.nc','.ne','.nf','.ng','.ni','.nl','.no','.np','.nr','.nu','.nz','.om','.online','.pa','.pe','.pf','.pg','.ph','.pk','.pl','.pm','.pn','.pr','.ps','.pt','.pw','.py','.qa','.re','.ro','.rs','.ru','.rw','.sa','.sb','.sc','.sd','.se','.sg','.sh','.si','.sj','.sk','.sl','.sm','.sn','.so','.sr','.st','.su','.sv','.sx','.sy','.sz','.tc','.td','.tf','.tg','.th','.tj','.tk','.tl','.tm','.tn','.to','.tp','.tr','.tt','.tv','.tw','.tz','.ua','.ug','.uk','.um','.us','.uy','.uz','.va','.vc','.ve','.vg','.vi','.vn','.vu','.wf','.ws','.ye','.yt','.za','.zm','.zw' ];
    $tldTwo = [ '.com.my','.co.uk','.com.sg','.co.id','.com.my','.co.jp' ];
    $devTld = [ '.col','.local','.dev','.ain','.app','.exp','.localhost' ];
    $baseDomainName = null;
    if ( isset( $_SERVER[ 'HTTP_HOST' ] ) ) {
        $tld = null;
        $domainName = explode( '.', $_SERVER[ 'HTTP_HOST' ] );
        if ( count( $domainName ) == 1 ) {
            $domainName = $domainName[ 0 ];
        } else {
            if ( in_array( '.'.$domainName[ count( $domainName ) - 2 ].'.'.$domainName[ count( $domainName ) - 1 ], $tldTwo ) ) {
                $tld = '.'.$domainName[ count( $domainName ) - 2 ].'.'.$domainName[ count( $domainName ) - 1 ];
                $baseDomainName = implode( '.', array_slice( $domainName, 1 ) );
            } else if ( in_array( '.'.$domainName[ count( $domainName ) - 1 ], $tldOne ) ) {
                $tld = '.'.$domainName[ count( $domainName )- 1 ];
                $baseDomainName = implode( '.', array_slice( $domainName, 1 ) );
            } else if ( in_array( '.'.$domainName[ count( $domainName ) - 1 ], $devTld ) ) {
                $tld = '.'.$domainName[ count( $domainName ) - 1 ];
                $baseDomainName = implode( '.', array_slice( $domainName, 1 ) );
            } else {
                $baseDomainName = $_SERVER[ 'HTTP_HOST' ];
            }
        }
        $domainName = $_SERVER[ 'HTTP_HOST' ];
        defined( 'IS_CONSOLE' ) or define( 'IS_CONSOLE', false );
    } else {
        $tld = null;
        $domainName = 'console';
        defined( 'IS_CONSOLE' ) or define( 'IS_CONSOLE', true );
    }
    defined( 'BASE_DOMAIN_NAME' ) or define( 'BASE_DOMAIN_NAME', $baseDomainName );
    defined( 'DOMAIN_NAME' ) or define( 'DOMAIN_NAME', $domainName );
    /**
     * END Dynamically detect domain name
     */

    /**
     * ENVIRONMENT declaration
     */
    if ( in_array( $tld, array_merge( $devTld, [ /*add your own local tld here*/ ] ) ) || $baseDomainName=='appitnetwork.net' ) {
        define( 'WWFSGENV', 'dev' );
        ini_set( 'display_errors', E_ALL );
    } else if ( mb_substr_count( $baseDomainName, 'test' ) > 0 || mb_substr_count( $baseDomainName, 'tst' ) > 0 ) {
        define( 'WWFSGENV', 'test' );
    } else {
        define( 'WWFSGENV', 'prod' );
        error_reporting( 0 );
        ini_set( 'display_errors', 0 );
    }
}

if ( !function_exists( 'pr' ) ) {
    // global debug nice print_r function adapted from cakePHP
    function pr( $var = null ) {
        $template = PHP_SAPI !== 'cli' ? '<pre class="pr">%s</pre>' : "\n%s\n\n";
        printf( $template, trim( print_r( $var, true ) ) );
    }
}

if ( !function_exists( 'wwfsg_get_curl_log' ) ) {
    function wwfsg_get_curl_log() {
        return ABSPATH . 'wp-content/wwfsg-curl.log';
    }
}

if ( !function_exists( 'wwfsg_info_log' ) ) {
    function wwfsg_info_log( $message, $origin = [] ) {
        if ( get_option( 'wwfsg_enable_info_log') ) {
            $timestamp = current_time( 'd-m-Y H:i:s' );
            $stamp = null;
            if ( !empty( $origin ) ) {
                $origin['file'] = str_replace(ABSPATH, '', $origin['file']);
                if ( array_key_exists( 'file', $origin ) && array_key_exists( 'function', $origin ) && array_key_exists( 'line', $origin ) ) {
                    $stamp = "\n[$timestamp][".$origin['file']."][".$origin['function']."][".$origin['line']."] ";
                } else if ( array_key_exists( 'file', $origin ) && array_key_exists( 'line', $origin ) ) {
                    $stamp = "\n[$timestamp][".$origin['file']."][][".$origin['line']."] ";
                }
            }
            if ( is_null( $stamp ) ) {
                $dbt = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
                $dbt[1]['file'] = str_replace(ABSPATH, '', $dbt[1]['file']);
                $stamp = "\n[$timestamp][".$dbt[1]['file']."][".$dbt[1]['function']."][".$dbt[1]['line']."] ";
            }
            $file = ABSPATH . 'wp-content/wwfsg-info.log';
            file_put_contents( $file, $stamp . print_r( $message, true ),  FILE_APPEND );
        }
    }
}

if ( !function_exists( 'wwfsg_render_view' ) ) {
    function wwfsg_render_view( $file, $params = [] ) {
        ob_start();
        ob_implicit_flush( false );
        extract( $params, EXTR_OVERWRITE );
        require( $file );
        return ob_get_clean();
    }
}

if ( !function_exists( 'wwfsg_test_send_mail' ) ) {
    function wwfsg_test_send_mail( $email, $subject, $content ) {
        return wp_mail($email, $subject, $content);
    }
}

if( array_key_exists('test-send-mail', $_GET) && $_GET['test-send-mail'] ) {
    wwfsg_test_send_mail( 'hbahmat@wwf.sg', 'Test Send Email from '.$_SERVER['HTTP_HOST'], 'Testing...' );
}
