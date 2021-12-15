<?php
/**
 * Вспомогательные функции
 *
 *
 */


abstract class CCI_Helpers
{

    /**
    * Все активные купоны
    *
    * @return Array
    */
    public static function get_active_coupons(){

        $coupons    = array();
        $args = array(
            'numberposts' => -1,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'post_type'   => 'coupons'
        );

        $posts      =  get_posts( $args );
        $date_today = new DateTime( current_time( 'Y-m-d', 0 ) );

        foreach ( $posts as $post ){
            $date_end = new DateTime( get_post_meta( $post->ID, 'coupon_date_end', true) );

            if ( $date_end >= $date_today ){
                $coupons[] = $post;
            };
        };

        return $coupons;
    }

    /**
    * Все истекшие купоны
    *
    * @return Array
    */
    public static function get_expired_coupons(){
        $coupons    = array();
        $posts      =  get_posts( 'post_type=coupons&numberposts=-1' );
        $date_today = new DateTime( current_time( 'Y-m-d', 0 ) );
        foreach ( $posts as $post ){
            $date_end = new DateTime( get_post_meta( $post->ID, 'coupon_date_end', true) );
            if ( $date_end < $date_today ){
                $coupons[] = $post;
            };
        };
        return $coupons;
    }

    /**
     * Инкремент счетчика
     *
     * @param Int $id - Coupon ID
     *
     * @return Int
     */
    public static function increase_counter( $id ){
        $args = array(
            'post_type' => 'coupons',
            'meta_query' => array(
                array(
                    'key' => 'coupon_id',
                    'value' => $id,
                ),
            ),
        );
        $coupon     =  get_posts( $args );
        $counter    = get_post_meta( $coupon[0]->ID, 'coupon_counter', true );

        $counter    = ( !empty( $counter ) ) ? $counter + 1 : 1;
        $is_updated = update_post_meta( $coupon[0]->ID, 'coupon_counter', $counter );

        return $counter;
    }


    public static function set_counters_cookie(){

        if ( isset( $_COOKIE['cci_counters'] ) ) {
            unset( $_COOKIE['cci_counters'] );
            setcookie( 'cci_counters', null, -1, '/' );
        };

        $counters_array = [];
        $args = array(
            'numberposts' => -1,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'post_type'   => 'coupons'
        );
        $coupons    =  get_posts( $args );

        foreach( $coupons as $coupon ){
            $counter    = get_post_meta( $coupon->ID, 'coupon_counter', true );
            $coupon_id  = get_post_meta( $coupon->ID, 'coupon_id', true );
            $counters_array[$coupon_id] = $counter;
        };
        $counters_json = json_encode( $counters_array );
        $x = setcookie( 'cci_counters', $counters_json );

    }

    public static function get_counters_json(){

        $counters_array = [];
        $args = array(
            'numberposts' => -1,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'post_type'   => 'coupons'
        );
        $coupons    =  get_posts( $args );

        foreach( $coupons as $coupon ){
            $counter    = get_post_meta( $coupon->ID, 'coupon_counter', true );
            $coupon_id  = get_post_meta( $coupon->ID, 'coupon_id', true );
            $counters_array[$coupon_id] = $counter;
        };
        $counters_json = json_encode( $counters_array );
        return $counters_json;
    }

    /**
    * Заголовок секции активных купонов с датами
    *
    */
    public static function get_active_title(){

        $options    = get_option( 'cci_options' );
        $title      = $options['active_title'];

        $this_month = date_i18n( 'F', strtotime( 'first day of this month', time() ) );
        $next_month = date_i18n( 'F', strtotime( 'first day of this month + 1 month', time() ) );

        $title = str_replace( "%%this_month%%", $this_month, $title );
        $title = str_replace( "%%next_month%%", $next_month, $title );
        $title = str_replace( "%%year%%", date_i18n( 'Y' ), $title );

        return $title;
    }

    /**
    * SEO-Заголовок страницы
    *
    */
    public static function get_seo_title(){

        $use_next_month = false;
        $term = CCI_Config::DAYS_TILL_END_MONTH;
        
        $secs_left  = strtotime( 'first day of this month + 1 month', time() ) - strtotime( 'today', time() );
        $days_left = intval( ( $secs_left ) / 86400);

        if ( $days_left <= $term ) $use_next_month = true;

        $options    = get_option( 'cci_options' );
        $title      = $options['seo_title'];

        // Если до конца месяца осталось < дней, чем укзано в $term, то указываем следующие месяцы
        $this_month = ( !$use_next_month ) ? date_i18n( 'F', strtotime( 'first day of this month', time() ) ) : date_i18n( 'F', strtotime( 'first day of this month + 1 month', time() ) );
        $next_month = ( !$use_next_month ) ? date_i18n( 'F', strtotime( 'first day of this month + 1 month', time() ) ) : date_i18n( 'F', strtotime( 'first day of this month + 2 months', time() ) );

        $title = str_replace( "%%this_month%%", $this_month, $title );
        $title = str_replace( "%%next_month%%", $next_month, $title );
        $title = str_replace( "%%year%%", date_i18n( 'Y' ), $title );

        return $title;
    }


