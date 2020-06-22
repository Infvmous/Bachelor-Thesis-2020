<?php

/**
 * Cart
 * Модуль корзины покупок
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/modules/cart.php
 */

require_once '../include/config.php';

if (isset($_POST['job']) && isset($_POST['id'])) {
    $out = array();

    $job = $_POST['job'];
    $id = $_POST['id'];

    $objCatalog = new Catalog();
    $product = $objCatalog->getProduct($id);

    if (!empty($product)) {
        switch ($job) {
        case 0:
            Session::removeItem($id);
            $out['job'] = 1;
            break;
        case 1:
            Session::setItem($id);
            $out['job'] = 0;
            break;
        }
        echo Helper::json($out);
    }
}