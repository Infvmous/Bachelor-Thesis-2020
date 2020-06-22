<?php
/**
 * List
 * Страница управления информацией о заказах
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/orders.html
 */

$objOrder = new Order();
$objCatalog = new Catalog();

if (isset($_POST['srch'])) {
    if (!empty($_POST['srch'])) {
        $url = $this->objUrl->getCurrent('srch') . '/srch/'
            . urlencode(stripcslashes($_POST['srch']));
    } else {
        $url = $this->objUrl->getCurrent('srch');
    }
    Helper::redirect($url);
} else {
    $srch = stripcslashes(urldecode($this->objUrl->get('srch')));
    if (!empty($srch)) {
        $orders = $objOrder->getOrders($srch);
        $empty = 'Не найдены заказы, удовлетворяющие выбранным критериям.';
    } else {
        $orders = $objOrder->getOrders();
        $empty = 'Заказы не найдены.';
    }
    $objPaging = new Paging($this->objUrl, $orders, 15);
    $rows = $objPaging->getRecords();

    include '_header.php'; ?>

    <h1>Список заказов</h1>
    <form action="<?php echo $this->objUrl->getCurrent('srch'); ?>" method="post">
        <table cellpadding="0" cellspacing="0" class="tbl_insert">
            <tr>
                <td>
                    <input
                        type="text"
                        name="srch"
                        id="srch"
                        value="<?php echo $srch; ?>"
                        class="fld"
                        placeholder="Номер заказа"
                    />
                </td>
                <td>
                    <label for="btn_add" class="sbm sbm_blue fl_l">
                        <input
                            type="submit"
                            id="btn_add"
                            class="btn"
                            value="Найти"
                        />
                    </label>
                </td>
            </tr>
        </table>
    </form>

    <?php if (!empty($rows)) { ?>

    <table cellpadding="0" cellspacing="0" class="tbl_repeat">

    <tr>
        <th class="col_5">Id</th>
        <th>Дата</th>
        <th class="col_15 ta_l">E-mail</th>
        <th class="col_15">Итого</th>
        <th class="col_15">Статус</th>
        <th class="col_15">PayPal</th>
        <th class="col_15">&nbsp;</th>
        <th class="col_5">&nbsp;</th>
    </tr>

        <?php foreach ($rows as $order) { ?>

            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo Helper::setDate(1, $order['date']); ?></td>
                <td>
                <?php
                    $objUser = new User();
                    $user = $objUser->getUser($order['client']);
                    echo '<a href="mailto:';
                    echo $user['email'];
                    echo '">';
                    echo $user['email'];
                    echo '</a>';
                ?>
                </td>
                <td>
                    <?php
                        echo Catalog::$currency;
                        echo number_format($order['total'], 2);
                    ?>
                </td>
                <td>
                    <?php
                        $status = $objOrder->getStatus($order['status']);
                        echo $status['name'];
                    ?>
                </td>
                <td >
                    <?php
                        echo $order['payment_status'] != null ?
                        //$order['payment_status'] :
                        "Оплачен" :
                        "Ожидание";
                    ?>
                </td>
                <td>
                    <?php if ($order['status'] == 1) { ?>
                        <a href="<?php echo $this->objUrl->getCurrent('action') . '/action/remove/id/' . $order['id']; ?>" class="red">Удалить</a>
                    <?php } else { ?>
                        <span class="inactive">&nbsp;</span>
                    <?php } ?>
                </td>
                <td>
                    <a href="<?php echo $this->objUrl->getCurrent('action') . '/action/edit/id/' . $order['id']; ?>">Открыть</a>
                </td>
            </tr>

        <?php } ?>
    </table>

        <?php echo $objPaging->getPaging(); ?>

        <?php
    } else {
        echo '<p>' . $empty . '</p>';
    }
    ?>

    <?php include '_footer.php';
} ?>





