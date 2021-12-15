<?php

// active coupons section
// [coupons type="active"]
?>

<div id="active_resizer"></div>
<script>
            let media_style = 'lg';

            // get width without paddings
            var resizer = window.getComputedStyle( document.getElementById("active_resizer"), null );
            var width = parseInt( resizer.getPropertyValue("width") );

            if ( width < 319 ) media_style = 'exs';
            if ( width >= 319 && width < 409 ) media_style = 'xxxs';
            if ( width >= 409 && width < 529 ) media_style = 'xxs';
            if ( width >= 529 && width < 715 ) media_style = 'xs';
            if ( width >= 715 && width < 899 ) media_style = 'sm';
            if ( width >= 899 && width < 969 ) media_style = 'md';
            if ( width >= 969 ) media_style = 'lg';

            let style_file = "/wp-content/plugins/coupons-cpa-import/assets/css/media_" + media_style + "_style.css";
            var styler = document.createElement("link");
            styler.type = "text/css";
            styler.rel  = "stylesheet";
            styler.id	= "media_" + media_style + "_style";
            styler.href = style_file;
            document.getElementsByTagName("head")[0].appendChild(styler);


</script>

<section class="coupons_active_container">
<?php

$coupons        = CCI_Helpers::get_active_coupons();
$coupons        = CCI_Helpers::sort_active_coupons( $coupons );
$options        = get_option( 'cci_options' );
$to_show        = (int)$options['active_show'];
$adv_order      = (int)$options['active_adv'];
$total_coupons  = count( $coupons );
$opened_coupons = $hidden_coupons = array();




if ( ( $total_coupons - $to_show ) > 0 ){
    // если купонов больше
    $index_show     = $to_show;
    $index_hidden   = $total_coupons - $to_show;
    $opened_coupons = array_slice( $coupons, 0, $index_show );
    $hidden_coupons = array_slice( $coupons, $index_show );
} else {
    // если купонов меньше
    $index_show     = $total_coupons;
    $index_hidden   = 0;
    $opened_coupons = $coupons;
};

?>
	<header class="coupons_active_container--title">
		<h1 class="h1">
           <?php echo ( CCI_Helpers::get_active_title() ); ?>
        </h1>
	</header>

    <?php

    echo ( CCI_Helpers::get_filter( $coupons ) );

    $i = 0;
    foreach ( $opened_coupons as $o_coupon ){
        ( new CCI_Coupon( $o_coupon ) )->render();
        if ( $i == ( $adv_order - 1 ) && 0 != $adv_order) {
            echo adv_active_block( $adv_order );
        }
        $i++;
    }

    if ( $index_hidden ){

        echo '<div class="coupons_active_additional_container">';
        foreach ( $hidden_coupons as $h_coupon ){
            ( new CCI_Coupon( $h_coupon ) )->render();
        };
        echo '</div><div class="coupons_active_loadmore">Показать еще!</div>';
     };

?>
</section>
