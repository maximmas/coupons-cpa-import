<?php 

// Шаблон купона

trait Coupon
{
    
    public function get_coupon_template(){
		
		$template = '<article class="coupon_item coupon_active_item %15$s"
        				data-promocode="%1$s"
        				data-promolink="%2$s"
        				data-gotolink="%3$s"
        				data-coupid="%4$s"
						data-postid="%5$s">
            			<div class="coupon_item_left">
							<div class="coupon_item_left_clickable">
								%6$s
								<div class="coupon_item_left--icon %7$s">
									<span class="%8$s"></span>
								</div>
						<div class="coupon_item_left--type">%9$s</div>
					</div>
				</div>
				<div class="coupon_item_center">
					<header class="coupon_item_center--title">
						%10$s
					</header>
					<span class="coupon_item_center--title--rest"></span>
					<div class="coupon_item_center--desciption">
						<div class="coupon_item_center--content">
							<div class="coupon_item_center--excerpt">
								%11$s
							</div>
							<span class="coupon_item_center--fulltext hidetext"></span>
						</div>	
						<div class="coupon_item_center--toggle">
							<i class="far fa-plus-square"></i>
						</div>
					</div>	
				</div>
				<div class="coupon_item_right">
					<div class="coupon_item_right--container">
						%12$s
						<div class="coupon_item_right--arrow">
							<i class="far fa-angle-right"></i>
						</div>
					</div>
				</div>
				<div class="coupon_item_bottom">
						<div class="coupon_item_bottom--counter">
							<i class="fa fa-eye" aria-hidden="true"></i>
							Воспользовались 
							<span class="counter_times">%13$s</span>
						</div>
						<div class="coupon_item_bottom--term">
								<i class="fa fa-history" aria-hidden="true"></i>
								<span class="counter_days">%14$s</span>
						</div>
					</div>
				</article>';

		return $template;
    }

    




    
}