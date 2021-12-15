<?php 
/**
 * Coupons CPA Import settings page
 *
 * v 1.0
 *
 */


// Plugin settings page registration
add_action( 'admin_menu', 'cci_create_settings_menu' );
function cci_create_settings_menu()
{
    // add_plugins_page( 'Coupons CPA Import настройки', 'Coupons CPA Import настройки', 'manage_options', 'cci_setup', 'cci_setup_page' );
    add_options_page( 'Coupons CPA Import', 'Coupons CPA Import', 'manage_options', 'cci_setup', 'cci_setup_page' );
    add_action( 'admin_init', 'cci_register_settings' );
};

function cci_register_settings()
{
    register_setting( 'cci_options', 'cci_options', 'cci_sanitize_options' );
};

function cci_setup_page()
{?>
   

<div>
    <h2><?php esc_html_e('Coupons CPA Import', 'cci'); ?></h2>
    <form method="post" action="options.php">
    <?php
    settings_fields('cci_options');
    $options = get_option('cci_options');

    $active_title    = isset( $options['active_title'] ) ? $options['active_title'] : 'Действующие купоны %%this_month%% - %%next_month%% / %%year%%';
    $seo_title       = isset( $options['seo_title'] ) ? $options['seo_title'] : 'Действующие купоны %%this_month%% - %%next_month%% / %%year%%';
    $archive_title   = isset( $options['archive_title'] ) ? $options['archive_title'] : 'Завершившиеся акции, скидки, промокоды';
    $active_show     = isset( $options['active_show'] ) ? $options['active_show'] : 4;
    $archive_show    = isset( $options['archive_show'] ) ? $options['archive_show'] : 3;
    $archive_load    = isset( $options['archive_load'] ) ? $options['archive_load'] : 2;
    $modal_shortcode = isset( $options['modal_shortcode'] ) ? $options['modal_shortcode'] : '';
    $adv_shortcode   = isset( $options['adv_shortcode'] ) ? $options['adv_shortcode'] : '';
    $active_adv      = isset( $options['active_adv'] ) ? $options['active_adv'] : 2;

    $admitad_xml    = isset( $options['admitad_xml'] ) ? $options['admitad_xml'] : '';
    $actionpay_xml  = isset( $options['actionpay_xml'] ) ? $options['actionpay_xml'] : '';
    $admitad_text   = isset( $options['admitad_text'] ) ? $options['admitad_text'] : '';
    $actionpay_text = isset( $options['actionpay_text'] ) ? $options['actionpay_text'] : '';

    $color_section_bg = isset( $options['color_section_bg'] ) ? $options['color_section_bg'] : 'F2F2F2';
    $color_single_bg = isset( $options['color_single_bg'] ) ? $options['color_single_bg'] : 'F2F2F2';

    
    // $color_main  = isset( $options['color_main'] ) ? $options['color_main'] : '57ac58';
    // #f57218
    $color_delivery = isset( $options['color_delivery'] ) ? $options['color_delivery'] : 'F57218';
    // #57ac58
    $color_promocode = isset( $options['color_promocode'] ) ? $options['color_promocode'] : '57AC58';
    // #547ED3
    $color_action = isset( $options['color_action'] ) ? $options['color_action'] : '547ED3';
    // #b53d72
    $color_gift = isset( $options['color_gift'] ) ? $options['color_gift'] : 'B53D72';
    // #6bc64d
    $color_button = isset( $options['color_button'] ) ? $options['color_button'] : '6BC64D';
    // #39b813
    $color_button_back = isset( $options['color_button_back'] ) ? $options['color_button_back'] : '39B813';
    // #858985
    $color_menu_active = isset( $options['color_menu_active'] ) ? $options['color_menu_active'] : '858985';
    //#2B66C0
    $color_modal_action = isset( $options['color_modal_action'] ) ? $options['color_modal_action'] : '2B66C0';
    //#2B66C0
    $color_modal_goto = isset( $options['color_modal_goto'] ) ? $options['color_modal_goto'] : '2B66C0';

    
    ?>
        <div class="cci_settings" id="tabs">
            <ul>
                <li><a href="#tabs-1"><b><?php esc_html_e( 'Активные купоны', 'cci' ); ?></b></a></li>
                <li><a href="#tabs-2"><b><?php esc_html_e( 'Архив купонов', 'cci' ); ?></b></a></li>
                <li><a href="#tabs-3"><b><?php esc_html_e( 'Импорт', 'cci' ); ?></b></a></li>
                <li><a href="#tabs-4"><b><?php esc_html_e( 'Рекламный блок', 'cci' ); ?></b></a></li>
                <li><a href="#tabs-5"><b><?php esc_html_e( 'Настройка цветов', 'cci' ); ?></b></a></li>
            </ul>
            
            <div id="tabs-1">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Заголовок секции', 'cci' ); ?></th>
                        <td>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[active_title]"
                                   value = "<?php echo esc_attr($active_title); ?>"
                                   placeholder = "<?php echo esc_attr($active_title); ?>"
                            />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'SEO-заголовок страницы', 'cci' ); ?></th>
                        <td>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[seo_title]"
                                   value = "<?php echo esc_attr($seo_title); ?>"
                                   placeholder = "<?php echo esc_attr($seo_title); ?>"
                            />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Сколько купонов показывать', 'cci' ); ?></th>
                        <td>
                           <select name="cci_options[active_show]">
                                <!-- debug-->
                                <option value="1" <?php selected($active_show, "1"); ?>>1</option>
                                <option value="2" <?php selected($active_show, "2"); ?>>2</option>
                                <option value="3" <?php selected($active_show, "3"); ?>>3</option>
                                <!--/debug-->
                                <option value="4" <?php selected($active_show, "4"); ?>>4</option>
                                <option value="5" <?php selected($active_show, "5"); ?>>5</option>
                                <option value="6" <?php selected($active_show, "6"); ?>>6</option>
                                <option value="7" <?php selected($active_show, "7"); ?>>7</option>
                                <option value="8" <?php selected($active_show, "8"); ?>>8</option>
                                <option value="9" <?php selected($active_show, "9"); ?>>9</option>
                                <option value="10" <?php selected($active_show, "10"); ?>>10</option>
                                <option value="11" <?php selected($active_show, "11"); ?>>11</option>
                                <option value="12" <?php selected($active_show, "12"); ?>>12</option>
                                <option value="13" <?php selected($active_show, "13"); ?>>13</option>
                                <option value="14" <?php selected($active_show, "14"); ?>>14</option>
                                <option value="15" <?php selected($active_show, "15"); ?>>15</option>
                                <option value="16" <?php selected($active_show, "16"); ?>>16</option>
                                <option value="17" <?php selected($active_show, "17"); ?>>17</option>
                                <option value="18" <?php selected($active_show, "18"); ?>>18</option>
                                <option value="19" <?php selected($active_show, "19"); ?>>19</option>
                                <option value="20" <?php selected($active_show, "20"); ?>>20</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Шорткод для модального окна', 'cci' ); ?></th>
                        <td>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[modal_shortcode]"
                                   value = "<?php echo esc_attr($modal_shortcode); ?>"
                                   placeholder = "<?php echo esc_attr($modal_shortcode); ?>"
                            />
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tabs-2">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Заголовок секции', 'cci' ); ?></th>
                        <td>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[archive_title]"
                                   value = "<?php echo esc_attr($archive_title); ?>"
                                   placeholder = "<?php echo esc_attr($archive_title); ?>"
                            />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Сколько купонов показывать', 'cci' ); ?></th>
                        <td>
                            <select name="cci_options[archive_show]">
                                <option value="4" <?php selected($archive_show, "4"); ?>>4</option>
                                <option value="5" <?php selected($archive_show, "5"); ?>>5</option>
                                <option value="6" <?php selected($archive_show, "6"); ?>>6</option>
                                <option value="7" <?php selected($archive_show, "7"); ?>>7</option>
                                <option value="8" <?php selected($archive_show, "8"); ?>>8</option>
                                <option value="9" <?php selected($archive_show, "9"); ?>>9</option>
                                <option value="10" <?php selected($archive_show, "10"); ?>>10</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Сколько купонов загружать', 'cci' ); ?></th>
                        <td>
                            <select name="cci_options[archive_load]">
                                <option value="2" <?php selected($archive_load, "2"); ?>>2</option>
                                <option value="3" <?php selected($archive_load, "3"); ?>>3</option>
                                <option value="4" <?php selected($archive_load, "4"); ?>>4</option>
                                <option value="5" <?php selected($archive_load, "5"); ?>>5</option>
                                <option value="6" <?php selected($archive_load, "6"); ?>>6</option>
                                <option value="7" <?php selected($archive_load, "7"); ?>>7</option>
                                <option value="8" <?php selected($archive_load, "8"); ?>>8</option>
                                <option value="9" <?php selected($archive_load, "9"); ?>>9</option>
                                <option value="10" <?php selected($archive_load, "10"); ?>>10</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tabs-3">
                <table class="form-table">

                    <tr valign="top">
                        <th scope="row">Выгрузка Admitad XML</th>
                        <td>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[admitad_xml]"
                                   value = "<?php echo esc_attr($admitad_xml); ?>"
                                   placeholder = "<?php echo esc_attr($admitad_xml); ?>"
                            />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Выгрузка Actionpay XML</th>
                        <td>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[actionpay_xml]"
                                   value = "<?php echo esc_attr($actionpay_xml); ?>"
                                   placeholder = "<?php echo esc_attr($actionpay_xml); ?>"
                            />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Текст купона Admitad</th>
                        <td>
                            <textarea 
                                   cols = "51" 
                                   rows = "6"
                                   name="cci_options[admitad_text]"
                                   placeholder = "<?php echo esc_attr($admitad_text); ?>"
                            ><?php echo esc_attr($admitad_text); ?></textarea>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Текст купона Actionpay</th>
                        <td>
                            <textarea 
                                   cols = "51" 
                                   rows = "6"
                                   name="cci_options[actionpay_text]"
                                   placeholder = "<?php echo esc_attr($actionpay_text); ?>"
                            ><?php echo esc_attr($actionpay_text); ?></textarea>
                        </td>
                    </tr>
                </table> 
            </div>
            <div id="tabs-4">
                <table class="form-table">
                     <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Вывод рекламного блока', 'cci' ); ?></th>
                        <td>
                           <select name="cci_options[active_adv]">
                                <option value="0" <?php selected($active_adv, "0"); ?>>Не показывать</option>
                                <option value="1" <?php selected($active_adv, "1"); ?>>После 1</option>
                                <option value="2" <?php selected($active_adv, "2"); ?>>После 2</option>
                                <option value="3" <?php selected($active_adv, "3"); ?>>После 3</option>
                                <option value="4" <?php selected($active_adv, "4"); ?>>После 4</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Шорткод для рекламного блока', 'cci' ); ?></th>
                        <td>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[adv_shortcode]"
                                   value = "<?php echo esc_attr($adv_shortcode); ?>"
                                   placeholder = "<?php echo esc_attr($adv_shortcode); ?>"
                            />
                        </td>
                    </tr>
                </table>
            </div>
            <div id="tabs-5">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет фона секций', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_section_bg]"
                                   value = "<?php echo esc_attr($color_section_bg); ?>"
                                   placeholder = "<?php echo esc_attr($color_section_bg); ?>"
                            />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет фона одинарного купона', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_single_bg]"
                                   value = "<?php echo esc_attr($color_single_bg); ?>"
                                   placeholder = "<?php echo esc_attr($color_single_bg); ?>"
                            />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет купона промокода', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_promocode]"
                                   value = "<?php echo esc_attr($color_promocode); ?>"
                                   placeholder = "<?php echo esc_attr($color_promocode); ?>"
                            />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет купона акции', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_action]"
                                   value = "<?php echo esc_attr($color_action); ?>"
                                   placeholder = "<?php echo esc_attr($color_action); ?>"
                            />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет купона подарка', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_gift]"
                                   value = "<?php echo esc_attr($color_gift); ?>"
                                   placeholder = "<?php echo esc_attr($color_gift); ?>"
                            />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет купона доставки', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_delivery]"
                                   value = "<?php echo esc_attr($color_delivery); ?>"
                                   placeholder = "<?php echo esc_attr($color_delivery); ?>"
                            />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет кнопки', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_button]"
                                   value = "<?php echo esc_attr($color_button); ?>"
                                   placeholder = "<?php echo esc_attr($color_button); ?>"
                            />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет обратной стороны кнопки', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_button_back]"
                                   value = "<?php echo esc_attr($color_button_back); ?>"
                                   placeholder = "<?php echo esc_attr($color_button_back); ?>"
                            />
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет активного меню', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_menu_active]"
                                   value = "<?php echo esc_attr($color_menu_active); ?>"
                                   placeholder = "<?php echo esc_attr($color_menu_active); ?>"
                            />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет кнопки \'Копировать\' в модальном окне', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_modal_action]"
                                   value = "<?php echo esc_attr($color_modal_action); ?>"
                                   placeholder = "<?php echo esc_attr($color_modal_action); ?>"
                            />
                        </td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><?php esc_html_e( 'Цвет кнопки \'Перейти\' в модальном окне', 'cci' ); ?></th>
                        <td>
                        <span>#</span>
                            <input type = "text"
                                   size = "50" 
                                   name="cci_options[color_modal_goto]"
                                   value = "<?php echo esc_attr($color_modal_goto); ?>"
                                   placeholder = "<?php echo esc_attr($color_modal_goto); ?>"
                            />
                        </td>
                    </tr>
                   

                </table>
            </div>
            

        </div>
        <?php submit_button(); ?>
    </form>
</div>
<?php
};


function cci_sanitize_options($input)
{
    foreach( $input as $name => & $val ){
        $val = strip_tags( $val );
    };
    return $input;
};

