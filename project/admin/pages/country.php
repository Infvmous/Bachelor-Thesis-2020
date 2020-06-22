<?php
/**
 * Конфиг страницы управления странами
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/country
 */
Login::restrictAdmin();

$objCountry = new Country();

$id = $this->objUrl->get('id');
$action = $this->objUrl->get('action');

try {
    switch($action) {
    case 'active':
    case 'remove':
    case 'update':
        if (!empty($id)) {
            $country = $objCountry->getOne($id);
            if (!empty($country)) {
                switch($action) {
                case 'active':
                    include_once 'country'.DS.'active.php';
                    break;
                case 'remove':
                    include_once 'country'.DS.'remove.php';
                    break;
                case 'update':
                    include_once 'country'.DS.'update.php';
                    break;
                }
            } else {
                throw new Exception('Запись не найдена');
            }
        } else {
            throw new Exception('Параметр не найден');
        }
        break;
    case 'add':
        include_once 'country'.DS.'add.php';
        break;
    default:
        include_once 'country'.DS.'list.php';

    }
} catch (Exception $e) {
    echo Helper::json(array('error' => true, 'message' => $e->getMessage()));
}