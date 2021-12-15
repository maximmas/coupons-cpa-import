jQuery(function ($) {

    let archive_loader = $('.coupons_expired_loadmore'),
        archive_index = archive_loader.data('index'),
        part = archive_loader.data('part'),
        total = archive_loader.data('total');
    localStorage.arc_index = archive_index;


    archive_loader.on('click', a_loader);
    function a_loader() {

        // reactivation of last coupon
        var last_coup = $('.coupons_expired_container').find('.archive_item_wrapper').slice(-1);
        if (last_coup.length) {
            last_coup.css('opacity', '1')
                .find('.archive_item_wrapper--title')
                .removeClass('arc_disabled')
                .bind('click')
                .css('cursor', 'pointer')
                // set title underline on hover
                .hover(function () {
                    $(this).css('text-decoration', 'underline');
                }, function () {
                    $(this).css('text-decoration', 'none');
                });

            last_coup.find('.archive_item_wrapper--toggle')
                .removeClass('arc_disabled')
                .hover(
                    function () {
                        $(this).css({ 'color': '#111', 'cursor': 'pointer' })
                    },
                    function () {
                        $(this).css('color', '#bbb')
                    }
                );
        };

        let old_index = localStorage.getItem('arc_index');
        localStorage.arc_index = archive_index;

        let data = {
            action: 'loader',
            index: old_index,
            part: part,
        };
        $.ajax({
            url: CCI_AjaxHandler.cci_ajaxurl,
            type: 'POST',
            data: data,
            beforeSend: function () { },
            success: function (resp) {
                let html = resp;
                localStorage.arc_index = parseInt(old_index) + parseInt(part);
                $('.archive_item_wrapper').last().after(html);
                let coups = $('.archive_item_wrapper');
                if (coups.length == total) $('.coupons_expired_loadmore').hide();
            },
            error: function (resp) {
                // console.log('Houston, we have a problem');
            }
        });
    };




});
