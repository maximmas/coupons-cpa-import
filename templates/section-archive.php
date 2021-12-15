<?php
// expired coupons section
// [coupons type="archive"]
?>

<div id="archive_resizer"></div>
<script>
            // let width_ = document.getElementById('archive_resizer').parentNode.offsetWidth;
            let media_style_ = 'lg';

               // get width without paddings
            var resizer_ = window.getComputedStyle( document.getElementById("archive_resizer"), null );
            var width_ = parseInt( resizer_.getPropertyValue("width") );
            
            if ( width_ < 290 ) media_style_ = 'exs';
            if ( width_ >= 290 && width_ < 400 ) media_style_ = 'xxxs';

            if ( width_ >= 400 && width_ < 500 ) media_style_ = 'xxs';
            if ( width_ >= 500 && width_ < 550 ) media_style_ = 'xs';
            if ( width_ >= 550 && width_ < 870 ) media_style_ = 'sm';
            if ( width_ >= 870 && width_ < 940 ) media_style_ = 'md';
            if ( width_ >= 940 ) media_style_ = 'lg';

			let style_el_ = document.getElementById( "media_" + media_style_ + "_style" );
			if( null == style_el_ ){
				let style_file_ = "/wp-content/plugins/coupons-cpa-import/assets/css/media_" + media_style_ + "_style.css";
				var styler_	= document.createElement("link");
				styler_.type = "text/css";
				styler_.rel	= "stylesheet";
				styler_.id	= "media_" + media_style_ + "_style";
				styler_.href = style_file_;
                document.getElementsByTagName("head")[0].appendChild(styler_);
                
			};
        </script>

	<section class="coupons_expired_container">
        <?php $options    = get_option( 'cci_options' ); ?>
		<header class="coupons_expired_container--title">
            <h2>
                <?php echo ( $options['archive_title'] ); ?>
            </h2>
		</header>

<?php 

	// how many coupons show after page loading
    $to_show = (int)$options['archive_show'];

    // how many coupons show after each loader click
    $part = (int)$options['archive_load'];
       
    $coupons         = CCI_Helpers::get_expired_coupons();
    $total_coupons   = count( $coupons );
    $opened_coupons  = array();
    $is_loader = 1;

    if ( ( $total_coupons - $to_show ) > 0 ){
        // if a quantity of coupons is more
        $opened_coupons = array_slice( $coupons, 0, $to_show );
    } else {
        // if a quantity of coupons is less
        $opened_coupons = $coupons;
        $is_loader = 0;
    };

    foreach ( $opened_coupons as $opened_coupon ){ 
		$coupon =  new CCI_Coupon( $opened_coupon );
		?>
		<div class="archive_item_wrapper">
			<div class="archive_item_wrapper--handler"> 
				<header class="archive_item_wrapper--title">
					<?php echo $coupon->get_title(); ?>
				</header>
				<div class="archive_item_wrapper--toggle">
					<i class="far fa-caret-square-down"></i>
				</div>
			</div>
			<?php $coupon->render(); ?>
		</div>
	<?php
	};

    if ( $is_loader ) { ?>
    <!-- loader -->
		<div class="coupons_expired_loadmore" 
            data-index="<?php echo $to_show; ?>" 
            data-part="<?php echo $part; ?>"
            data-total="<?php echo $total_coupons; ?>">
			Показать больше...
		</div>
    <?php
    }
    ?>
	</section>