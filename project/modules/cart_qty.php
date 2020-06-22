<?php

/**
 * Cart quantity
 * Модуль, отвечающий за количество товаров одного типа в корзине
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/modules/cart_qty.php
 */

require_once '../include/config.php';

if (isset($_POST['qty']) && isset($_POST['id'])) {
    $out = array();
    $id = $_POST['id'];
    $value = $_POST['qty'];

    $objCatalog = new Catalog();
    $product = $objCatalog->getProduct($id);

    if (!empty($product)) {
        switch ($value) {
        case 0:
            Session::removeItem($id);
            break;
        default:
            Session::setItem($id, $value);
        }
    }
}