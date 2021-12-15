<?php

/**
 *  Различные конфигурацилонные данные
 *
 *
 */

abstract class CCI_Config{

  /**
  * Количество дней до конца месяца, после которого в заголовок страницы будут
  * подставляться не послледующие  месяцы.
  *
  */
  const DAYS_TILL_END_MONTH = 5;


  /**
  * Шаблон рекламного блока
  *
  */
  const ADV_TEMPLATE = "ad-template.php";


  /**
  * Порядок вывода купонов
  *
  */
  public static function coupons_priority(){
    return ['promocode','discount','gift','delivery'];
  }





}
