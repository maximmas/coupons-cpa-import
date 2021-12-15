<?php
require_once __DIR__ . '/config.php';


add_action('admin_enqueue_scripts', 'mp_admin_scripts');
function mp_admin_scripts()
{

    wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js', array(), false, true );
    wp_enqueue_script( 'vuex', 'https://cdnjs.cloudflare.com/ajax/libs/vuex/3.1.1/vuex.min.js', array('vue'), false, true );
    wp_enqueue_script( 'vuetify', 'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js', array('vue'), false, true );
    wp_enqueue_script( 'admin-script', plugins_url( 'admin/admin-script.js', __FILE__ ), array('vue', 'vuex', 'vuetify', 'jquery'), false, true );

    global $options_name;
    global $default_options;

    wp_localize_script(
      'admin-script',
      'mp_options_data',
      array(
          'nonce'   => wp_create_nonce( 'wp_rest' ),
          'siteUrl' => get_site_url(),
          'options' => get_option( 'cci_options', $default_options ),
      ));

    wp_enqueue_style('vuetify-fonts', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900', false );
    wp_enqueue_style('vuetify-icons', 'https://cdn.jsdelivr.net/npm/@mdi/font@3.x/css/materialdesignicons.min.css', false );
    wp_enqueue_style( 'vuetify-style', "https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" );
    wp_enqueue_style( 'admin-style', plugins_url( 'admin/css/admin-style.css', __FILE__ ) );
};

add_action('admin_menu', 'mp_admin_menu');
function mp_admin_menu()
{
    $page_title = "CCI settings";
    $menu_title = "Coupons CPA Import настройки";

    $capability = "manage_options";
    $menu_slug = "mp_plugin_settings";
    $callback = "mp_settings_page_render";

    $parent_menu_item = "options-general.php";
    // $parent_menu_item = "tools.php";
    // $parent_menu_item = "edit.php";
    // $parent_menu_item = "edit.php?post_type=page";

    add_submenu_page( $parent_menu_item, $page_title, $menu_title, $capability, $menu_slug, $callback );
};

function mp_settings_page_render(){
    require_once __DIR__ . '/admin/admin-page.html';
};

add_action( 'rest_api_init', 'mp_register_rest' );
function mp_register_rest() {
        register_rest_route(
            'mp/v2',
            '/options',
            array(
                'methods' => 'POST',
                'callback' => 'mp_save_updated_options'
            )
        );
  };

function mp_save_updated_options( WP_REST_Request $request ){
    $parameters = $request->get_params();
    $x = mp_sanitize_options( $parameters );
    update_option( 'cci_options', mp_sanitize_options( $parameters ) );
    die(1);
};

function mp_sanitize_options( $parameters ){
    foreach ( $parameters as $key => $value ){
            $value = esc_html( $value );
    };
    return $parameters;
};
