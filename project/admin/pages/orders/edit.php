<?php
/**
 * Edit
 * Страница редактирования
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/orders/action/edit.html
 */
$id = $this->objUrl->get('id');

if (!empty($id)) {

    $objOrder = new Order();
    $order = $objOrder->getOrder($id);

    if (!empty($order)) {

        $objForm = new Form();
        $objValid = new Validation($objForm);

        $objUser = new User();
        $user = $objUser->getUser($order['client']);

        $objCatalog = new Catalog();

        $items = $objOrder->getOrderItems($id);
        $status = $objOrder->getStatuses();

        if ($objForm->isPost('status')) {

            $objValid->expected = array('status', 'notes');
            $objValid->required = array('status');

            $vars = $objForm->getPostArray($objValid->expected);

            if ($objValid->isValid()) {
                if ($objOrder->updateOrder($id, $vars)) {
                    // Если заказ успешно изменен
                    Helper::redirect(
                        $this->objUrl->getCurrent(
                            array('action', 'id'),
                            false,
                            array('action', 'edited')
                        )
                    );
                } else {
                    // Если произошла ошибка изменения заказа
                    Helper::redirect(
                        $this->objUrl->getCurrent(
                            array('action', 'id'),
                            false,
                            array('action', 'edited-failed')
                        )
                    );
                }
            }
        }

        include '_header.php'; ?>

        <h1>Редактирование заказа №<?php echo $order['id']; ?></h1>
        <form action="" method="post">
            <table cellpadding="0" cellspacing="0" class="tbl_insert">
                <tr>
                    <th>Дата:</th>
                    <td colspan="4">
                        <?php echo Helper::setDate(2, $order['date']); ?>
                    </td>
                </tr>
                <tr>
                    <th>Номер заказа:</th>
                    <td colspan="4"><?php echo $order['id']; ?></td>
                </tr>

                <?php if (!empty($items)) { ?>

                    <tr>
                        <th rowspan="<?php echo count($items) + 1; ?>">Товары:</th>
                        <td class="col_5">Id</td>
                        <td>Товар</td>
                        <td class="col_5 ta_r">К-во</td>
                        <td class="col_15 ta_r">Сумма</td>
                    </tr>

                    <?php
                    foreach ($items as $item) {
                            $product = $objCatalog->getProduct($item['product']);
                        ?>

                        <tr>
                            <td>
                                <?php
                                if ($product['id'] != null) {
                                    echo $product['id'];
                                } else {
                                    echo '0';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($product['name'] != null) {
                                    echo Helper::encodeHtml($product['name']);
                                } else {
                                    echo 'Удален или не найден';
                                }
                                ?>
                            </td>
                            <td class="ta_r"><?php echo $item['qty']; ?></td>
                            <td class="ta_r">
                                <?php
                                    Catalog::$currency;
                                    echo number_format(
                                        ($item['price'] * $item['qty']),
                                        2
                                    );
                                ?>
                            </td>
                        </tr>

                    <?php } ?>

                <?php } ?>

                <tr>
                    <th>Доставка:</th>
                    <td colspan="3">
                        <?php echo Helper::encodeHTML($order['shipping_type']); ?>
                    </td>
                    <td>
                        <?php
                            Catalog::$currency;
                            echo number_format($order['shipping_cost'], 2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Всего:</th>
                    <td colspan="4" class="ta_r">
                        <?php
                            Catalog::$currency;
                            echo number_format($order['subtotal'], 2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>НДС (<?php echo $order['vat_rate']; ?>%):</th>
                    <td colspan="4" class="ta_r">
                        <?php
                            Catalog::$currency;
                            echo number_format($order['vat'], 2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Итого:</th>
                    <td colspan="4" class="ta_r">
                        <strong>
                            <?php
                                Catalog::$currency;
                                echo number_format($order['total'], 2);
                            ?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <th>Клиент:</th>
                    <td colspan="4">
                        <?php
                        echo '<p>';
                        echo Helper::encodeHTML($order['full_name']) . '</br>';
                        echo '<a href="mailto:';
                        echo $order['email'];
                        echo '">';
                        echo $order['email'];
                        echo '</a>';
                        echo '</p>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Платежный адрес</th>
                    <td colspan="4">
                        <?php
                            echo '<p>';
                            echo Helper::encodeHTML($order['address']) . '</br>';
                            echo Helper::encodeHTML($order['country_name']) . '</br>';
                            echo Helper::encodeHTML($order['city']) . '</br>';
                            echo Helper::encodeHTML($order['state']) . '</br>';
                            echo Helper::encodeHTML($order['post_code']);
                            echo '</p>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Адрес доставки</th>
                    <td colspan="4">
                        <?php
                            echo '<p>';
                            echo Helper::encodeHTML($order['ship_address']) . '</br>';
                            echo Helper::encodeHTML($order['ship_country_name']) . '</br>';
                            echo Helper::encodeHTML($order['ship_city']) . '</br>';
                            echo Helper::encodeHTML($order['ship_state']) . '</br>';
                            echo Helper::encodeHTML($order['ship_post_code']);
                            echo '</p>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Статус оплаты:</th>
                    <td colspan="4">
                        <?php
                            echo !empty($order['payment_status']) ?
                                //$order['payment_status'] :
                                "Оплачен" :
                                "Ожидание оплаты"
                        ?>
                    </td>
                </tr>
                <tr>
                    <th><label for="status">Статус заказа</label></th>
                    <td colspan="4">
                        <?php $objValid->validate('status'); ?>

                        <?php if (!empty($status)) { ?>

                            <select name="status" id="status" class="sel">

                                <?php foreach ($status as $stat) { ?>
                                    <option
                                        value="<?php echo $stat['id']; ?>"
                                        <?php echo $objForm->stickySelect(
                                            'status', $stat['id'], $order['status']
                                        );?>><?php echo Helper::encodeHTML($stat['name']); ?>
                                    </option>
                                <?php } ?>

                            </select>

                        <?php } ?>

                    </td>
                </tr>
                <tr>
                    <th><label for="notes">Примечания</label></th>
                    <td colspan="4">
                        <textarea
                            name="notes"
                            id="notes"
                            cols=""
                            rows=""
                            class="tar"
                        ><?php echo $objForm->stickyText('notes', $order['notes']); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>&nbsp;</th>
                    <td colspan="4">
                        <div class="sbm sbm_blue fl_r">
                            <a href="<?php echo $this->objUrl->getCurrent(array('action'), false, array('action', 'invoice')); ?>" class="btn" target="_blank">Квитанция</a>
                        </div>

                        <label for="btn_update" class="sbm sbm_blue fl_l">
                            <input type="submit" id="btn_update" class="btn" value="Сохранить" />
                        </label>
                    </td>
                </tr>
            </table>
        </form>

        <?php include '_footer.php';
    }
} else {
    Helper::redirect($this->objUrl->href('error'));
}