    /**
    * HTML-код фильтра
    *
    * @param array $active_coupons - all ctive coupons
    *
    * @return String
    *
    */
    public static function get_filter( $active_coupons ){

        $options        = get_option( 'cci_options' );
        $active_show    = $options['active_show'];

        $ids            = array();
        $types          = array();
        $no_promo_ids   = array();

        $types_orig = array();


        foreach( $active_coupons as $coupon ){
            $ids[] = $coupon->ID;

            $coup_is_promo = false;
            $coupon_spec_terms = get_the_terms( $coupon->ID, 'coupon-species' );
            if( is_array( $coupon_spec_terms ) ){
                foreach( $coupon_spec_terms as $term ){
                    if ( 'promocode' == $term->slug ) {
                        $coup_is_promo = true;
                        break;
                    };
                };
            };
            if ( !$coup_is_promo ) {
                $no_promo_ids[] = $coupon->ID;
            };

        };

        $species = get_terms(array(
            'taxonomy'      => 'coupon-species',
            'hide_empty'    => true,
            'fields'        => 'all',
            'object_ids'    => $ids
        ));

        if ( !empty( $no_promo_ids ) ){

            $types_orig = get_terms(array(
                'taxonomy'      => 'coupon-types',
                'hide_empty'    => true,
                'fields'        => 'all',
                // 'object_ids'    => $ids
                'object_ids'    => $no_promo_ids
            ));
        };
        
        /* меняем местами доставку и подарок */
        if ( count( $types_orig ) < 3 ){
          $types = $types_orig;
        } else {
          $el_last        = array_splice( $types_orig, -2, 2 );
          $el_last_revers = array_reverse( $el_last );
          $types          = array_merge( $types_orig, $el_last_revers );
        };

        $template = "<nav class='coupons_active_container--filter' data-show='{$active_show}'>";
        $template .= '<div class="active_filter_item active_filter" data-filter="coupon_item">Все</div>';
        foreach( $species as $specie ){
            if( 'promocode' == $specie->slug ){
                $template .= "<div class='active_filter_item' data-filter='{$specie->slug}'>{$specie->name}</div>";
            };
        };
        foreach( $types as $type ){
                $typeslug = ( 'discount' == $type->slug ) ? 'action' : $type->slug;
                $template .= "<div class='active_filter_item' data-filter='{$typeslug}'>{$type->name}</div>";
            };
        $template .= '</nav>';
        return $template;
    }

    /**
    * Сортировка активных купонов по типам
    *
    * @return Array
    */
    public static function sort_active_coupons( $coupons ){

        // массивы купонов по типам
        $c_delivery = array();
        $c_discount = array();
        $c_gift     = array();
        $c_promocode    = array();
        $coups_not_promo = array();

        $ids        = array(); // ID всех купонов
        $ids_promo  = $ids_action = array(); // ID промо/акция купонов
        $ids_del = $ids_disc = $ids_gift = array(); // массивы ID купонов по типам
        $sorted_coupons = array(); // сортированный массив купонов

        $priority = CCI_Config::coupons_priority();

        foreach( $coupons as $coupon ){
            $ids[] = $coupon->ID;
            $coup_is_promo = false;
            $coupon_spec_terms = get_the_terms( $coupon->ID, 'coupon-species' );
            if( is_array( $coupon_spec_terms ) ){
                foreach( $coupon_spec_terms as $term ){
                    if ( 'promocode' == $term->slug ) {
                        $coup_is_promo = true;
                        break;
                    };
                };
            };
            if ( $coup_is_promo ) {
                $ids_promo[] = $coupon->ID;
            } else {
                $ids_action[] = $coupon->ID;
            };
        };

        foreach( $ids_action as $id ){
            $coupon_type_terms = get_the_terms( $id, 'coupon-types' );
            $type =  $coupon_type_terms[0]->slug;
            switch ($type) {
                case 'discount':
                    $ids_disc[] = $id;
                    break;
                case 'gift':
                    $ids_gift[] = $id;
                    break;
                case 'delivery':
                    $ids_del[] = $id;
                    break;
                default:
                    $ids_disc[] = $id;
                    break;
            };
        };


        foreach( $coupons as $coupon ){
          $c_id = intval( $coupon->ID);
          if( in_array( $c_id, $ids_promo ) ){
              $c_promocode[] = $coupon;
          } elseif ( in_array( $c_id, $ids_disc ) ) {
              $c_discount[] = $coupon;
          } elseif ( in_array( $c_id, $ids_gift ) ){
              $c_gift[] = $coupon;
          } else{
              $c_delivery[] = $coupon;
          };
        };

        $coupons_arr = array();
        foreach( $priority as $key => $value ){

            switch( $value ){
                case 'discount':
                    $coupons_arr[] = $c_discount;
                    break;
                case 'promocode':
                    $coupons_arr[] = $c_promocode;
                    break;
                case 'gift':
                    $coupons_arr[] = $c_gift;
                    break;
                case 'delivery':
                    $coupons_arr[] = $c_delivery;
                    break;
                default:
                    $coupons_arr[] = $c_discount;
                    break;
            };
        };

        // $sorted_coupons = array_merge( $coupons_arr[0], $coupons_arr[1], $coupons_arr[2], $coupons_arr[3] );

        /* убираем приоритет всех купонов кроме промокода */
        foreach( $coupons as $coupon ){
          $c_id = intval( $coupon->ID);
          if( !in_array( $c_id, $ids_promo ) ){
              $coups_not_promo[] = $coupon;
          }
        };
        $sorted_coupons = array_merge( $coupons_arr[0], $coups_not_promo );


        return $sorted_coupons;
    }

