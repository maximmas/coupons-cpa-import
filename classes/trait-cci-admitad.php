<?php


trait Admitad
{
/**
    * Main importer function
    * Calls by Cron or AJAX
    *
    * @static
    * @param String $call_type how this method was called 
    * @return void
    */
    private static function admitad_importer( $call_type = "cron" ){
    
        global $wpdb;
                    
        $table_name     = 'cci_admitad_import';
        $options        = get_option( 'cci_options' );
        $link           = $options['admitad_xml'];
        
        self::$default_text  = $options['admitad_text'];
        
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
        if( !array_key_exists( 'coupons', $result ) ) {
            // what if coupons are in array with another key ?
            self::coupons_prolongation();
            return true;
        };
        
        // save data to the intermediate table
        foreach ( $result['coupons']['coupon'] as $coupon ){
            
            $data = self::adm_get_coupon_data( $coupon ); 
            $result = $wpdb->insert( $table_name, $data );
        };

        // create/update custom post types 
        self::daily_update_coupons( CCI_ADMITAD_DB );
        
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

     private static function adm_get_coupon_data( $coupon ){
        $types  = '';
        $text   = ( isset( $coupon['description'] ) ) ? $coupon['description'] : '';
          
        if ( isset( $coupon['specie_id'] ) ){
                switch ( (int)$coupon['specie_id'] ) {
                    case 1:
                        $species = 'promocode';
                        break;
                    case 2:
                        $species = 'action';
                        break;
                    default:
                        $species = 'action';
                };
            } else{
                $species = 'action';
            };

            if ( isset( $coupon['types'] ) ){
                $type_ids = array();
                foreach( $coupon['types'] as $type_id ){
                    // if coupon has multiple types
                    if ( is_array( $type_id ) ){
                        foreach( $type_id as $id ){
                            $type_ids[] = self::adm_get_type_slug( $id );
                        };

                        // choose one type with priority
                        if ( in_array( 'delivery', $type_ids ) ){
                            $types = 'delivery';    
                        } elseif ( in_array( 'discount', $type_ids ) ) {
                            $types = 'discount';
                        } else{
                            $types = 'gift';
                        };
                    } else {
                        $types = self::adm_get_type_slug( $type_id );
                    };
                };
            }else{
                $types = 'discount';
            };
            
            // column / value
            $data = array( 
                'CouponID'      => $coupon['@attributes']['id'],
                'Title'         => $coupon['name'],
                'Description'   => $text,
                'PromoCode'     => $coupon['promocode'],
                'GotoLink'      => $coupon['gotolink'],
                'PromoLink'     => $coupon['promolink'],
                'Species'       => $species,
                'Types'         => $types,
                'Discount'      => $coupon['discount'],
                'DateStart'     => $coupon['date_start'],
                'DateEnd'       => $coupon['date_end']
            );

            return $data;
    }

    private static function adm_get_type_slug( $id ){
        $type_slug = '';
        switch ( (int)$id ) {
            case 1:
                $type_slug = 'delivery';
                break;
            case 2:
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