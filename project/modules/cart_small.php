<?php

/**
 * Small cart
 * Модуль маленькой корзины
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/modules/cart_small.php
 */

$objCatalog = new Catalog();
$currency = Catalog::$currency;

$objCart = is_object($objCart) ? $objCart : new Cart();
?>

<h2>Ваша корзина</h2>

<dl id="cart_small">

    <dt>Товаров</dt>
    <dd class="bl_ti">
        <span><?php echo $objCart->number_of_items; ?></span>
    </dd>
    <?php echo $this->user['country']; ?>
    <dt>Всего</dt>
    <dd class="bl_st">
    <?php echo $currency; ?><span><?php echo number_format($objCart->sub_total, 2); ?></span>
    </dd>
</dl>

<div class="dev br_td">&#160;</div>
    <p>
        <a href="<?php echo $this->objUrl->href('cart'); ?>">В корзину</a>
        &nbsp;|&nbsp;
        <a href="<?php echo $this->objUrl->href('checkout'); ?>">Оформить</a>
    </p>
<div class="dev br_td">&#160;</div>


