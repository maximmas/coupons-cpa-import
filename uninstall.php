<?php

//if uninstall/delete not called from WordPress exit
if( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit ();

/*
* очитска БД 
* !!! включить в production

require_once ( plugin_dir_path( __FILE__ ) . '/classes/class-cci-helpers.php' );

CCI_Helpers::delete_coupons();

CCI_Helpers::coupons_table_delete( 'admitad' );
CCI_Helpers::coupons_table_delete( 'actionpay' );

wp_clear_scheduled_hook( 'cci_cron_import' );
*/