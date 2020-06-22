<?php

/**
 * Cart remove
 * Модуль удаления товаров из корзины
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/modules/cart_remove.php
 */

require '../include/config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    Session::removeItem($id);
}
