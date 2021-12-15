jQuery(function ($) {

	var minus_icon = '<i class="far fa-minus-square"></i>',
		plus_icon = '<i class="far fa-plus-square"></i>',
		down_icon = '<i class="far fa-caret-square-down"></i>',
		up_icon = '<i class="far fa-caret-square-up"></i>',
		filter = $('.active_filter_item'),
		real_active_container = $('.coupons_active_container').not('.coupon_single_container'),
		// modal_item = $('#cci_modal .coupon_item'),
		// modal_toggler = modal_item.find('#cci_modal .coupon_item--right--toggle'),

		adv_block = $('#add_block'),
		adv_order = parseInt(adv_block.data('order')) - 1,

		clicked_coupon_id = false,
		clicked_coupon_counter = false,

		active_coupons_all = real_active_container.find('.coupon_item');
	active_show = parseInt($('nav.coupons_active_container--filter').data('show')),
		active_coupons_promocode = real_active_container.find('.coupon_item.promocode'),
		active_coupons_action = real_active_container.find('.coupon_item.action'),
		active_coupons_gift = real_active_container.find('.coupon_item.gift'),
		active_coupons_delivery = real_active_container.find('.coupon_item.delivery'),

		active_coupons_displaying = active_coupons_all; // отображаемый массив купонов

	// var counters_json = getCookie('cci_counters');
	var counters_json = null;

	// запрос счетчиков,если страница закэширована
	if (null == counters_json) {
		// let data = {
		// 	action: 'getcounters',
		// };
		$.ajax({
			url: CCI_AjaxHandler.cci_ajaxurl,
			type: 'POST',
			// data: data,
			data: { action: 'getcounters' },
			success: function (resp) { cciInsertCounters(resp, clicked_coupon_id, clicked_coupon_counter); },
		});
	} else {
		cciInsertCounters(counters_json, clicked_coupon_id, clicked_coupon_counter);
	};

	cciCouponsAdjustment('all');

	// если страница в новой вкладке - открываем модальное окно
	if (localStorage.cci_coupon) {
		let coupon = JSON.parse(localStorage.cci_coupon);

		// сохреняем ID и счетчик выбранного купона
		clicked_coupon_id = parseInt(coupon.coupon_id);
		clicked_coupon_counter = parseInt(coupon.coupon_counter);
		localStorage.removeItem('cci_coupon');
		cciModalInit(coupon);
	};

	// text toggling
	$('body').on('click', '.coupon_item_center--toggle', function () {
		let icon = $(this).find('i'),
			full_text = $(this).parent().find('.coupon_item_center--fulltext').text(),
			ex_text = $(this).parent().find('.coupon_item_center--excerpt'),
			short_text = ex_text.text(),
			l_block,
			r_block;

		l_block = $(this).parent().parent().parent().find('.coupon_item_left_clickable');
		r_block = $(this).parent().parent().parent().find('.coupon_item_right--container');


		let l_height = l_block.height(),
			r_height = r_block.height();

		l_block.css('max-height', l_height);
		r_block.css('max-height', r_height);

		if (icon.hasClass('fa-plus-square')) {
			$(this).empty().html(minus_icon);
			let content = short_text + ' ' + full_text;
			content = content.replace('...', '');
			ex_text.addClass('expanded').text(content);
		} else {
			$(this).empty().html(plus_icon);
			ex_text.removeClass('expanded');
			cciTextCutter($(this).parent());
		};
	});

	// фильтр
	filter.on('click.name', function () {
		let type = $(this).data('filter');
		let coupons;
		switch (type) {
			case 'promocode':
				// coupons = active_coupons_promocode.slice(0, active_show - 1);
				active_coupons_displaying = active_coupons_promocode;
				break;
			case 'action':
				active_coupons_displaying = active_coupons_action;
				break;
			case 'delivery':
				active_coupons_displaying = active_coupons_delivery;
				break;
			case 'gift':
				active_coupons_displaying = active_coupons_gift;
				break;
			case 'coupon_item':
				active_coupons_displaying = active_coupons_all;
				break;
		};

		filter.removeClass('active_filter');
		$(this).addClass('active_filter');

		active_coupons_all.each(function (indx, element) {
			$(element).hide();
		});

		$('.coupons_active_additional_container').show();


		var adv_to_end = (active_coupons_displaying.length <= adv_order) ? true : false;
		active_coupons_displaying.each((indx, element) => {
			$(element).show();
			if (indx == adv_order && !adv_to_end) {
				$(element).after(adv_block);
			};
			if (indx == (active_show - 1)) {
				return false;
			};
		});

		// показываем рекламный блок в конце
		if (adv_to_end) {
			active_coupons_displaying.after(adv_block);
		};

		if (active_coupons_displaying.length <= active_show) {
			$('.coupons_active_loadmore').hide();
		} else {
			$('.coupons_active_loadmore').show();
		};

		cciCouponsAdjustment('all');

	});

	// loader
	$('.coupons_active_loadmore').on('click', function () {
		active_show = active_coupons_all.length;
		$(this).fadeOut();
		$('.coupons_active_additional_container').fadeIn();

		active_coupons_displaying.each((indx, element) => {
			$(element).show();
		});

		cciCouponsAdjustment('extra');
	});


	// СЕКЦИЯ АРХИВА
	$('body').on('click', '.archive_item_wrapper--toggle, .archive_item_wrapper--title', function () {

		if ($(this).hasClass('arc_disabled')) return true;

		let container = $(this).parent().parent(),
			item = container.find('.coupon_item'),
			l_block = item.find('.coupon_item_left'),
			r_block = item.find('.coupon_item_right--container');
		c_text = item.find('.coupon_item_center--content'),

			container.css('opacity', '1');

		item.toggleClass('archive_item_opened');
		if (item.hasClass('archive_item_opened')) {
			container
				.find('.archive_item_wrapper--toggle')
				.html('')
				.html(up_icon);
			container
				.find('.archive_item_wrapper--title')
				.css('text-decoration', 'underline');

			item.css('display', 'flex');
		} else {
			container
				.find('.archive_item_wrapper--toggle')
				.html('')
				.html(down_icon);
			container
				.find('.archive_item_wrapper--title')
				.css('text-decoration', 'none');
			item.css('display', 'none');
		};

		// выравнивание правого блока
		let r_height = r_block.parent().height();
		r_block.height(r_height);

		// левый блок
		let discount = l_block.find('.coupon_item_left--discount');
		if (discount.width() > l_block.width()) {
			discount.hide();
			l_block.find('.coupon_item_left--icon').addClass('no_value');
		};

		// обрезка центрального блока
		let c_width = c_text.width();
		c_text.css('min-width', c_width);


	});

	cciArchiveEncounter();

	// клик
	var click_catchers = '.coupon_item_right--button, .coupon_item_center--title, .coupon_item_left_clickable, .coupon_item_right--arrow';
	$('body').on('click', click_catchers, cciCounterHandler);


	// копирование кода в буфер
	var clipboard = new ClipboardJS('.modal_code--action');
	clipboard.on('success', function (e) {
		let text = '<i class="fas fa-check"></i>Скопировано';
		$('.modal_code--action')
			.addClass('copied')
			.html(text);
		e.clearSelection();
	});



});
