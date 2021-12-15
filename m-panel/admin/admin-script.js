new Vue({
  el: '#app',
  vuetify: new Vuetify(),
  data() {
    return {
      options: mp_options_data.options,
      show_min: 2,
      show_max: 20,

      active: null,
      is_saving: false,
      is_show_ad: false,
      where_ad: 5,
      ad_min: 2,
      ad_max: 10,

    }
  },
  methods: {
    addValue: function (e) {
      this.colors.push(e.target.value)
    },
    next() {
      const active = parseInt(this.active)
      this.active = (active < 3 ? active + 1 : 0)
    },
    baseColor: function () {
      this.options.color_section_bg = this.options.color_base;
      this.options.color_single_bg = this.options.color_base;
      this.options.color_delivery = this.options.color_base;
      this.options.color_promocode = this.options.color_base;
      this.options.color_action = this.options.color_base;
      this.options.color_gift = this.options.color_base;
      this.options.color_button = this.options.color_base;
      this.options.color_button_back = this.options.color_base;
      this.options.color_menu_active = this.options.color_base;
      this.options.color_modal_action = this.options.color_base;
      this.options.color_modal_goto = this.options.color_base;
    },
    onSave: function () {
      this.is_saving = true;
      if (this.is_base_color) {
        this.set_base
      };
      console.log('url ' + mp_options_data.siteUrl);
      jQuery.ajax({
        url: mp_options_data.siteUrl + '/wp-json/mp/v2/options',
        method: 'POST',
        data: this.options,
        beforeSend: function (request) {
          request.setRequestHeader('X-WP-Nonce', mp_options_data.nonce);
        },
        success: () => {
          this.message = 'Сохранено !';
          this.is_saved = true;
        },
        error: (data) => this.message = data.responseText,
        complete: () => this.is_saving = false,
      });
    }
  },
  created() {

    setTimeout(function () {
      jQuery('#preloader').css('display', 'none');
    }, 1000);


  }
})