    /**
    * Возвращает объект купона по ID
    *
    * @param Int $id - Coupon ID
    * @return Object
    */
    public static function get_single_coupon( $id ){
        $args = array(
            'post_type' => 'coupons',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'       => 'coupon_id',
                    'value'     => $id,
                    'compare'   => '='
                ),
            ),
        );
        $coup = new WP_Query( $args );
        $coupon = $coup->post;
        return $coupon;
    }

    /**
    * Удаление записей из БД
    *
    */
    public static function delete_coupons(){

      $args = array(
          'post_type' => 'coupons',
          'posts_per_page' => -1,
      );

      $coupons = get_posts( $args );
      foreach( $coupons as $coupon ){
          wp_delete_post( $coupon->ID );
        };
      return true;
    }

    /**
    * Delete table from db after plugin uninstalling
    *
    * @static
    * @return void
    */
    public static function coupons_table_delete( $source_name ){
        global $wpdb;
        $table_name = 'cci_' . $source_name . '_import';
        $db_name = $wpdb->dbname;

        $cci_query = "DROP TABLE $table_name";
        $is_table = $wpdb->query( $cci_query );
    }


    /**
    * Очистка кэша
    *
    */
    public static function clear_wpfc_cache(){
        global $wpdb;
        $use_next_month = false;
        $is_cleared = 1;
        $current_month = date('n');
        $term = CCI_Config::DAYS_TILL_END_MONTH;
        
        $secs_left  = strtotime( 'first day of this month + 1 month', time() ) - strtotime( 'today', time() );
        $days_left = intval( ( $secs_left ) / 86400);

        if ( $days_left <= $term || 1 == 1) {

            // берем данные из таблицы
            $table_name =  'cci_monthly_cache';
            if ( cci_is_table_exists( $table_name ) ){
                $sql = "SELECT IsCleared FROM $table_name WHERE MonthID =  $current_month";
                $res = $wpdb->get_results( $sql );
                $is_cleared = $res[0]->IsCleared;
            };
            if( !$is_cleared ){
                if( isset( $GLOBALS['wp_fastest_cache'] ) 
                    && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')){
                    $GLOBALS['wp_fastest_cache']->deleteCache(true);
                };
                // сохраняем в таблице
                $result_ = $wpdb->update($table_name,array('IsCleared' => 1),array('MonthID' => $current_month));
            };
        };
    }

    public static function cache_table_create(){
        global $wpdb;
        $table_name = 'cci_monthly_cache';
        $db_name = $wpdb->dbname;
        
        $is_table = cci_is_table_exists( $table_name );

        if( !$is_table ){
            $create_sql = "CREATE TABLE $table_name (
                            ID int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            MonthID int(11),
                            IsCleared int(11)
                      ) CHARACTER SET utf8 COLLATE utf8_general_ci";
            $result_ = $wpdb->query( $create_sql );

            // начальные значения
            for ( $i = 1; $i<13; $i++ ){
                $data = array(
                    'MonthID'   => $i,
                    'IsCleared' => 0
                );
                $result = $wpdb->insert( $table_name, $data );
            };
        };
    }
    
    public static function clear_monthly_cache_index(){
          global $wpdb;
        $table_name = 'cci_monthly_cache';
        $db_name = $wpdb->dbname;
        $is_table = cci_is_table_exists( $table_name );
        
        for ( $i = 1; $i<13; $i++ ){
               $data = array(
                  'MonthID'   => $i,
                  'IsCleared' => 0
              );
         $result_ = $wpdb->update($table_name,array('IsCleared' => 0),array('MonthID' => $i));
        };
    }
   


}
