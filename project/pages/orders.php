<?php

/**
 * Страница с заказами пользователя
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/orders.html
 */

Login::restrictFront($this->objUrl);

$objOrder = new Order();
$orders = $objOrder->getClientOrders(Session::getSession(Login::$login_front));

$objPaging = new Paging($this->objUrl, $orders, 30);
$rows = $objPaging->getRecords();

require_once '_header.php';
?>

<h1>Список текущих заказов</h1>

<?php if (!empty($rows)) { ?>

    <table cellspacing="0" cellpadding="0" class="tbl_repeat">
    <tr>
        <th class="col_5">Номер</th>
        <th>Дата</th>
        <th class="ta_r col_15">Статус</th>
        <th class="ta_r col_15">Итого</th>
        <th class="ta_r col_5">Квитанция</th>
    </tr>

    <?php foreach ($rows as $row) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo Helper::setDate(1, $row['date']); ?></td>
            <td class="ta_r">
                <?php
                    $status = $objOrder->getStatus($row['status']);
                    echo $status['name'];
                ?>
            </td>

            <td class="ta_r">
                <?php
                    $objCatalog = new Catalog();
                    echo Catalog::$currency;
                    echo number_format($row['total'], 2);
                ?>
            </td>

            <td class="ta_r">
                <?php
                if ($row['pp_status'] == 1) {
                    echo '<a href="';
                    echo $this->objUrl->href('invoice', array('token', $row['token']));
                    echo '" target="_blank">Открыть</a>';
                } else {
                    echo '<span class="inactive">Открыть</span>';
                }
                ?>
            </td>
            </tr>

    <?php } ?>

    </table>

    <?php echo $objPaging->getPaging(); ?>

<?php } else { ?>
<p>На данный момент у Вас нет текущих заказов.</p>
<?php } ?>


<?php require_once '_footer.php'; ?>