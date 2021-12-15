<?php
// Magnific Popup Modal новая версия
?>

<div id="cci_modal" class="cci_modal white-popup mfp-hide">
	<section class="modal_container">
      <article class="modal_action">
				<div class="modal_subtitle">
						Перейдите на сайт и используйте промокод.
				</div>
				<div class="modal_action_wrapper">
			    <div class="modal_code_container">
			        <div class="modal_code--value"></div>
				        	<div class="modal_code--action"
					            data-clipboard-action="copy"
					            data-clipboard-target=".modal_code--value">
					            <i class="far fa-copy"></i>Скопировать
					        </div>
					    </div>
					    <div class="modal_goto">
					        <a href="" target="_blank">Перейти в магазин</a>
						 </div>
				</div>
      </article>
	</section>
	<div class="modal_cashback">
			<?php
				$options = get_option( 'cci_options' );
				$shortcode = $options['modal_shortcode'];
				if ( !empty( $shortcode ) ) {
					echo do_shortcode( $shortcode );
				};
			?>
	</div>
</div>
