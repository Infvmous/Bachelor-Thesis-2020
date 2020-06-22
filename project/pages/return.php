<?php

/**
 * Страница, на которую попадает пользователь после оплаты покупки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/return.html
 */

Session::clear('cart');
$token = $this->objUrl->get('token');

if (!empty($token)) {
    $objOrder = new Order();
    $order = $objOrder->getOrderByToken($token);
    $objCatalog = new Catalog();

    $array = array();

    if (!empty($order)) {
        $items = $objOrder->getOrderItems($order['id']);

        include_once '_header.php';
        ?>

        <h1>Спасибо за Ваш заказ!</h1>
        <p>
            Заказ успешно получен и принят в обработку.<br />
            Ниже представлена информация об оформленном заказе.
        </p>

        <div class="dev br_td">&nbsp;</div>

        <p>
            <strong>Ваши покупки будут доставлены по адресу:</strong><br />
            <?php echo $order['full_name']; ?>,
            <?php echo $order['ship_address']; ?>,
            <?php echo $order['ship_city']; ?>,
            <?php echo $order['ship_state']; ?>,
            <?php echo $order['ship_post_code']; ?>,
            <?php echo $order['ship_country_name']; ?>
        </p>

        <p><strong>Заказ №<?php echo $order['id']; ?> / Дата <?php echo $order['date']; ?></strong></p>

        <table cellpadding="0" cellspacing="0" class="tbl_repeat br_bd">
            <tr>
                <th>Товар</th>
                <th class="ta_r">Кол-во</th>
                <th class="ta_r col_15">Цена</th>
            </tr>

            <?php foreach ($items as $item) {
                // Уменьшить количество товара на складе

                echo '<script>';

                echo 'console.log('. json_encode($objCatalog->db->last_query) .')';
                echo '</script>';
                ?>

                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td class="ta_r"><?php echo $item['qty']; ?></td>
                    <td class="ta_r">
                        <?php
                        echo Catalog::$currency;
                        echo number_format($item['price_total'], 2);
                        ?>
                    </td>
                </tr>

            <?php } ?>

            <tbody class="rowHighlight">

                <tr>
                    <td colspan="2" class="br_td">
                        <i>Всего товаров:</i>
                    </td>
                    <td class="ta_r br_td">
                        <i>
                        <?php
                        echo Catalog::$currency;
                        echo number_format($order['subtotal_items'], 2);
                        ?>
                        </i>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="br_td">
                        Тип доставки: <?php echo $order['shipping_type']; ?>
                    </td>
                    <td class="ta_r br_td">
                        <?php
                        echo Catalog::$currency;
                        echo number_format($order['shipping_cost'], 2);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="br_td">
                        Всего:
                    </td>
                    <td class="ta_r br_td">
                        <?php
                        echo Catalog::$currency;
                        echo number_format($order['subtotal'], 2);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="br_td">
                        НДС (<?php echo $order['vat_rate']; ?>%):
                    </td>
                    <td class="ta_r br_td">
                        <?php
                        echo Catalog::$currency;
                        echo number_format($order['vat'], 2);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="br_td">
                        <strong>Итого:</strong>
                    </td>
                    <td class="ta_r br_td">
                        <strong>
                        <?php
                        echo Catalog::$currency;
                        echo number_format($order['total'], 2);
                        ?>
                        </strong>
                    </td>
                </tr>

            </tbody>
        </table>

        <!--<p><a href="#" onclick="window.print(); return false;">Печать</a></p>-->

        <?php
        include_once '_footer.php';
    } else {
        Helper::redirect($this->objUrl->href('error'));
    }
} else {
    Helper::redirect($this->objUrl->href('error'));
}
?>