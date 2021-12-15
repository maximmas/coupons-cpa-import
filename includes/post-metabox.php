<div class="form-field">
<input type="hidden" name="coupon_nonce" id="coupon_nonce" value="<?php echo $data['coupon_nonce']; ?>" />
</div>
<br>
<!-- <div class="form-field">
	<label for="coupon_short_name"><div class="dashicons dashicons-edit"></div> Краткое название купона</label>
	<input name="coupon_short_name" id="coupon_short_name" type="text" value="<?php /*echo $data['coupon_short_name']; */?>" size="40">
</div> -->
<!-- <br> -->
<div class="form-field">
	<label for="coupon_id"><div class="dashicons dashicons-admin-post"></div> Id купона</label>
	<input name="coupon_id" id="coupon_id" type="text" value="<?php echo $data['coupon_id']; ?>" size="40">
</div>
<br>
<div class="form-field">
	<label for="coupon_promocode"><div class="dashicons dashicons-admin-network"></div> Промокод</label>
	<input name="coupon_promocode" id="coupon_promocode" type="text" value="<?php echo $data['coupon_promocode']; ?>" size="40">
</div>
<br>
<div class="form-field">
	<label for="coupon_promolink"><div class="dashicons dashicons-admin-links"></div> Промо ссылка купона</label>
	<input name="coupon_promolink" id="coupon_promolink" type="text" value="<?php echo $data['coupon_promolink']; ?>" size="40">
</div>
<br>
<div class="form-field">
	<label for="coupon_gotolink"><div class="dashicons dashicons-admin-links"></div> Партнерская ссылка для слива траффика</label>
	<input name="coupon_gotolink" id="coupon_gotolink" type="text" value="<?php echo $data['coupon_gotolink']; ?>" size="40">
</div>
<br>
<div class="form-field">
	<label for="coupon_discount"><div class="dashicons dashicons-megaphone"></div> Скидка</label>
	<input name="coupon_discount" id="coupon_discount" type="text" value="<?php echo $data['coupon_discount']; ?>" size="40">
</div>
<br>
<div class="form-field">
	<label for="coupon_counter"><div class="dashicons dashicons-visibility"></div> Количество просмотров</label>
	<input name="coupon_counter" id="coupon_counter" type="text" value="<?php echo $data['coupon_counter']; ?>" size="40">
</div>
<br>
<div class="form-field">
	<label for="coupon_date_start"><div class="dashicons dashicons-clock"></div> Дата начала действия купона</label>
	<input name="coupon_date_start" id="coupon_date_start" type="date" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php echo $data['coupon_date_start']; ?>" size="40">
</div>
<br>
<div class="form-field">
	<label for="coupon_date_end"><div class="dashicons dashicons-clock"></div> Дата окончания действия купона</label>
	<input name="coupon_date_end" id="coupon_date_end" type="date" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="<?php echo $data['coupon_date_end']; ?>" size="40">
</div>
<br>
<div class="form-field">
	<label for="coupon_prolong">
		<input	name="coupon_prolong" 
				id="coupon_prolong"
				type="checkbox"
				value="1"
				<?php checked( 1, $data['coupon_prolong'] ); ?>
				>
		Автопродление купона
	</label>
</div>
