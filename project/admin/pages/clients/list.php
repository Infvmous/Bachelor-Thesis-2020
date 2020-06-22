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
 * @link     http://darket-shop/panel/clients.html
 */

$objUser = new User();
$objOrder = new Order();
$objCatalog = new Catalog();

$srch = $this->objUrl->get('srch');
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
        $users = $objUser->getUsers($srch);
        $empty = 'Не найдены заказы, удовлетворяющие выбранным критериям.';
    } else {
        $users = $objUser->getUsers();
        $empty = 'Заказы не найдены.';
    }
    $objPaging = new Paging($this->objUrl, $users, 15);
    $rows = $objPaging->getRecords();

    include '_header.php'; ?>

    <h1>Список клиентов</h1>
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
                        placeholder="Имя или E-mail"
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
            <th>Имя</th>
            <th>E-mail</th>
            <th class="col_5 ta_r"></th>
            <th class="col_5 ta_r"></th>
        </tr>

            <?php foreach ($rows as $user) { ?>

                <tr>
                    <td><?php echo Helper::encodeHTML($user['first_name'] . " " . $user['last_name']); ?></td>
                    <td>
                        <?php
                            echo '<a href="mailto:';
                            echo $user['email'];
                            echo '">';
                            echo $user['email'];
                            echo '</a>';
                        ?>
                    </td>
                    <td class="ta_r">
                        <?php
                        $orders = $objOrder->getClientOrders($user['id']);

                        if (empty($orders)) { ?>
                            <a href="<?php echo $this->objUrl->getCurrent('action') . '/action/remove/id/' . $user['id']; ?>" class="red">Удалить</a>
                        <?php } else { ?>
                            <span class="inactive">&nbsp;</span>
                        <?php } ?>
                    </td>
                    <td class="ta_r">
                        <a href="<?php echo $this->objUrl->getCurrent('action') . '/action/edit/id/' . $user['id']; ?>">Изменить</a>
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





