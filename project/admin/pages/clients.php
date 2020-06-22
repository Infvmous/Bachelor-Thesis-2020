<?php
/**
 * Страница управления клиентами
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/clients.html
 */
Login::restrictAdmin();

$action = $this->objUrl->get('action');

switch($action) {
case 'edit':
    include 'clients/edit.php';
    break;

case 'edited':
    include 'clients/edited.php';
    break;

case 'edited-failed':
    include 'clients/edited-failed.php';
    break;

case 'remove':
    include 'clients/remove.php';
    break;

default:
    include 'clients/list.php';

}