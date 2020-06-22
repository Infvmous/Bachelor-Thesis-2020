<?php

/**
 * Cart view
 * Страница корзины, после изменения количества товаров
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/modules/cart_view.php
 */

$objUrl = new Url();

$session = Session::getSession('cart');
$objCart = new Cart();
$out = array();

if (!empty($session)) {
    $objCatalog = new Catalog();
    $currency = Catalog::$currency;

    foreach ($session as $key => $value) {
        $out[$key] = $objCatalog->getProduct($key);
    }
}

?>

<?php if (!empty($out)) { ?>

    <form action="" method="post" id="frm_cart">
        <table class="tbl_repeat tr_bd">
            <tr>
                <th>Товар</th>
                <th class="ta_r">Кол-во</th>
                <th class="ta_r col_15">Цена</th>
                <th class="ta_r col_15"></th>
            </tr>

            <?php foreach ($out as $item) { ?>
                    <tr>
                        <td><?php echo Helper::encodeHTML($item['name']); ?></td>
                        <td class="ta_r">
                            <select
                                name="qty-<?php echo $item['id']; ?>"
                                id="qty-<?php echo $item['id']; ?>"
                                class="select_qty"
                            >
                            <?php for ($val = 1; $val <= $item['qty']; $val++) { ?>
                                <option
                                    value="<?php echo $val; ?>"
                                    <?php
                                    if ($val == $session[$item['id']]['qty']) {
                                        echo ' selected';
                                    }
                                    ?>
                                >
                                    <?php echo $val; ?>
                                </option>
                            <?php } ?>
                            </select>
                        </td>
                        <td class="ta_r"><?php echo $currency; echo number_format($objCart->itemTotal($item['price'], $session[$item['id']]['qty']), 2);?></td>
                        <td class="ta_r">
                            <?php echo Cart::removeButton($item['id']); ?>
                        </td>
                    </tr>
            <?php } ?>


            <tr>
                <td colspan="2" class="br_td">Всего</td>
                <td class="ta_r br_td"><?php echo $currency; echo number_format($objCart->sub_total, 2); ?></td>
                <td class="ta_r br_td">&#160;</td>
            </tr>

        </table>

        <div class="sbm sbm_blue fl_r">
            <a href="<?php echo $objUrl->href('checkout'); ?>" class="btn">Перейти к оформлению покупки</a>
        </div>
        <!--<div class="sbm sbm_blue fl_l update_cart">
        <span class="btn">Обновить</span>
        </div>
        <div class="sbm sbm_blue fl_l">
            <span class="btn" onclick="history.go(-1)">Назад</span>
        </div>-->
    </form>

    <div class="dev">&#160;</div>
<?php } else { ?>
    <p>Ваша корзина пуста.</p>
<?php } ?>