<?php
/**
 * List
 * Страница управления информацией о товарах
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/products.html
 */

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
        $products = $objCatalog->getAllProducts($srch);
        $empty = 'По запросу &laquo;' . $srch . '&raquo; ничего не найдено<br />';
        $empty .= 'Проверьте написан ли запрос без ошибок.<br /><br />';
        $empty .= '<a href="/admin/products">В список товаров</a>';
    } else {
        $products = $objCatalog->getAllProducts();
        $empty = 'Список товаров пуст';
    }
    $objPaging = new Paging($this->objUrl, $products, 20);
    $rows = $objPaging->getRecords();

    include '_header.php';
    ?>

    <h1>Список товаров</h1>

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
                        placeholder="Поиск товара"
                    />
                </td>
                <td>
                    <label for="btn_add" class="sbm sbm_blue fl_l">
                    <input type="submit" id="btn_add" class="btn" value="Найти" />
                    </label>
                </td>
            </tr>
        </table>
    </form>

    <div class="dev br_td">&#160;</div>

    <!-- ДОБАВИТЬ ПРОДУКТ -->
    <p>
        <a href="<?php echo $this->objUrl->getCurrent('action') . '/action/add'; ?>">
            Добавить новый товар
        </a>
    </p>

    <?php if (!empty($rows)) { ?>

    <table cellpadding="0" cellspacing="0" class="tbl_repeat">

    <tr>
        <th class="col_5">Id</th>
        <th>Товар</th>
        <th class="col_15 ta_r"></th>
        <th class="col_5 ta_r"></th>
    </tr>

        <?php foreach ($rows as $product) { ?>

            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo Helper::encodeHtml($product['name']); ?></td>
                <td class="ta_r">
                    <a href="<?php echo $this->objUrl->getCurrent('action') . '/action/remove/id/' . $product['id']; ?>" class="red">Удалить</a>
                </td>
                <td class="ta_r">
                    <a href="<?php echo $this->objUrl->getCurrent('action') . '/action/edit/id/' . $product['id']; ?>">Изменить</a>
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





