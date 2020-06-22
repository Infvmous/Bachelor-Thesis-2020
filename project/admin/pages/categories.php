<?php
/**
 * Страница управления категориями в приложении
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/categories
 */
Login::restrictAdmin();

$action = $this->objUrl->get('action');

switch($action) {
case 'add':
    include 'categories/add.php';
    break;

case 'added':
    include 'categories/added.php';
    break;

case 'added-failed':
    include 'categories/added-failed.php';
    break;

case 'edit':
    include 'categories/edit.php';
    break;

case 'edited':
    include 'categories/edited.php';
    break;

case 'edited-failed':
    include 'categories/edited-failed.php';
    break;

case 'remove':
    include 'categories/remove.php';
    break;

default:
    include 'categories/list.php';

}