<?php
/**
 * Страница управления информацией о веб-приложении
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/business
 */
Login::restrictAdmin();

$action = $this->objUrl->get('action');

switch($action) {
case 'edited':
    include 'business/edited.php';
    break;

case 'edited-failed':
    include 'business/edited-failed.php';
    break;

default:
    include 'business/edit.php';
    break;
}