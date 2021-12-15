<?php
/**
 * Кастомайзер
 *
 *
 */


abstract class CCI_Customizer
{

   /**
    * Цвета из настроек
    *
    * @return String
    */
    public static function section_bg_color(){
        $options    = get_option( 'cci_options' );
        $bgc        =trim( $options['color_section_bg'] );
        $bgc_single =trim( $options['color_single_bg'] );
        $dashed_border_color = ( intval($bgc) == 0 ) ? "e2e2e2" : $bgc;
        $dashed_border_color_single = ( intval($bgc_single) == 0 ) ? "e2e2e2" : $bgc_single;

        $color_delivery     = trim( $options['color_delivery'] );
        $color_promocode    = trim( $options['color_promocode'] );
        $color_action       = trim( $options['color_action'] );
        $color_gift         = trim( $options['color_gift'] );
        $color_button       = trim( $options['color_button'] );
        $color_button_back  = trim( $options['color_button_back'] );
        $color_menu_active  = trim( $options['color_menu_active'] );
        $color_modal_action = trim( $options['color_modal_action'] );
        $color_modal_goto   = trim( $options['color_modal_goto'] );

        $style = "<style>";

        // бэкграунд для секций активных купонов и архива
        $style .= ".coupons_active_container .coupon_item .coupon_item_center:before{background:$bgc;}";
        $style .= ".coupons_active_container .coupon_item .coupon_item_center:after{background-color:$bgc;}";
        $style .= ".coupons_active_container .coupon_item .coupon_item_center{border-right-color:$dashed_border_color;}";

        $style .= ".coupon_single_container .coupon_item .coupon_item_center:before{background:$bgc_single;}";
        $style .= ".coupon_single_container .coupon_item .coupon_item_center:after{background-color:$bgc_single;}";
        $style .= ".coupon_single_container .coupon_item .coupon_item_center{border-right-color:$dashed_border_color_single;}";

        // кнопка Открыть
        $style .= ".coupon_item .coupon_item_right .coupon_item_right--button:before{background-color:$color_button;}";
        $style .= ".coupon_item .coupon_item_right .coupon_item_right--button:hover:before{background-color:$color_button;}";

        // кнопка Открыть backside
        $style .= ".coupon_item .coupon_item_right .coupon_item_right--button.promo_button:after {border-color:transparent transparent transparent $color_button_back;}";
        // активное меню
        $style .= ".coupons_active_container .coupons_active_container--filter .active_filter_item.active_filter{background-color:$color_menu_active;}";
        // modal "Скопировать"
        $style .= ".modal_code--action{background-color:$color_modal_action;}";
        // modal "Перейти"
        $style .= ".modal_goto {background-color:$color_modal_goto;}";

        // промокод
        $style .= ".coupon_item.promocode .coupon_item_left--type {background-color:$color_promocode !important;}";
        $style .= ".coupon_item.promocode .coupon_item_left--discount {color:$color_promocode;}";
        $style .= ".coupon_item.promocode .ic-coupon {color: $color_promocode;}";

        // акция
        $style .= ".coupon_item.action .coupon_item_left--type {background-color:$color_action !important;}";
        $style .= ".coupon_item.action .coupon_item_left--discount {color:$color_action;}";
        $style .= ".coupon_item.action .ic-sale {color: $color_action;}";

        // подарок
        $style .= ".coupon_item.gift .coupon_item_left--type {background-color:$color_gift !important;}";
        $style .= ".coupon_item.gift .coupon_item_left--discount {color:$color_gift;}";
        $style .= ".coupon_item.gift .ic-gift {color:$color_gift;}";

        // доставка
        $style .= ".coupon_item.delivery .coupon_item_left--type {background-color:$color_delivery !important;}";
        $style .= ".coupon_item.delivery .coupon_item_left--discount {color:$color_delivery;}";
        $style .= ".coupon_item.delivery .ic-car {color:$color_delivery;}";



        $style .= "</style>";

        return $style;
    }

}