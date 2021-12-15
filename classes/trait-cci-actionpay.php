<?php


trait Actionpay
{
/**
    * Main importer function
    * Calls by Cron or AJAX
    *
    * @static
    * @param String $call_type how this method was called 
    * @return void
    */
    private static function actionpay_importer( $call_type = "cron" ){
      
        global $wpdb;
                    
        $table_name     = 'cci_actionpay_import';
        $options        = get_option( 'cci_options' );
        $link           = $options['actionpay_xml'];
        
        self::$default_text  = $options['actionpay_text'];
        
        // if link no stored -> stop import
        if( $link == '' ) {
            // what if coupons are in array with another key ?
            self::coupons_prolongation();
            self::coupons_set_valid_meta();
            return true;
        };


        // clear old data
        $delete = $wpdb->query( "DELETE FROM $table_name" );
        
        // get new data
        $xml    = simplexml_load_file( $link ); // get XML
        $json   = json_encode( $xml ); // convert XML into JSON
        $result = json_decode( $json, TRUE ); // convert JSON into associative array
        
        // if coupons array doesn't exist -> stop import
        if( !array_key_exists( 'promotion', $result ) ) {
            // what if coupons are in array with another key ?
            
            self::coupons_prolongation();
            return true;
        };
        
        // save data to the intermediate table
        foreach ( $result['promotion'] as $coupon ){
            
            $data = self::act_get_coupon_data( $coupon );
            $result = $wpdb->insert( $table_name, $data );
        };

        // create/update custom post types 
        self::daily_update_coupons( 'cci_actionpay_import' );
        
        // prolongation
        self::coupons_prolongation();

        self::coupons_set_valid_meta();

        // exit 
        if ( "ajax" == $call_type ){
            echo 1;
            wp_die(); 
        } else {
            return true;
        };
        
    }

     private static function act_get_coupon_data( $coupon ){
        $types  = '';
        $text   = ( isset( $coupon['description'] ) ) ? $coupon['description'] : '';
        
        if ( is_string( $coupon['code'] ) ){
          $species = 'promocode';
          $promocode = $coupon['code'];
        } else {
          $species = 'action';
          $promocode = 'Не нужен';
        };
        
        if ( isset( $coupon['type_id'] ) ){
            $types = self::act_get_type_slug( $coupon['type_id'] );
        };

            // column / value
            $data = array( 
                'CouponID'      => $coupon['id'],
                'Title'         => $coupon['title'],
                'Description'   => $text,
                'PromoCode'     => $promocode,
                // 'GotoLink'      => $coupon['landing'],
                // 'PromoLink'     => $coupon['landing'],
                'GotoLink'      => $coupon['link'],
                'PromoLink'     => $coupon['link'],
                'Species'       => $species,
                'Types'         => $types,
                'Discount'      => '',
                'DateStart'     => $coupon['begin_date'],
                'DateEnd'       => $coupon['end_date']
            );

            return $data;
    }

    private static function act_get_type_slug( $id ){
        $type_slug = '';
        switch ( (int)$id ) {
            case 1:
                $type_slug = 'delivery';
                break;
            case 4:
                $type_slug = 'discount';
                break;
            case 3:
                $type_slug = 'gift';
                break;
            default:
                $type_slug = 'discount';
        };

        return $type_slug;
    }
    
}