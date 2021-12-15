jQuery(document).ready(function ($) {

  var $inline_editor = inlineEditPost.edit;

  inlineEditPost.edit = function (id) {

    $inline_editor.apply(this, arguments);

    var post_id = 0;
    if (typeof (id) == 'object') {
      post_id = parseInt(this.getId(id));
    }

    if (post_id != 0) {
      $row = $('#edit-' + post_id);

      $coupon_promocode = $('#coupon_promocode_' + post_id).text();
      $coupon_discount = $('#coupon_discount_' + post_id).text();
      $coupon_counter = $('#coupon_counter_' + post_id).text();
      $coupon_date_start = $('#coupon_date_start_' + post_id).text();
      $coupon_date_end = $('#coupon_date_end_' + post_id).text();
      $coupon_promo_link = $('#coupon_promolink_' + post_id).text();
      $coupon_goto_link = $('#coupon_gotolink_' + post_id).text();
      $coupon_prolong = $('#coupon_prolong_' + post_id).text();

      $row.find('#coupon_promocode').val($coupon_promocode);
      $row.find('#coupon_discount').val($coupon_discount);
      $row.find('#coupon_counter').val($coupon_counter);
      $row.find('#coupon_date_start').val($coupon_date_start);
      $row.find('#coupon_date_end').val($coupon_date_end);
      $row.find('#coupon_promolink').val($coupon_promo_link);
      $row.find('#coupon_gotolink').val($coupon_goto_link);

      if ('да' == $coupon_prolong) {
        $row.find('#coupon_prolong').prop('checked', true);
      } else {
        $row.find('#coupon_prolong').prop('checked', false);
      }

    }

  }

});