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
 * @link     https://darket-shop.ru/panel/products.html
 */
Login::restrictAdmin();

$action = $this->objUrl->get('action');

switch($action) {
case 'add':
    include 'products/add.php';
    break;

case 'added':
    include 'products/added.php';
    break;

case 'added-failed':
    include 'products/added-failed.php';
    break;

case 'added-no-upload':
    include 'products/added-no-upload.php';
    break;

case 'edit':
    include 'products/edit.php';
    break;

case 'edited':
    include 'products/edited.php';
    break;

case 'edited-failed':
    include 'products/edited-failed.php';
    break;

case 'edited-no-upload':
    include 'products/edited-no-upload.php';
    break;

case 'remove':
    include 'products/remove.php';
    break;

default:
    include 'products/list.php';

}