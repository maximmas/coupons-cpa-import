<?php
// Importing data from CPA networks

require_once ( plugin_dir_path( __FILE__ ) . '/trait-cci-admitad.php');
require_once ( plugin_dir_path( __FILE__ ) . '/trait-cci-actionpay.php');


abstract class CCI_Importer
{

    use Admitad;
    use Actionpay;


    /**
     * Default content text for coupons which haven't it
     */
    private static $default_text;


    public static function run_import(  $call_type = "cron" ){

        self::admitad_importer('cron');
        self::actionpay_importer('cron');
    }

    /**
    * Update coupons CPT from import table
    * Calls from *_importer methods
    *
    * @static
    * @param String $cpa_type where get data from
    * @return void
    */
    private static function daily_update_coupons( $cpa_type = 'CCI_ADMITAD_DB' ){
            global $wpdb;

            // get all imported coupons from DB
            $rows = $wpdb->get_results( "SELECT * FROM $cpa_type" );
            if ( $rows ){
                foreach( $rows as $row ){

                    // setup enddate
                    if( '0000-00-00' != $row->DateEnd && !is_null($row->DateEnd) ){
                        $dateEnd        = $row->DateEnd;
                        $prolongation   = '';
                    } else {
                        $dateEnd        = date_i18n( 'Y-m-d', strtotime( 'first day of this month + 1 month', time() ) );
                        $prolongation   = 1;
                    };

                    // get post coupon with the same ID
                    $args = array(
                        'post_type' => 'coupons',
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            array(
                                'key'       => 'coupon_id',
                                'value'     => $row->CouponID,
                                'compare'   => '='
                            ),
                        ),
                    );
                    $coup = new WP_Query( $args );
                    $coupon = $coup->post;

                    // if such post exists already
                    if( $coupon && !wp_is_post_revision( $coupon->ID ) ){

                        // update post here
                        update_post_meta( $coupon->ID, 'coupon_date_end', $dateEnd );
                        update_post_meta( $coupon->ID, 'coupon_prolong', $prolongation );

                    } else {
                        // create new post
                        $text       = ( '' == $row->Description ) ? self::$default_text : $row->Description;
                        $post_data  = array(
                            'post_title'    => $row->Title,
                            'post_content'  => $text,
                            'post_status'   => 'publish',
                            'post_author'       => 1,
                            'post_category'     => array( 8,39 ),
                            'comment_status'    => 'closed',
                            'ping_status'       => 'closed',
                            'post_type'         => 'coupons',
                        	'meta_input'    => array(
                                 'coupon_id'            => $row->CouponID,
                                 'coupon_promocode'     => $row->PromoCode ,
                                 'coupon_promolink'     => $row->PromoLink,
                                 'coupon_gotolink'      => $row->GotoLink,
                                 'coupon_date_start'    => $row->DateStart,
                                 'coupon_date_end'      => $dateEnd,
                                 'coupon_discount'      => $row->Discount,
                                 'coupon_counter'       => 0,
                                 'coupon_prolong'       => $prolongation
                            ),
                        );
                        $post_id    = wp_insert_post( $post_data );

                        // add taxonomy to created post
                        $species    = $row->Species;
                        $types      = $row->Types;
                        wp_set_object_terms( $post_id, $species, 'coupon-species', false );
                        wp_set_object_terms( $post_id, $types, 'coupon-types', false );
                    };
                };
            };
    }

   /**
    * Create table in db for imported from CPA data
    *
    * @static
    * @return void
    */
    public static function coupons_table_create( $source_name ){
        global $wpdb;
        $table_name = 'cci_' . $source_name . '_import';
        $db_name = $wpdb->dbname;

        $sql = "SELECT COUNT(*)
                        FROM information_schema.tables
                        WHERE TABLE_SCHEMA = '$db_name'
                        AND TABLE_NAME = '$table_name'";

        $is_table = $wpdb->get_var($sql);

        if( !$is_table ){
            $create_sql = "CREATE TABLE $table_name (
                            ID int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            CouponID int(11),
                            Title text,
                            Description text,
                            Species varchar(255),
                            Types  varchar(255),
                            PromoCode varchar(255),
                            Discount varchar(255),
                            DateStart Date,
                            DateEnd Date,
                            GotoLink varchar(255),
                            PromoLink varchar(255)
                ) CHARACTER SET utf8 COLLATE utf8_general_ci";
            $result_ = $wpdb->query( $create_sql );
        };
    }



    /**
    * Function for prolongation coupons which have prolongation option checked
    *
    * @static
    * @return void
    */
    public static function coupons_prolongation(){

        $args = array(
            'post_type' => 'coupons',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key'       => 'coupon_prolong',
                    'value'     => '1',
                    'compare'   => '='
                ),
            ),
        );
        $coups = new WP_Query( $args );
        $coupons = $coups->posts;
        foreach ( $coupons as $coupon ){
            $end_date               = get_post_meta( $coupon->ID, 'coupon_date_end' );
            $end_date_timestamp     = strtotime( $end_date[0] );
            $current_date_timestamp = (int)current_time( 'timestamp' );
            if( $end_date_timestamp < $current_date_timestamp ){
                $dateEnd = date_i18n( 'Y-m-d', strtotime( 'first day of this month + 1 month', time() ) );
                update_post_meta( $coupon->ID, 'coupon_date_end', $dateEnd );
            };
        };
    }

    /**
    * Function for prolongation coupons which have prolongation option checked
    *
    * @static
    * @return void
    */
    public static function coupons_set_valid_meta(){
        
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
            $is_valid = ( $date_end >= $date_today ) ? 1 :0;
            update_post_meta( $post->ID, 'coupon_valid', $is_valid );
        };


        
    }

}
