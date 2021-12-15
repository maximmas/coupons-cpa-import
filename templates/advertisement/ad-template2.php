<?php

function adv_active_block($order){
  
  return 
    "<div id='add_block' data-order='{$order}'>
        <div class='add_block--title'>
          <h2>РЕКЛАМНЫЙ БЛОК</h2>
        </div>
        <div class='add_block--content'>
          <div class='add_block--content_image'>
            <img src='" . plugin_dir_url( __FILE__ ) . "coffee.jpg'>
          </div>
          <div class='add_block--content_text'>
            <p>
              Задача организации, в особенности же укрепление и развитие 
              структуры обеспечивает широкому кругу (специалистов) участие 
              в формировании позиций, занимаемых участниками в отношении 
              поставленных задач.
            </p>
          </div>
        </div>  
    </div>";
};
