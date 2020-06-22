<?php
/**
 * Конфиг почтовых индексов
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/zones
 */
$cid = $this->objUrl->get('cid');
$call = $this->objUrl->get('call');

switch($call) {
case 'add':
    include_once 'codes'.DS.'add.php';
    break;
case 'remove':
    if (!empty($cid)) {
        $code = $objShipping->getPostCode($cid, $zone['id']);
        if (!empty($code)) {
            include_once 'codes'.DS.'remove.php';
        } else {
            throw new Exception('Запись не найдена');
        }
    } else {
        throw new Exception('Параметр не найден');
    }
    break;
default:
    include_once 'codes'.DS.'list.php';
}