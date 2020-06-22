<?php
/**
 * Rates - выбор зоны доставки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/shipping/remove.html
 */
$zid = $this->objUrl->get('zid');
$call = $this->objUrl->get('call');

switch($type['local']) {
case 1:
    $zone = $objShipping->getZoneById($zid);
    if (!empty($zone)) {
        include_once 'rates'.DS.'local.php';
    }
    break;
default:
    $objCountry = new Country();
    $country = $objCountry->getCountry($zid);
    if (!empty($country)) {
        include_once 'rates'.DS.'international.php';
    }
}