<?php
/**
 * Страница управления доставкой
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/shipping
 */
Login::restrictAdmin();

$objShipping = new Shipping();

$id = $this->objUrl->get('id');
$action = $this->objUrl->get('action');

try {
    switch($action) {

    case 'default':
    case 'active':
    case 'remove':
    case 'update':
    case 'duplicate':
    case 'rates':
        if (!empty($id)) {
            $type = $objShipping->getType($id);
            if (!empty($type)) {
                switch($action) {
                case 'default':
                    include_once 'shipping'.DS.'default.php';
                    break;
                case 'active':
                    include_once 'shipping'.DS.'active.php';
                    break;
                case 'remove':
                    include_once 'shipping'.DS.'remove.php';
                    break;
                case 'update':
                    include_once 'shipping'.DS.'update.php';
                    break;
                case 'duplicate':
                    include_once 'shipping'.DS.'duplicate.php';
                    break;
                case 'rates':
                    include_once 'shipping'.DS.'rates.php';
                    break;
                }
            } else {
                throw new Exception('Record not found');
            }
        } else {
            throw new Exception('Missing parameter');
        }
        break;
    case 'sort':
        include_once 'shipping'.DS.'sort.php';
        break;
    case 'add':
        include_once 'shipping'.DS.'add.php';
        break;
    default:
        include_once 'shipping'.DS.'list.php';
    }
} catch (Exception $e) {
    echo Helper::json(
        array(
        'error' => true,
        'message' => $e->getMessage()
        )
    );
}