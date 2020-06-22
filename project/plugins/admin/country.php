<?php
/**
 * Плагин стран
 * плагин доставки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */
if (!empty($data['rows'])) {
    unset($data['objUrl']->params['action']);
    unset($data['objUrl']->params['id']);

    $objPaging = new Paging($data['objUrl'], $data['rows'], 25);
    $countries = $objPaging->getRecords();
    ?>

    <table class="tbl_repeat" cellspacing="0" cellpadding="0">
        <tr>
            <th>Наименование</th>
            <th class="col_1 ta_r">Активна</th>
            <th class="col_1 ta_r"></th>
        </tr>

        <tbody>
            <?php foreach ($countries as $item) { ?>

                <tr id="row-<?php echo $item['id']; ?>">

                    <td>
                        <span class="clickHideShow" data-show="#name-<?php echo $item['id']; ?>">
                            <?php echo Helper::encodeHtml($item['name']); ?>
                        </span>
                        <input
                            type="text"
                            name="name-<?php echo $item['id']; ?>"
                            id="name-<?php echo $item['id']; ?>"
                            class="fld blurUpdateHideShow dn"
                            data-id="<?php echo $item['id']; ?>"
                            value="<?php echo $item['name']; ?>"
                        />
                    </td>
                    <td class="ta_r">
                        <a
                            href="#"
                            data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'active', 'id', $item['id'])); ?>"
                            class="clickReplace"
                        ><?php echo $item['include'] == 1 ? 'Да' : 'Нет'; ?></a>
                    </td>
                    <td class="ta_r">
                        <a
                            href="#"
                            data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'remove', 'id', $item['id'])); ?>"
                            class="clickAddRowConfirm red"
                            data-span="3"
                        >Удалить</a>
                    </td>
                </tr>

            <?php } ?>

        </tbody>
    </table>

    <?php echo $objPaging->getPaging(); ?>

<?php } else { ?>
    <p>В настоящее время нет доступных записей.</p>
<?php } ?>













