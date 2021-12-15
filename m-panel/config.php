<?php
  /**
  *
  * Настройки по умолчанию
  */
  $options = get_option('cci_options');
  // $options = get_option( 'mp-demo-options' );


  $color_section_bg = isset( $options['color_section_bg'] ) ? $options['color_section_bg'] : 'F2F2F2';
  $color_single_bg = isset( $options['color_single_bg'] ) ? $options['color_single_bg'] : 'F2F2F2';

  $color_base = isset( $options['color_base'] ) ? $options['color_base'] : '1E27CF';
  $color_delivery = isset( $options['color_delivery'] ) ? $options['color_delivery'] : 'F57218';
  $color_promocode = isset( $options['color_promocode'] ) ? $options['color_promocode'] : '57AC58';
  $color_action = isset( $options['color_action'] ) ? $options['color_action'] : '547ED3';
  $color_gift = isset( $options['color_gift'] ) ? $options['color_gift'] : 'B53D72';
  $color_button = isset( $options['color_button'] ) ? $options['color_button'] : '6BC64D';
  $color_button_back = isset( $options['color_button_back'] ) ? $options['color_button_back'] : '39B813';
  $color_menu_active = isset( $options['color_menu_active'] ) ? $options['color_menu_active'] : '858985';
  $color_modal_action = isset( $options['color_modal_action'] ) ? $options['color_modal_action'] : '2B66C0';
  $color_modal_goto = isset( $options['color_modal_goto'] ) ? $options['color_modal_goto'] : '2B66C0';


 $default_options = [

        // Название секций
        'menu_items' => array(
              "Активные купоны",
              "Архив купонов",
              "Импорт",
              "Рекламный блок",
              "Настройка цветов"
         ),


        'active_title'  => isset( $options['active_title'] ) ? $options['active_title'] : 'Действующие купоны %%this_month%% - %%next_month%% / %%year%%',
        'seo_title'     => isset( $options['seo_title'] ) ? $options['seo_title'] : 'Действующие купоны %%this_month%% - %%next_month%% / %%year%%',
        'active_show'   => isset( $options['active_show'] ) ? $options['active_show'] : 4,
        'modal_shortcode'  => isset( $options['modal_shortcode'] ) ? $options['modal_shortcode'] : '[cp_popup display="inline" style_id="584" step_id = "1"][/cp_popup][cp_popup display="inline" style_id="439" step_id = "1"][/cp_popup]',
        'archive_title' => isset( $options['archive_title'] ) ? $options['archive_title'] : 'Завершившиеся акции, скидки, промокоды',
        'archive_show'  => isset( $options['archive_show'] ) ? $options['archive_show'] : 3,
        'archive_load'  => isset( $options['archive_load'] ) ? $options['archive_load'] : 2,

        'admitad_xml'    => isset( $options['admitad_xml'] ) ? $options['admitad_xml'] : 'Insert XML link here',
        'actionpay_xml'  => isset( $options['actionpay_xml'] ) ? $options['actionpay_xml'] : 'Insert XML link here',
        'admitad_text'   => isset( $options['admitad_text'] ) ? $options['admitad_text'] : 'Идейные соображения высшего порядка, а также дальнейшее развитие различных форм деятельности способствует подготовке и реализации системы массового участия.',
        'actionpay_text' => isset( $options['actionpay_text'] ) ? $options['actionpay_text'] : 'Предварительные выводы неутешительны: понимание сути ресурсосберегающих технологий обеспечивает актуальность распределения внутренних резервов и ресурсов.',


        'advert_to_show'          => true,
        'active_adv'      => isset( $options['active_adv'] ) ? $options['active_adv'] : 2,
        'adv_shortcode'   => isset( $options['adv_shortcode'] ) ? $options['adv_shortcode'] : '[cp_popup display="inline" style_id="439" step_id = "1"][/cp_popup]',


            'color_base' => isset( $options['color_base'] ) ? $options['color_base'] : '#1E27CF',
            'color_section_bg' => isset( $options['color_section_bg'] ) ? $options['color_section_bg'] : '#F2F2F2',
            'color_single_bg' => isset( $options['color_single_bg'] ) ? $options['color_single_bg'] : '#F2F2F2',
            'color_delivery' => isset( $options['color_delivery'] ) ? $options['color_delivery'] : '#F57218',
            'color_promocode' => isset( $options['color_promocode'] ) ? $options['color_promocode'] : '#57AC58',
            'color_action' => isset( $options['color_action'] ) ? $options['color_action'] : '#547ED3',
            'color_gift' => isset( $options['color_gift'] ) ? $options['color_gift'] : '#B53D72',
            'color_button' => isset( $options['color_button'] ) ? $options['color_button'] : '#6BC64D',
            'color_button_back' => isset( $options['color_button_back'] ) ? $options['color_button_back'] : '#39B813',
            'color_menu_active' => isset( $options['color_menu_active'] ) ? $options['color_menu_active'] : '#858985',
            'color_modal_action' => isset( $options['color_modal_action'] ) ? $options['color_modal_action'] : '#2B66C0',
            'color_modal_goto' => isset( $options['color_modal_goto'] ) ? $options['color_modal_goto'] : '#2B66C0'



      ];
