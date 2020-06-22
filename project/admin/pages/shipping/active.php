<?php
/**
 * Active
 * Страница активации типа доставки для локальных или международных зон
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/shipping/add.html
 */
$active = $type['active'] == 1 ? 0 : 1;
if ($objShipping->updateType(array('active' => $active), $type['id'])) {
    $replace  = '<a href="#" data-url="';
    $replace .= $this->objUrl->getCurrent();
    $replace .= '" class="clickReplace">';
    $replace .= $active == 1 ? 'Да' : 'Нет';
    $replace .= '</a>';
    echo Helper::json(array('error' => false, 'replace' => $replace));
} else {
    throw new Exception('Запись не может быть изменена');
}