<?php

/**
 * Coupon custom post type registration
 * 
 */

add_action( 'init', 'cci_post_registration' );
function cci_post_registration() {	
	
	$labels = array(
		'name'				=> 'Купоны',
		'menu_name'			=> 'Купоны',
		'singular_name'		=>'Купоны',
		'search_items'		=>'Поиск купонов',
		'name_admin_bar'	=>'Купон',
		'all_items'			=>'Все купоны',
		'add_new'			=> 'Добавить купон',
		'edit_item'			=> 'Редактировать купон',
        'add_new_item'		=> 'Добавить новый купон',
        'view_item'			=> 'Просмотреть купон',
		'not_found'			=> 'Ничего не найдено',
		'not_found_in_trash' =>'В корзине купонов нет'
	); 
		
    $args = array(
        'labels'				=>$labels,
        'public'				=> true,
		'query_var'             => true,
		'publicly_queryable'	=> true,
        'show_ui'				=> true,
		'show_in_menu'			=> true,
		'exclude_from_search'	=> false,
		'show_in_nav_menus'		=> true,
        'show_in_admin_bar'		=> true,
		'menu_position'			=> 5,
		'menu_icon'				=> 'dashicons-megaphone',
        'hierarchical'			=> true,
        'rewrite'				=> array("slug" => "coupons", 'with_front' => false), 
		'taxonomies'			=> array('coupon-types','coupon-species'),
		'has_archive'			=> true,
		// 'supports'				=> array( 'title', 'editor', 'comments', 'revisions', 'author', 'excerpt', 'thumbnail'),
		'supports'				=> array( 'title', 'editor' ),
	); 

	register_post_type( 'coupons' , $args );
};


/**
 * Custom taxonomy registration
 * 
 */
add_action( 'init', 'cci_taxonomy_registration' );
function cci_taxonomy_registration(){

	// type
	$labels_types = array(
		'name' => 'Типы купонов',
		'singular_label' =>'Типы',
		'singular_name' => 'Типы купонов',
		'search_items' => 'Поиск типов купонов',
		'all_items' => 'Все типы купонов',
		'parent_item' => 'Родителький тип купонов',
		'parent_item_colon' => 'Родителький тип:',
		'edit_item' => 'Редактировать тип купона',
		'update_item' => 'Обновить тип купона',
		'add_new_item' => 'Добавить тип купона',
		'new_item_name' => 'Новаый тип',
		'menu_name' => 'Типы купонов'
	);
	
	$args_types = array (
		'labels'				=> $labels_types,
		'public'				=> true,
		'show_ui'				=> true,
		'show_in_menu'			=>true,
		'show_in_nav_menus'		=> true,
		'hierarchical'			=> true,
		'publicly_queryable'	=> false,
		'exclude_from_search'	=>true,
		'show_in_admin_bar'		=> true,
		'map_meta_cap'			=>true,
		'rewrite'				=> array( 'slug' => 'coupon-types', 'with_front' => false ),
		'query_var'             => true,
		'meta_box_cb'			=> 'post_categories_meta_box'
	);	

	// species
	$labels_spec = array(
		'name'				=> 'Виды купонов',
		'singular_label'	=>'Виды',
		'singular_name'		=> 'Виды купонов',
		'search_items'		=> 'Поиск видов купонов',
		'all_items'			=> 'Все виды купонов',
		'parent_item'		=> 'Родителький вид купонов',
		'parent_item_colon'	=> 'Родителький вид:',
		'edit_item'			=> 'Редактировать вид купона',
		'update_item'		=> 'Обновить вид купона',
		'add_new_item'		=> 'Добавить вид купона',
		'new_item_name'		=> 'Новый вид',
		'menu_name'			=> 'Виды купонов'
	);
		
	$args_spec = array (
		'labels'				=> $labels_spec,
		'public'				=> true,
		'show_ui'				=> true,
		'show_in_menu'			=>true,
		'show_in_nav_menus'		=> true,
		'hierarchical'			=> true,
		'publicly_queryable'	=> false,
		'exclude_from_search'	=>true,
		'show_in_admin_bar'		=> true,
		'map_meta_cap'			=>true,
		'rewrite'				=> array( 'slug' => 'coupon-species', 'with_front' => false ),
		'query_var'             => true,
		'meta_box_cb'			=> 'post_categories_meta_box'
	);	
		
    register_taxonomy( 'coupon-species', 'coupons', $args_spec );		
	register_taxonomy( 'coupon-types', 'coupons', $args_types );		

};

/**
 * Create terms in the coupons-species custom taxonomy
 * 
 */
