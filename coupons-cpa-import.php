<?php
/*
Plugin Name: Coupons CPA Import
Plugin URI:
Description: WordPress plugin for importing and displaying coupons from CPA networks
Version: 1.46
Author: Maxim Maslov
Author URI:
Text domain: cci
Domain Path: /languages
*/

define( 'CCI_ADMITAD_DB', 'cci_admitad_import' );

require_once ( plugin_dir_path( __FILE__ ) . '/config/config.php' );
require_once ( plugin_dir_path( __FILE__ ) . '/includes/post-registration.php' );
require_once ( plugin_dir_path( __FILE__ ) . '/classes/class-cci-helpers.php' );
require_once ( plugin_dir_path( __FILE__ ) . '/classes/class-cci-coupon.php' );
require_once ( plugin_dir_path( __FILE__ ) . '/classes/class-cci-importer.php' );
require_once ( plugin_dir_path( __FILE__ ) . '/classes/class-cci-customizer.php' );
require_once ( plugin_dir_path( __FILE__ ) . '/classes/class-cci-editor.php' );

require_once ( plugin_dir_path( __FILE__ ) . '/admin/settings-page.php' );


add_action( 'wp_enqueue_scripts', 'cci_scripts' );
function cci_scripts() {

    wp_enqueue_script( 'cci-magnific', plugin_dir_url( __FILE__ ) . 'assets/js/magnific-popup.min.js', array( 'jquery'), null, 'footer' );
    wp_enqueue_script( 'cci-clipboard', plugin_dir_url( __FILE__ ) . 'assets/libs/js/clipboard.min.js', array('jquery'), null, 'footer' );
    wp_enqueue_script( 'cci-cookie', plugin_dir_url( __FILE__ ) . 'assets/libs/js/cookie.js', array(), null, 'footer' );
    wp_enqueue_script( 'cci-modules', plugin_dir_url( __FILE__ ) . 'js/modules.js', array('jquery','cci-magnific','cci-clipboard', 'cci-cookie'), null, 'footer' );

    wp_enqueue_script( 'cci-template-script', plugin_dir_url( __FILE__ ) . 'assets/js/common.js', array( 'jquery', 'cci-magnific', 'cci-clipboard', 'cci-modules' ), null, 'footer' );
    wp_enqueue_script( 'cci-script', plugin_dir_url( __FILE__ ) . 'js/script.js', array('jquery','cci-magnific', 'cci-clipboard', 'cci-modules'), null, 'footer' );

    wp_localize_script( 'jquery', 'CCI_AjaxHandler', array( 'cci_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

    $style_libs = array( 'cci-fontawesome', 'cci-magnific-style', 'cci-colors' );

    wp_enqueue_style( 'cci-colors', plugin_dir_url( __FILE__ ) . 'assets/css/colors.css' );
    wp_enqueue_style( 'cci-style', plugin_dir_url( __FILE__ ) . 'assets/css/main.css', $style_libs );
    wp_enqueue_style( 'cci-fontawesome', plugin_dir_url( __FILE__ ) . 'assets/css/fontawesome.css' );
    wp_enqueue_style( 'cci-magnific-style', plugin_dir_url( __FILE__ ) . 'assets/css/magnific-popup.min.css' );
    wp_enqueue_style( 'cci-modal-style-new', plugin_dir_url( __FILE__ ) . 'assets/css/modal.css', $style_libs, null );

    wp_enqueue_style( 'cci-google-fonts', 'https://fonts.googleapis.com/css?family=Raleway|Roboto', false );

}

function cci_admin_scripts()
{
    wp_enqueue_script( 'sfp_admin_script', plugin_dir_url( __FILE__ ) . 'admin/js/admin-scripts.js', array(), false, true );
    wp_enqueue_script( 'cci_quick_edit_script', plugin_dir_url( __FILE__ ) . 'admin/js/quick-edit.js', array('jquery','inline-edit-post') );
    wp_enqueue_script( 'jquery-ui-core', array('jquery') );
    wp_enqueue_script( 'jquery-ui-tabs', array('jquery', 'jquery-ui-core') );
    wp_enqueue_style( 'jquery-ui-style', plugin_dir_url( __FILE__ ) . 'admin/libs/jquery-ui.min.css' );
    wp_enqueue_style( 'cci-admin-style', plugin_dir_url( __FILE__ ) . 'admin/css/admin_style.css' ) ;

}
add_action( 'admin_enqueue_scripts', 'cci_admin_scripts' );


add_action( 'init', 'cci_set_config_data' );
function cci_set_config_data(){

    $template = CCI_Config::ADV_TEMPLATE;
    require_once ( plugin_dir_path(__FILE__) . "templates/advertisement/{$template}" );

    CCI_Editor::add_filters();
    CCI_Editor::add_quick_meta_edit();

}

register_activation_hook(__FILE__, 'cci_plugin_activation');
function cci_plugin_activation() {


    CCI_Importer::coupons_table_create( 'admitad' );
    CCI_Importer::coupons_table_create( 'actionpay' );
    CCI_Helpers::cache_table_create();

    wp_clear_scheduled_hook( 'cci_cron_import' );
    wp_clear_scheduled_hook( 'cci_cron_clear_cache' );
    wp_schedule_event( strtotime('21:00:00'), 'daily', 'cci_cron_import' );
    wp_schedule_event( strtotime('first day of this month', time() ), 'monthly', 'cci_cron_clear_cache' );

   CCI_Importer::coupons_set_valid_meta();
}

add_action( 'cci_cron_import', 'cci_cron_importer' );
function cci_cron_importer(){
    CCI_Importer::run_import('cron');
     CCI_Helpers::clear_wpfc_cache();
}


add_action( 'cci_cron_clear_cache', 'cci_cron_cache' );
function cci_cron_cache(){
    CCI_Helpers::clear_monthly_cache_index();
}


// set cookie with counters before load page
// add_action( 'init', 'set_counters_cookie' );
function set_counters_cookie() {
    CCI_Helpers::set_counters_cookie();
}

add_filter( 'cron_schedules', 'cci_cron_add_monthly' );
function cci_cron_add_monthly( $schedules ) {

	$schedules['monthly'] = array(
		'interval' => 2592000,
		'display' => __( 'Once a Month' )
	);
	return $schedules;
}




/***
 * Coupons shortcode
 * [coupons type="active"]
 * [coupons type="archive"]
 */
add_shortcode('coupons', 'cci_show_coupons');
function cci_show_coupons( $atts ){

    $type = isset( $atts['type'] ) ? $atts['type'] : 'active';

    $template_name = ( 'active' == $type ) ? 'section-active.php' : 'section-archive.php';
    ob_start();
    include ( plugin_dir_path( __FILE__ ) . 'templates/' . $template_name );

    $section = ob_get_contents();
    ob_end_clean();
    return $section;
};


/***
 * Single coupon shortcode
 * [coupon id="xxxx"]
 */
add_shortcode('coupon', 'cci_show_coupon');
function cci_show_coupon( $atts ){

    $coup_id = isset( $atts['id'] ) ? (int)$atts['id'] : 0;

    ob_start();
    include ( plugin_dir_path( __FILE__ ) . 'templates/section-single-coupon.php' );

    $section = ob_get_contents();
    ob_end_clean();
    return $section;
}


add_action( 'wp_ajax_counterinc', 'cci_counter' );
add_action( 'wp_ajax_nopriv_counterinc', 'cci_counter' );
function cci_counter(){
    $coupon_id 	= esc_html( $_POST['data'] );
    $x = CCI_Helpers::increase_counter( $coupon_id );
    echo $x;
    wp_die();
}


add_action( 'wp_ajax_getcounters', 'cci_get_counters_json' );
add_action( 'wp_ajax_nopriv_getcounters', 'cci_get_counters_json' );
function cci_get_counters_json(){

    $json = CCI_Helpers::get_counters_json();
    echo $json;
    wp_die();
}


add_action( 'wp_ajax_loader', 'cci_archive_loader' );
add_action( 'wp_ajax_nopriv_loader', 'cci_archive_loader' );
function cci_archive_loader(){
    $index_start 	= (int)esc_html( $_POST['index'] );
    $part           = (int)esc_html( $_POST['part'] );
    $expired_coupons = CCI_Helpers::get_expired_coupons();

    if ( count( $expired_coupons ) < $index_start ){
        echo 1;
        wp_die();
    }

    $expired_coupons = array_slice( $expired_coupons, $index_start, $part );

    ob_start();

    foreach ( $expired_coupons as $expired_coupon ){
		$coupon =  new CCI_Coupon( $expired_coupon );
		?>
		<div class="archive_item_wrapper">
			<div class="archive_item_wrapper--handler">
				<header class="archive_item_wrapper--title">
					<?php echo $coupon->get_title(); ?>
				</header>
				<div class="archive_item_wrapper--toggle">
					<i class="far fa-caret-square-down"></i>
				</div>
			</div>
			<?php $coupon->render(); ?>
		</div>
	<?php
	}

    $data = ob_get_contents();
    ob_end_clean();
    echo $data;
    wp_die();

}


add_filter( 'pre_get_document_title', 'cci_change_page_title', PHP_INT_MAX );
function cci_change_page_title ( $title ) {

    $seo_title = CCI_Helpers::get_seo_title();
    $page_title = $title;
    global $post;
    if( has_shortcode( $post->post_content, 'coupons') ) {
      $title = ( null !== $seo_title ) ? $seo_title : $page_title;
    } else {
      $title = $page_title;
    }

    return $title;
}



add_action( 'wp_head', 'cci_header_css' );
function cci_header_css(){

    echo '<style>.coupons_active_container--title > h1{display: block;}</style>';
    echo CCI_Customizer::section_bg_color();
}


add_action( 'wp_footer', 'cci_add_modal' );
function cci_add_modal(){
	 include ( plugin_dir_path( __FILE__ ) . 'templates/modal.php');
}


//add_action('wpfc_delete_cache', "cci_clear_cache_logger");
//function cci_clear_cache_logger(){
//    // logger code here
//}



function cci_clear_wpfc_cache(){
    if(isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')){
        $GLOBALS['wp_fastest_cache']->deleteCache(true);
    }
}

function cci_is_table_exists($table_name){
    global $wpdb;
    $db_name = $wpdb->dbname;
    $sql = "SELECT COUNT(*) FROM information_schema.tables WHERE TABLE_SCHEMA = '$db_name' AND TABLE_NAME = '$table_name'";

    $is_table = $wpdb->get_var($sql);

    return $is_table;
}
