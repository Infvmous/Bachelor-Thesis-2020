<?php
/**
 * Страница управления страницами зон
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/zones
 */

Login::restrictAdmin();

$objShipping = new Shipping();

$id = $this->objUrl->get('id');
$action = $this->objUrl->get('action');

try {
    switch($action) {
    case 'update':
    case 'remove':
    case 'codes':
        if (!empty($id)) {
            $zone = $objShipping->getZoneById($id);
            if (!empty($zone)) {
                switch($action) {
                case 'update':
                    include_once 'zones'.DS.'update.php';
                    break;
                case 'remove':
                    include_once 'zones'.DS.'remove.php';
                    break;
                case 'codes':
                    include_once 'zones'.DS.'codes.php';
                    break;
                }
            } else {
                throw new Exception('Запись не найдена');
            }
        } else {
            throw new Exception('Не найден параметр');
        }
        break;
    case 'add':
        include_once 'zones'.DS.'add.php';
        break;
    default:
        include_once 'zones'.DS.'list.php';
    }
} catch (Exception $e) {
    echo Helper::json(array('error' => true, 'message' => $e->getMessage()));
}