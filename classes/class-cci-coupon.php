<?php
// Basic class of coupon

require_once ( plugin_dir_path( __FILE__ ) . '/trait-cci-coupon.php');

class CCI_Coupon
{
    use Coupon;
    
    private $type;
    private $type_name;
    private $title;
    private $description;
    private $coupon_id;
    private $post_id;
    private $counter;
    private $code;
    private $discount;
    private $promolink;
    private $gotolink;
    private $date_end;

    public function __construct( $post ){
        $this->post_id      = $post->ID;
        $this->set_type();

        $this->code         = $this->get_code();
        $this->title        = $post->post_title;
        $this->description  = $post->post_content;
        $this->coupon_id    = get_post_meta( $post->ID, 'coupon_id', true );
        $this->promolink    = get_post_meta( $post->ID, 'coupon_promolink', true );
        $this->gotolink     = get_post_meta( $post->ID, 'coupon_gotolink', true );
        $this->date_end     = get_post_meta( $post->ID, 'coupon_date_end', true );
        $this->discount     = get_post_meta( $post->ID, 'coupon_discount', true );
        $this->counter      = get_post_meta( $post->ID, 'coupon_counter', true );
    }


    public function get_title(){
        return $this->title;
    }

    public function render(){
        $template = sprintf(
            $this->get_coupon_template(),
            $this->code,
            $this->promolink,
            $this->gotolink,
            $this->coupon_id,
            $this->post_id,
            $this->get_discount(),
            $this->get_value_class(),
            $this->get_icon_class(),
            $this->type_name,
            $this->title,
            $this->description,
            $this->get_button(),
            $this->counter,
            $this->get_days(),
            $this->type
        );

       echo $template;
    }

    private function get_discount(){
        $el = '';
        if ( $this->discount ){
            $last_digit = substr( $this->discount, -1 );
            if ( '%' == $last_digit ){
                $icon = '<i class="fas fa-percent"></i>';
            } elseif ( '$' == $last_digit ){
                $icon = '<i class="fas fa-dollar-sign"></i>';
            } else {
                $icon = '<i class="fas fa-ruble-sign"></i>';
            };
            $discount_value = preg_replace( '/[^0-9]/', '', $this->discount );
            $el = "<div class='coupon_item_left--discount'><span class='coupon_disount_size'>{$discount_value}</span>{$icon}</div>";
        };
        return $el;
    }

    private function get_button(){
        $cta_action = '<button class="coupon_item_right--button">Открыть предложение</button>';
        if ( $this->code ){
            $last_digits = mb_substr( $this->code, -3 );
        } else {
            $last_digits = 'ХХХ';
        };
        $cta_promo = "<button class='coupon_item_right--button promo_button'>Открыть промокод<span class='coupon_item_right--code'>
        {$last_digits}</span></button>";

        if ( 'promocode' == $this->type ){
            $button = $cta_promo;
        } else {
            $button = $cta_action;
        };
        return $button;
    }

    private function get_value_class(){
        $value_class = ( !$this->discount ) ? 'no_value' : '';
        return $value_class;
    }

    private function get_icon_class(){
        switch( $this->type ){
            case 'gift':
                $icon = 'ic-gift';
                break;
            case 'delivery':
                $icon = 'ic-car';
                break;
            case 'action':
                $icon = 'ic-sale';
                break;
            case 'promocode':
                $icon = 'ic-coupon';
                break;
            default:
                $icon = 'ic-coupon';
        }
        return $icon;
    }

    private function get_days(){
        $date_today = new DateTime( current_time( 'Y-m-d', 0 ) );
        $date_end   = new DateTime( $this->date_end );
        $days       = 'Заканчивается';

        $day_format_1 = 'день';
        $day_format_2 = 'дня';
        $day_format_3 = 'дней';

        $days_array_1 = array(1);
        $days_array_2 = array(2,3,4);
        $days_array_3 = array(5,6,7,8,9);



        // get difference
        if ( $date_end > $date_today ){
            $diff       = date_diff($date_today, $date_end);
            $days .= ' через ' . $diff->days . ' ' . $this->_day( $diff->days );

        } elseif( $date_end == $date_today ){
            // $days .= ' сегодня';
            $days .= $this->get_hours_left();
        };
        return $days;
    }

    private function get_hours_left(){
        $current_stamp = current_time('timestamp');
        $current_time = date("H", $current_stamp);
        $time_left = 24 - $current_time;
        $result = ' через ' . $time_left;

        $hours_1 = array(1,21);
        $hours_2 = array(2,3,4,22,23);
        // $hours_3 = array(5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);

        if ( in_array( $time_left, $hours_1 ) ){
            $result .= ' час';
        } elseif( in_array( $time_left, $hours_2 ) ){
            $result .= ' часа';
        } else{
            $result .= ' часов';
        };
        
        return $result;
    }

    private function _day( $days ){
        $form1 = "день";
        $form2 = "дня";
        $form3 = "дней";

        $n = abs($days) % 100;
        $n1 = $n % 10;

        if ($n > 10 && $n < 20) {
            return $form3;
        };

        if ($n1 > 1 && $n1 < 5) {
            return $form2;
        };

        if ($n1 == 1) {
            return $form1;
        };

        return $form3;
    }


    private function set_type(){
        $terms_classes = '';
        $this->type_name = false;
        $specie = wp_get_post_terms( $this->post_id, 'coupon-species', array("fields" => "all") );

        if ( 'promocode' == $specie[0]->slug ){
            $terms_classes = 'promocode';
            $this->type_name = 'КОД';
        } else{
            $types = wp_get_post_terms( $this->post_id, 'coupon-types', array("fields" => "all") );

            // set classname for the 'discount' coupons as 'action' becasue this name uses in styles
            if ( 'discount' == $types[0]->slug ){
                $terms_classes = 'action';
            } else {
                $terms_classes = $types[0]->slug;
            };

            $this->type_name = ( !$this->type_name ) ? $types[0]->name : $this->type_name;
        };


        $this->type = $terms_classes;
    }

    private function get_code(){
        $code = get_post_meta($this->post_id, 'coupon_promocode', true);
        if ( ( '' == $code ) && ( 'promocode' == $this->type ) ){
            $c = 'XXX';
        } else {
            $c = $code;
        };

        return $c;

    }


}
