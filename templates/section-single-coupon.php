<?php

// single coupons section
// [coupon id="xxx"]
?>

<div id="single_resizer"></div>
<script>

            var media_style_s = 'lg';

            // get width without paddings
            var resizer_s = window.getComputedStyle( document.getElementById("single_resizer"), null );
            var width_s = parseInt( resizer_s.getPropertyValue("width") );

            if ( width_s < 290 ) media_style_s = 'exs';
            if ( width_s >= 290 && width_s < 400 ) media_style_s = 'xxxs';

            if ( width_s >= 400 && width_s < 500 ) media_style_s = 'xxs';
            if ( width_s >= 500 && width_s < 790 ) media_style_s = 'xs';
            if ( width_s >= 790 && width_s < 870 ) media_style_s = 'sm';
            if ( width_s >= 870 && width_s < 940 ) media_style_s = 'md';
            if ( width_s >= 940 ) media_style_s = 'lg';
            var style_file_s = "/wp-content/plugins/coupons-cpa-import/assets/css/media_" + media_style_s + "_style.css";
            var styler_s = document.createElement("link");
            styler_s.type = "text/css";
            styler_s.rel  = "stylesheet";
            styler_s.id	= "media_" + media_style_s + "_style";
            styler_s.href = style_file_s;
            document.getElementsByTagName("head")[0].appendChild(styler_s);
        </script>


<section class="coupons_active_container coupon_single_container">
<?php
    $coupon = CCI_Helpers::get_single_coupon( $coup_id );
    ( new CCI_Coupon( $coupon ) )->render();
?>
</section>
