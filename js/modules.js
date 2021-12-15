jQuery(function ($) {


  window.cciInsertCounters = function (counters_json, clicked_coupon_id, clicked_coupon_counter) {
    var counters = JSON.parse(counters_json);
    if (counters) {
      let coupons = $('.coupon_active_item');
      for (var coupon_id in counters) {
        let counter_value = parseInt(counters[coupon_id]);
        coupons.each(function () {

          // change counter in clicked coupon only
          if (clicked_coupon_id && $(this).data('coupid') == clicked_coupon_id) {
            let counter_el = $(this).find('.counter_times');
            counter_el.text(clicked_coupon_counter);
            return true;
          };

          if ($(this).data('coupid') == coupon_id) {
            let counter_el = $(this).find('.counter_times');
            let old_counter = parseInt(counter_el.text());
            if (old_counter != counter_value) {
              counter_el.fadeOut().text(counter_value).fadeIn();
            };
          };


        });
      }
    } else {
      // do something
    };
  };

  window.cciCouponsAdjustment = function (container) {
    let coupons;
    switch (container) {
      case 'all':
        coupons = $('.coupons_active_container, .coupon_single_container ').find('.coupon_item:visible');
        break;
      case 'extra':
        coupons = $('.coupons_active_container .coupons_active_additional_container').find('.coupon_item');
        break;
      default:
        coupons = $('.coupons_active_container ').find('.coupon_item:visible');
    };
    // heights / widths adjustment
    coupons.each(function () {
      let discount = $(this).find('.coupon_item_left--discount'),
        l_block = $(this).find('.coupon_item_left'),
        r_block_height = $(this).find('.coupon_item_right').height(),
        c_text = $(this).find('.coupon_item_center--content'),
        c_width = c_text.width(),
        toggler = $(this).find('.coupon_item_center--toggle');

      if (cciTextCutter($(this))) {
        toggler.hide();
      };

      // discount size
      if (discount.width() > l_block.width()) {
        discount.hide();
        $(this).find('.coupon_item_left--icon').addClass('no_value');
      };

      // right block
      $(this).find('.coupon_item_right--container').height(r_block_height);



    });
  };

  window.cciTextCutter = function (coupon) {
    let short_text = coupon.find('.coupon_item_center--excerpt'),
      full_text = coupon.find('.coupon_item_center--fulltext'),
      short_title = coupon.find('.coupon_item_center--title'),
      full_title = coupon.find('.coupon_item_center--title--rest'),
      text_words = short_text.text().split(" "),
      title_words = short_title.text().split(" "),
      is_text_fitted,
      is_title_fitted,
      text_rest = [],
      title_rest = [];

    // text cutting
    is_text_fitted = (short_text.prop('offsetHeight') < short_text.prop('scrollHeight')) ? false : true;


    if (!is_text_fitted) {
      while (short_text.prop('scrollHeight') > short_text.prop('offsetHeight')) {
        var aa = text_words.pop();
        text_rest.unshift(aa);
        short_text.text(text_words.join(" ") + "... ");
      };
      full_text.text(text_rest.join(" "));
    };

    // title cutting
    is_title_fitted = (short_title.prop('offsetHeight') < short_title.prop('scrollHeight')) ? false : true;
    if (!is_title_fitted) {

      while (short_title.prop('scrollHeight') > short_title.prop('offsetHeight')) {
        var bb = title_words.pop();
        title_rest.unshift(bb);
        short_title.text(title_words.join(" ") + "... ");
      };
      full_title.text(title_rest.join(" "));
    };

    is_text_fitted = (full_text.text().length > 0) ? false : true;
    return is_text_fitted;
  };

  // archive encounter
  // deactivation of last coupon
  window.cciArchiveEncounter = function () {
    let cont = $('.coupons_expired_container');
    let coups = cont.find('.archive_item_wrapper'), // total expired coupons
      archive_loader = $('.coupons_expired_loadmore'),
      archive_index = archive_loader.data('index'), // how many coupons to show
      total = archive_loader.data('total'); // total expired coupons ( == 'coups' )

    if (coups.length > 5 && total > archive_index) {
      let last_coup = coups.slice(-1);
      last_coup
        .css('opacity', '0.5')
        .find('.archive_item_wrapper--title')
        .addClass('arc_disabled')
        .css({ 'text-decoration': 'none', 'cursor': 'inherit' });

      let last_toggler = last_coup.find('.archive_item_wrapper--toggle');
      last_toggler
        .addClass('arc_disabled')
        .hover(
          function () {
            $(this).css({ 'color': '#bbb', 'cursor': 'inherit' })
          },
          function () { }
        );
    };
  };

  window.cciCounterHandler = function () {

    let coupon = $(this).parents('.coupon_item'),
      coupon_id = coupon.data('coupid'),
      coupon_counter = coupon.find('.counter_times');

    let is_unique_click = cciCheckUniqueClick(coupon_id);

    // if click is unique - send request for counter increment
    if (is_unique_click) {
      cciCounterIncreaser(coupon_id, coupon_counter);
    };

    let coupon_counter_value = parseInt(coupon_counter.text());
    coupon_counter_value = (is_unique_click) ? coupon_counter_value + 1 : coupon_counter_value;

    let promo = coupon.data('promocode'),
      text = coupon.find('.coupon_item_center--content').text(),
      title = coupon.find('.coupon_item_center--title').text(),
      title_rest = coupon.find('.coupon_item_center--title--rest').text(),
      gotolink = coupon.data('gotolink'),
      promolink = coupon.data('promolink'),
      is_promo = (coupon.hasClass('promocode')) ? 1 : 0;

    text = text.replace('...', '');
    title = title.replace('...', '');
    title = title + title_rest;

    let coup_html = coupon[0].outerHTML;

    var coup_obj = {
      promo: promo,
      title: title,
      link: gotolink,
      text: text,
      coupon_id: coupon_id,
      coupon_counter: coupon_counter_value,
      is_promo: is_promo,
      modal_coupon: coup_html,
    };

    localStorage.cci_coupon = JSON.stringify(coup_obj);
    let current_page = window.location.href;

    window.open(current_page, '');
    window.open(gotolink, '_self');

  };

  window.cciCheckUniqueClick = function (coupon_id) {
    let cookieBlockedName = 'cci_' + coupon_id;
    let is_blocked = getCookie(cookieBlockedName);
    if (null == is_blocked) {
      let options = {
        path: '/',
        expires: 86400 // 24h
      };
      setCookie(cookieBlockedName, true, options);
      return true;
    } else {
      return false;
    };
  };

  window.cciCounterIncreaser = function (coupon_id, coupon_counter) {
    let data = {
      action: 'counterinc',
      data: coupon_id
    };
    $.ajax({
      url: CCI_AjaxHandler.cci_ajaxurl,
      type: 'POST',
      data: data,
      success: function (resp) {
        coupon_counter.text(resp);
      },
      error: function (resp) {
      }
    });
  };

  // display popup
  window.cciModalInit = function (coupon) {

    $.magnificPopup.open({
      fixedContentPos: true,
      items: {
        src: $('#cci_modal'),
        type: 'inline'
      },
      callbacks: {
        beforeOpen: function () {
          $('.cci_modal .modal_container').css('display', 'flex').prepend(coupon.modal_coupon);

          // modal
          cciModalTextJoin();
          // modalTextCutter(modal);
          // if ( modalTextCutter() ) {
          // 	modal_toggler.hide();
          // };
          if (0 == coupon.is_promo) {
            $('.modal_code--action').hide();
            $('.modal_code--value').text('Промокод не требуется');
            $('.modal_code_container .modal_code--value').addClass('modal-value-noaction');
          } else {
            $('.modal_code_container .modal_code--value').removeClass('modal-value-noaction');
            $('.modal_code--value').text(coupon.promo);
          };

          // $('.modal_title').find('h2').text(coupon.title);
          // $('.modal_desc').text(coupon.text);
          $('.modal_goto').find('a').attr('href', coupon.link);
          var c = $('.cci_modal .modal_container');
          cciTextCutter(c);
        },
        open: function () {
          //modalTextCutter();

          // var c = $('.cci_modal .modal_container');
          cciModalTextCutter();
          //modalTextCutter();
        }
      },


    }, 0);
  };

  window.cciModalTextCutter = function () {

    let modal = $('.cci_modal .modal_container'),
      short_text = modal.find('.coupon_item_center--excerpt'),
      full_text = modal.find('.coupon_item_center--fulltext'),
      short_title = modal.find('.coupon_item_center--title'),
      full_title = modal.find('.coupon_item_center--title--rest'),
      modal_toggler = modal.find('.coupon_item_center--toggle'),
      text_words = short_text.text().split(" "),
      title_words = short_title.text().split(" "),
      is_text_fitted,
      is_title_fitted,
      text_rest = [],
      title_rest = [];

    // text cutting
    is_text_fitted = (short_text.prop('offsetHeight') < short_text.prop('scrollHeight')) ? false : true;

    if (!is_text_fitted) {
      while (short_text.prop('scrollHeight') > short_text.prop('offsetHeight')) {
        let t = text_words.pop();
        text_rest.unshift(t);
        short_text.text(text_words.join(" ") + "... ");
      };
      full_text.text(text_rest.join(" "));
    };

    // title cutting
    var oH = short_title.prop('offsetHeight');
    var sH = short_title.prop('scrollHeight');
    is_title_fitted = (short_title.prop('offsetHeight') < short_title.prop('scrollHeight')) ? false : true;
    if (!is_title_fitted) {
      while (short_title.prop('scrollHeight') > short_title.prop('offsetHeight')) {
        var bb = title_words.pop();
        title_rest.unshift(bb);
        short_title.text(title_words.join(" ") + "... ");
        oH = short_title.prop('offsetHeight');
        sH = short_title.prop('scrollHeight');
      };
      full_title.text(title_rest.join(" "));
    };
    is_text_fitted = (full_text.text().length > 0) ? false : true;
    if (!is_text_fitted) {
      modal_toggler.show();
    }

    return is_text_fitted;
  };

  // excerpt - видимая часть текста
  // fulltext - скрытая часть
  // соединяем текст и помещаем в excerpt
  // запускается один раз перед выводом окна
  window.cciModalTextJoin = function () {
    let modal = $('.cci_modal .modal_container');
    let hidden_el = modal.find('.coupon_item_center--fulltext'),
      hidden_text = hidden_el.text(),
      visible_el = modal.find('.coupon_item_center--excerpt'),
      visible_text = visible_el.text(),
      full_text = visible_text + ' ' + hidden_text;
    full_text = full_text.replace('...', '');
    // visible_el.addClass('expanded').text(content);
    visible_el.text(full_text);
    hidden_el.text('');
  };

});
