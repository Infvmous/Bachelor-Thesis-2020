<?php
/**
 * Страница управления товарами в приложении
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/orders.html
 */
Login::restrictAdmin();

$action = $this->objUrl->get('action');

switch($action) {
case 'edit':
    include 'orders/edit.php';
    break;

case 'edited':
    include 'orders/edited.php';
    break;

case 'edited-failed':
    include 'orders/edited-failed.php';
    break;

case 'remove':
    include 'orders/remove.php';
    break;

case 'invoice':
    include 'orders/invoice.php';
    break;

default:
    include 'orders/list.php';

}