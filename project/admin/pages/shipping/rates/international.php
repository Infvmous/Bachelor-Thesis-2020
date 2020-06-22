<?php
/**
 * Local зоны
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/shipping
 */
switch($call) {
case 'add':
    include_once 'international'.DS.'add.php';
    break;
case 'remove':
    include_once 'international'.DS.'remove.php';
    break;
default:
    include_once 'international'.DS.'list.php';

}