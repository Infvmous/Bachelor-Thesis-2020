<?php
/**
 * List
 * Страница управления информацией о категориях
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/categories.html
 */

$objCatalog = new Catalog();
$categories = $objCatalog->getCategories(1);

$objPaging = new Paging($this->objUrl, $categories, 15);
$rows = $objPaging->getRecords();

require '_header.php'; ?>

<h1>Список категорий</h1>
<!-- ДОБАВИТЬ КАТЕГОРИЮ -->
<p>
    <a href="<?php echo $this->objUrl->getCurrent(
        array('action', 'id')
    ) . '/action/add'; ?>">Добавить новую категорию</a>
</p>

<?php if (!empty($rows)) { ?>

<table cellpadding="0" cellspacing="0" class="tbl_repeat">

<tr>
    <th class="col_5">Id</th>
    <th>Категория</th>
    <th class="col_15 ta_r"></th>
    <th class="col_5 ta_r"></th>
</tr>

    <?php foreach ($rows as $category) { ?>

        <tr>
            <td><?php echo $category['id']; ?></td>
            <td><?php echo Helper::encodeHtml($category['name']); ?></td>
            <td>
                <a href="<?php echo $this->objUrl->getCurrent(array('action', 'id')) . '/action/remove/id/' . $category['id']; ?>" class="red">Удалить</a>
            </td>
            <td>
                <a href="<?php echo $this->objUrl->getCurrent(array('action', 'id')) . '/action/edit/id/' . $category['id']; ?>">Изменить</a>
            </td>
        </tr>

    <?php } ?>

</table>

    <?php echo $objPaging->getPaging(); ?>

    <?php
} else { ?>
    <p>В настоящее время нет созданных категорий.</p>
<?php }
    require '_footer.php';