add_action( 'init', 'cci_terms_species_taxonomy_insert' );
function cci_terms_species_taxonomy_insert(){
	
	wp_insert_term(
		'Промокод',
		'coupon-species',
		array(
			'description' => 'Соответствует specie_id="1"',
			'slug'        => 'promocode'
		)
	);
	wp_insert_term(
		'Акция',
		'coupon-species',
		array(
			'description' => 'Соответствует specie_id="2"',
			'slug'        => 'action'
		)
	);

	wp_insert_term(
		'Доставка',
		'coupon-types',
		array(
			'description' => 'Соответствует type_id="1"',
			'slug'        => 'delivery'
		)
	);
	wp_insert_term(
		'Акция',
		'coupon-types',
		array(
			'description' => 'Скидка. Соответствует type_id="2"',
			'slug'        => 'discount'
		)
	);
	wp_insert_term(
		'Подарок',
		'coupon-types',
		array(
			'description' => 'Соответствует type_id="3"',
			'slug'        => 'gift'
		)
	);

}

/**
 * Create terms in the coupons-types custom taxonomy
 * 
 */
// add_action( 'init', 'cci_terms_types_taxonomy_insert' );
function cci_terms_types_taxonomy_insert(){
	
	
}

/**
 * Metaboxes registration
 * 
 */
add_action( 'add_meta_boxes', 'cci_post_metaboxes' );
function cci_post_metaboxes() {
	add_meta_box('show_coupons_meta', 'Дополнительные попя купона', 'cci_show_post_meta', 'coupons', 'advanced', 'high');
};

function cci_show_post_meta() {
	global $post;
	$data['coupon_nonce']		= wp_create_nonce( plugin_basename(__FILE__) );
	$data['coupon_id']			= get_post_meta( $post->ID, 'coupon_id', true );
	$data['coupon_promocode']	= get_post_meta( $post->ID, 'coupon_promocode', true );
	$data['coupon_promolink']	= get_post_meta( $post->ID, 'coupon_promolink', true );
	$data['coupon_gotolink']	= get_post_meta( $post->ID, 'coupon_gotolink', true );
	$data['coupon_date_start']	= get_post_meta( $post->ID, 'coupon_date_start', true );
	$data['coupon_date_end']	= get_post_meta( $post->ID, 'coupon_date_end', true );
	$data['coupon_discount']	= get_post_meta( $post->ID, 'coupon_discount', true );
	$data['coupon_counter']		= get_post_meta( $post->ID, 'coupon_counter', true );
	$data['coupon_prolong']		= get_post_meta( $post->ID, 'coupon_prolong', true );
	$data['coupon_valid']			= get_post_meta( $post->ID, 'coupon_valid', true );
	
	include_once(plugin_dir_path( __FILE__ )."../includes/post-metabox.php");		
};

/**
 * Save metaboxes
 * 
 */
add_action( 'save_post', 'cci_save_post_meta', 1, 2 ); 
function cci_save_post_meta( $post_id, $post) {
	// if ( !wp_verify_nonce( $_POST['coupon_nonce'], plugin_basename(__FILE__) )) {
	// 	return $post->ID;
	// };
	
	if ( !current_user_can( 'edit_post', $post->ID ) ){
		return $post->ID;
	};

	$metadata = array(
		'coupon_id',
		'coupon_promocode',
		'coupon_promolink',
		'coupon_gotolink',
		'coupon_date_start',
		'coupon_date_end',
		'coupon_discount',
		'coupon_counter',
		'coupon_prolong',
		'coupon_valid'
	);

	// $events_meta = $_POST;
	$events_meta = array();
	
	foreach( $metadata as $meta){
		
		if( array_key_exists( $meta, $_POST ) ){
			$events_meta[$meta] = $_POST[$meta];
			
		};
	};

	

	foreach ( $events_meta as $key => $value ) { // Cycle through the $events_meta array!
	
		if( $post->post_type == 'revision' ) 
			return; // Don't store custom data twice
		$value = implode( ',', (array)$value ); // If $value is an array, make it a CSV (unlikely)
		if( get_post_meta( $post->ID, $key, FALSE ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if( !$value ) delete_post_meta( $post->ID, $key ); // Delete if blank

		// save checkbox
		if ( isset($_POST['coupon_prolong']) ) {
			update_post_meta($post->ID, 'coupon_prolong', $_POST['coupon_prolong']);
		}else{
			delete_post_meta($post->ID, 'coupon_prolong');
		}
	};

	// update validation
	$date_today = new DateTime( current_time( 'Y-m-d', 0 ) );
	$date_end = new DateTime( get_post_meta( $post->ID, 'coupon_date_end', true) );
	$is_valid = ( $date_end >= $date_today ) ? 1 :0;
  update_post_meta( $post->ID, 'coupon_valid', $is_valid );
	
};
