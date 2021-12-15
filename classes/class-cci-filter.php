<?php
// Basic class of filter

class CCI_Filter
{
   
    private $filters;

    public function __construct(){
        $this->filters = get_terms(array(
                    'taxonomy'      => 'coupon-species',
                    'hide_empty'    => true,
                    'fields'        => 'all'
        ));
    }

    public function render(){
        $template = '<nav class="coupons_active_container--filter">';
        $template .= '<div class="active_filter_item active_filter" data-filter="coupon_item">Все</div>';
        foreach( $this->filters as $filter ){
            $template .= "<div class='active_filter_item' data-filter='{$filter->slug}'>{$filter->name}</div>";
        };
        $template .= '</nav>';
        echo $template;
    }

    




}