<?php
// Basic class of Editor functions

abstract class CCI_Editor{


  public static function add_filters(){

    if( is_admin() ){

      if ( !isset( $_GET['coups_act'] ) ) $_GET['coups_act'] = -1;

          add_action( 'restrict_manage_posts', function( $post_type ){
            if( ! in_array( $post_type, ['coupons'] ) ) return;
            echo '
                    <select name="coups_act">
                        <option value="-1" '. selected(-1, $_GET['coups_act'], 0) .'>- все купоны -</option>
                        <option value="1" '. selected(1, $_GET['coups_act'], 0) .'>действующие</option></option>
                        <option value="0"'.   selected(0, $_GET['coups_act'], 0) .'>истекшие</option>
                    </select>';
          });

            add_action( 'pre_get_posts', 'add_event_table_filters_handler' );
            function add_event_table_filters_handler( $query ) {
              global $pagenow;
              $type = 'coupons';
              if( ! is_admin() ) return;
              if (isset($_GET['post_type'])) {
                   $type = $_GET['post_type'];
              };
              if( 'coupons' == $type
                  && is_admin()
                  && $pagenow == 'edit.php'
                  && isset( $_GET['coups_act'] )
                  && $_GET['coups_act'] != -1
                  && $query->is_main_query()
              ){
               
                $query->query_vars['meta_key']    = 'coupon_valid';          
                $query->query_vars['meta_value']  = esc_html($_GET['coups_act']);
              }
            }
        };
      }
      

// добавляем метабоксы на панель quick edit
public static function add_quick_meta_edit(){
  add_action( 'manage_coupons_posts_columns', array(__CLASS__, 'add_custom_admin_column'), 10, 1 );
  add_action( 'manage_coupons_posts_custom_column', array(__CLASS__, 'manage_custom_admin_columns'), 10, 2 );
  add_action( 'quick_edit_custom_box', array(__CLASS__, 'display_quick_edit_custom'), 10, 1 );
}    

    
public static function add_custom_admin_column( $columns ){
  
    $new_columns = array();

    $new_columns['coupon_promocode'] = 'Промокод';
    $new_columns['coupon_promolink'] = 'Промоссылка';
    $new_columns['coupon_gotolink'] = 'Партнерская ссылка';
    $new_columns['coupon_discount'] = 'Скидка';
    $new_columns['coupon_counter'] = 'Просмотры';
    $new_columns['coupon_date_start'] = 'Начало';
    $new_columns['coupon_date_end'] = 'Окончание';
    $new_columns['coupon_prolong'] = 'Автопродление';
   
    return array_merge($columns, $new_columns);
}

public static function manage_custom_admin_columns( $column_name, $post_id ){

    $html = '';
    if( $column_name == 'coupon_promocode'){
        $post_meta = get_post_meta($post_id, 'coupon_promocode', true);
        $html .= '<div id="coupon_promocode_' . $post_id . '">';
            $html .= $post_meta;
        $html .= '</div>';
    } else if( $column_name == 'coupon_promolink'){
        $post_meta = get_post_meta($post_id, 'coupon_promolink', true);
        $html .= '<div id="coupon_promolink_' . $post_id . '">';
            $html .= $post_meta;
        $html .= '</div>';
    } else if( $column_name == 'coupon_gotolink'){
        $post_meta = get_post_meta($post_id, 'coupon_gotolink', true);
        $html .= '<div id="coupon_gotolink_' . $post_id . '">';
            $html .= $post_meta;
        $html .= '</div>';
    } else if( $column_name == 'coupon_discount'){
        $post_meta = get_post_meta($post_id, 'coupon_discount', true);
        $html .= '<div id="coupon_discount_' . $post_id . '">';
            $html .= $post_meta;
        $html .= '</div>';
    } else if( $column_name == 'coupon_counter'){
        $post_meta = get_post_meta($post_id, 'coupon_counter', true);
        $html .= '<div id="coupon_counter_' . $post_id . '">';
            $html .= $post_meta;
        $html .= '</div>';
    } else if( $column_name == 'coupon_date_start'){
        $post_meta = get_post_meta($post_id, 'coupon_date_start', true);
        $html .= '<div id="coupon_date_start_' . $post_id . '">';
            $html .= $post_meta;
        $html .= '</div>';
    } else if( $column_name == 'coupon_date_end'){
        $post_meta = get_post_meta($post_id, 'coupon_date_end', true);
        $html .= '<div id="coupon_date_end_' . $post_id . '">';
            $html .= $post_meta;
        $html .= '</div>';
    } else if($column_name == 'coupon_prolong'){
        $post_meta = get_post_meta($post_id, 'coupon_prolong', true);
        $html .= '<div id="coupon_prolong_' . $post_id . '">';
        if($post_meta == '0' || empty($post_meta)){
            $html .= 'нет';
        }else if ($post_meta == '1'){
            $html .= 'да';
        }
        $html .= '</div>';
    };
 echo $html;

}

public static function display_quick_edit_custom( $column ){

    $html = '';
    wp_nonce_field('post_metadata', 'post_metadata_field');

    if( $column == 'coupon_promocode' ){
      $html .= '<fieldset class="inline-edit-col-right ">';
        $html .= '<div class="inline-edit-col">';
            $html .= '<div class="inline-edit-group wp-clearfix">';
                $html .= '<label class="inline-edit-status alignleft" for="coupon_promocode">';
                  $html .= '<span class="title">Промокод</span>';
                  $html .= '<input type="text" name="coupon_promocode" id="coupon_promocode" value="" />';
              $html .= '</label>';    
            $html .= '</div>';
          $html .= '</div>';
        $html .= '</fieldset>';    
    } elseif( $column == 'coupon_promolink' ){
        $html .= '<fieldset class="inline-edit-col-left">';
            $html .= '<div class="inline-edit-col">';
                $html .= '<div class="inline-edit-group wp-clearfix">';
                    $html .= '<label class="alignleft_" for="coupon_promolink">';
                        $html .= '<span class="title">Промоссылка</span>';
                        $html .= '<span class="input-text-wrap">';
                            $html .= '<input type="text" class="cci-admin-long-input" name="coupon_promolink" id="coupon_promolink" value="" />';
                        $html .= '</span>';    
                    $html .= '</label>';        
                $html .= '</div>';  
            $html .= '</div>';
        $html .= '</fieldset>';    
    } elseif( $column == 'coupon_gotolink' ){
        $html .= '<fieldset class="inline-edit-col-left">';
            $html .= '<div class="inline-edit-col">';
                $html .= '<div class="inline-edit-group wp-clearfix">';
                    $html .= '<label class="alignleft_" for="coupon_gotolink">';
                        $html .= '<span class="title">Партнер</span>';
                        $html .= '<span class="input-text-wrap">';
                            $html .= '<input type="text"  class="cci-admin-long-input"  name="coupon_gotolink" id="coupon_gotolink" value="" />';
                        $html .= '</span>';    
                    $html .= '</label>';        
                $html .= '</div>';  
            $html .= '</div>';
        $html .= '</fieldset>';    
    } elseif( $column == 'coupon_discount' ){
        $html .= '<fieldset class="inline-edit-col-right">';
            $html .= '<div class="inline-edit-col">';
                $html .= '<label class="inline-edit-group wp-clearfix">';
                    $html .= '<label class="inline-edit-status alignleft" for="coupon_discount">';
                        $html .= '<span class="title">Скидка</span>';
                        $html .= '<input type="text" name="coupon_discount" id="coupon_discount" value="" />';
                    $html .= '</label>';        
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</fieldset>';    
    } elseif( $column == 'coupon_date_start' ){
        $html .= '<fieldset class="inline-edit-col-right">';
            $html .= '<div class="inline-edit-col">';
                $html .= '<div class="inline-edit-group wp-clearfix">';
                    $html .= '<label class="inline-edit-status alignleft" for="coupon_date_start">';
                        $html .= '<span class="title">Начало</span>';
                        $html .= '<input type="date" name="coupon_date_start" id="coupon_date_start" value="" />';
                    $html .= '</label>';        
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</fieldset>';    
    } elseif( $column == 'coupon_date_end' ){
        $html .= '<fieldset class="inline-edit-col-right ">';
            $html .= '<div class="inline-edit-col">';
                $html .= '<div class="inline-edit-group wp-clearfix">';
                    $html .= '<label class="inline-edit-status alignleft" for="coupon_date_start">';
                        $html .= '<span class="title">Окончание</span>';
                        $html .= '<input type="date" name="coupon_date_end" id="coupon_date_end" value="" />';
                    $html .= '</label>';        
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</fieldset>';    
    } elseif( $column == 'coupon_counter' ){
        $html .= '<fieldset class="inline-edit-col-right ">';
            $html .= '<div class="inline-edit-col">';
                $html .= '<div class="inline-edit-group wp-clearfix">';
                    $html .= '<label class="inline-edit-status alignleft" for="coupon_counter">';
                        $html .= '<span class="title">Просмотры</span>';
                        $html .= '<input type="text" name="coupon_counter" id="coupon_counter" value="" />';
                    $html .= '</label>';        
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</fieldset>';    
    
} elseif( $column == 'coupon_prolong' ){
        $html .= '<fieldset class="inline-edit-col-right">';
            $html .= '<div class="inline-edit-col">';
                $html .= '<div class="inline-edit-group wp-clearfix">';
                    $html .= '<label class="inline-edit-status alignleft" for="coupon_counter">';
                        $html .= '<span class="checkbox-title">Автопродление</span>';
                        $html .= '<input type="checkbox" class="cci-admin-checkbox" name="coupon_prolong" id="coupon_prolong" value="1" checked/>';
                    $html .= '</label>';        
                $html .= '</div>';
            $html .= '</div>';
        $html .= '</fieldset>';    
    }
 
    echo $html;
}


}