<?php
/**
 * Плагин почтовых индексов
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/zones
 */
if (!empty($data['rows'])) {
    unset($data['objUrl']->params['call']);
    $objPaging = new Paging($data['objUrl'], $data['rows'], 25);
    $postCodes = $objPaging->getRecords();
?>

<table class="tbl_repeat" cellspacing="0" cellpadding="0">
   <tr>
        <th>Почтовый индекс</th>
        <th class="col_1 ta_r"></th>
    </tr>
    <tbody>

        <?php foreach ($postCodes as $item) { ?>

            <tr id="row-<?php echo $item['id']; ?>">
                <td>
                    <?php echo $item['post_code']; ?>
                </td>
                <td class="ta_r">
                    <a
                        href="#"
                        class="clickAddRowConfirm red"
                        data-url="<?php echo $data['objUrl']->getCurrent(array('call', 'cid'), false, array('call', 'remove', 'cid', $item['id'])); ?>"
                        data-span="2"
                    >Удалить</a>
                </td>
            </tr>

        <?php } ?>

    </tbody>
</table>

    <?php echo $objPaging->getPaging(); ?>

<?php } else { ?>
    <p>В настоящее время нет доступных почтовых индексов.</p>
<?php } ?>















