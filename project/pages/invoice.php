<?php

/**
 * Страница с квитанцией выбранного заказа
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/order/id/invoice.html
 */

Login::restrictFront($this->objUrl);

$token = $this->objUrl->get('token');
if (!empty($token)) {
    $objOrder = new Order();
    $order = $objOrder->getOrderByToken($token);

    if (!empty($order)
        && Session::getSession(Login::$login_front) == $order['client']
    ) {
        $items = $objOrder->getOrderItems($order['id']);

        $objBusiness = new Business();
        $business = $objBusiness->getBusiness();
        ?>

            <!DOCTYPE html>
            <html lang="ru">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Квитанция №<?php echo $order['id']; ?></title>
                <meta name="description" content="" />
                <meta name="keywords" content="" />
                <meta http-equiv="imagetoolbar" content="no" />
                <link href="/css/invoice.css" rel="stylesheet" type="text/css" />
            </head>
            <body>

            <div id="wrapper">
                <h1>Квитанция</h1>

                <div style="width:50%;float:left">
                    <p>
                        <strong>Платежный адрес:</strong>
                        <?php echo $order['full_name']; ?><br />
                        <?php echo $order['address']; ?>, <?php echo $order['city']; ?><br />
                        <?php echo $order['state']; ?>, <?php echo $order['post_code']; ?><br />
                        <?php echo $order['country_name']; ?>
                    </p>
                    <p>
                        <strong>Адрес доставки:</strong>
                        <?php echo $order['full_name']; ?><br />
                        <?php echo $order['ship_address']; ?>, <?php echo $order['ship_city']; ?><br />
                        <?php echo $order['ship_state']; ?>, <?php echo $order['ship_post_code']; ?><br />
                        <?php echo $order['ship_country_name']; ?>
                    </p>
                </div>

                <div style="width:50%;float:right;text-align:right;">
                    <p><strong><?php echo $business['name']; ?></strong><br />
                        <?php echo nl2br($business['address']); ?><br />
                        <?php echo $business['telephone']; ?><br />
                        <?php echo $business['email']; ?><br />
                        <?php echo $business['website']; ?>
                        <?php echo $order['vat_rate'] > 0 && !empty($order['vat_number']) ? '<br />Регистрационный номер НДС: '.$order['vat_number'] : null; ?>
                    </p>
                </div>

                <div class="dev">&#160;</div>
                    <h3>Заказ №<?php echo $order['id']; ?> / Дата <?php echo $order['date']; ?></h3>
                    <table cellpadding="0" cellspacing="0" class="tbl_repeat">
                    <tr>
                        <th>Товар</th>
                        <th class="ta_r">Кол-во</th>
                        <th class="ta_r col_15">Цена</th>
                    </tr>

                    <?php foreach ($items as $item) { ?>

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

                    <tbody class="summarySection">
                        <tr>
                            <td colspan="2" class="br_td">Всего товаров:</td>
                            <td class="ta_r br_td">
                                <?php
                                echo Catalog::$currency;
                                echo number_format($order['subtotal_items'], 2);
                                ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2" class="br_td">Тип доставки <?php echo $order['shipping_type']; ?>:</td>
                            <td class="ta_r br_td">
                                <?php
                                echo Catalog::$currency;
                                echo number_format($order['shipping_cost'], 2);
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="br_td">Всего:</td>
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
                            <td colspan="2" class="br_td"><strong>Итого:</strong></td>
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

                <div class="dev br_td">&nbsp;</div>
                <p><a href="#" onclick="window.print(); return false;">Печать</a></p>

            </div>

            </body>
            </html>

        <?php
    }
}
?>






