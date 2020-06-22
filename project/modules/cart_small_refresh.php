<?php

/**
 * Small cart refresh
 * Модуль обновления маленькой корзины
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/modules/cart_small_refresh.php
 */

require_once '../include/config.php';

$objCart = is_object($objCart) ? $objCart : new Cart();

$out = array();
$out['bl_ti'] = $objCart->number_of_items;
$out['bl_st'] = number_format($objCart->sub_total, 2);

echo Helper::json($out);