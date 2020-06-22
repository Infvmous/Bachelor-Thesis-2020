<?php

/**
 * Cart
 * Страница корзины товаров
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/cart.html
 */

$action = $this->objUrl->get('action');
if ($action == 'view') {
    echo Plugin::get('front' . DS . 'cart_view');
} else {
    include_once '_header.php';
    ?>

    <h1>Корзина</h1>
    <div id="cart_big">
        <?php echo Plugin::get('front' . DS . 'cart_view'); ?>
    </div>

    <?php
    include_once '_footer.php';
}
?>