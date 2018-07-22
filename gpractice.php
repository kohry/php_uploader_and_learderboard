<?php

/**

 * @link              http://machinelearningkorea.com
 * @since             1.0.1
 * @package           GCP
 *
 * @wordpress-plugin
 * Plugin Name:       Gorakgarak Competition Practice
 * Plugin URI:        http://machinelearningkorea.com/plugin-name-uri/
 * Description:       for internal use only
 * Version:           1.0.1
 * Author:            mlkorea
 * Author URI:        http://machinelearningkorea.com/
 * License:           mlk
 * License URI:       http://machinelearningkorea.com/
 * Text Domain:       gdp3
 * Domain Path:       /languages
 */


//require_once plugin_dir_path( __FILE__ ) . 'parser.php';


//shortcode
add_action('init', 'init_gp');

add_action( 'wp_enqueue_scripts', 'gp_enqueue' );

//순서 중요한듯
// 1 외부에서 request할수 있게. 접미사 wp_ajax 가 있으면 알아서 하는듯
add_action( 'wp_ajax_gdp3_request_leaderboard', 'gdp3_request_leaderboard' );

// 2 외부에서 로그인 없이 볼수 있도록함. wp_ajax_nopriv 가 있으면 알아서 한다.
add_action( 'wp_ajax_nopriv_request_leaderboard', 'gdp3_request_leaderboard' );


//주소를 적당히 만들어준다. ajax콜할수 있도록.
function gp_enqueue() {
	// Enqueue javascript on the frontend.
	wp_enqueue_script(
		'gpractice_url',
		plugins_url('gpractice.js', __FILE__),
		array('jquery')
	);
	// The wp_localize_script allows us to output the ajax_url path for our script to use.
	wp_localize_script(
		'gpractice_url',
		'gpractice_obj',
		array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
	);
}

//처음 DB초기화하는거.
function init_db() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $table_name = $wpdb->prefix . "ACCURACY_RESULT"; 

    $sql = "CREATE TABLE $table_name (
        seq mediumint(9) NOT NULL AUTO_INCREMENT,
        competition_id mediumint(10) NOT NULL, 
        accuracy double,
        timestamp TIMESTAMP DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP
        desc text,
        PRIMARY KEY  (seq)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function get_html($atts = [], $content = null)
{
    return file_get_contents(plugins_url("/template.php",__FILE__));
}


//
function init_gp()
{
    add_shortcode('gpractice', 'get_html');
    init_db();
}


//실제 가져온다.
function gdp3_request_leaderboard() {

    $limit = 20;
 
    $table_name = $wpdb->prefix . 'ACCURACY_RESULT';
    $result = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY accuracy DESC LIMIT $limit");

    echo $result;

    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {        
        // If you're debugging, it might be useful to see what was sent in the $_REQUEST
        // print_r($_REQUEST); 
    }
     
    // Always die in functions echoing ajax content
   die();
}




