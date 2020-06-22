<?php

/**
 * Страница для IPN
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/ipn.php
 */

$objPayPal = new PayPal();
$objPayPal->ipn();