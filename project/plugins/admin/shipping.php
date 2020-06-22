<?php
/**
 * Shipping
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

if (!empty($data['rows'])) { ?>
    <table class="tbl_repeat" cellspacing="0" cellpadding="0">
        <tr>
            <th>Наименование</th>
            <th class="col_1">Тарифы</th>
            <th class="col_1">Активен</th>
            <th class="col_1"></th>
            <th class="col_1 ta_r"></th>
        </tr>

        <tbody id="rowsLocal" class="sortRows" data-url="<?php echo $data['urlSort']; ?>">

            <?php foreach ($data['rows'] as $item) { ?>

                <tr id="row-<?php echo $item['id']; ?>">
                    <td>
                        <span class="clickHideShow" data-show="#name-<?php echo $item['id']; ?>">
                            <?php echo Helper::encodeHtml($item['name']); ?>
                        </span>
                        <input
                            type="text"
                            name="name-<?php echo $item['id']; ?>"
                            id="name-<?php echo $item['id']; ?>"
                            class="fld fldList blurUpdateHideShow dn"
                            data-id="<?php echo $item['id']; ?>"
                            value="<?php echo $item['name']; ?>"
                        />
                    </td>
                    <td>
                        <select
                            name="rate-<?php echo $item['id']; ?>"
                            id="rate-<?php echo $item['id']; ?>"
                            class="fld fldSmall selectRedirect"
                        >
                            <option value="">Выберите страну..</option>
                            <?php if (!empty($data['countries'])) { ?>

                                <?php foreach ($data['countries'] as $crow) { ?>

                                    <option
                                        value="<?php echo $crow['id']; ?>"
                                        data-url="<?php echo $data['objUrl']->getCurrent('action', false, array('action', 'rates', 'id', $item['id'], 'zid', $crow['id'])); ?>"
                                    >
                                        <?php echo $crow['name']; ?>
                                    </option>

                                <?php } ?>

                            <?php } else if (!empty($data['zones'])) { ?>

                                <?php foreach ($data['zones'] as $zrow) { ?>

                                    <option
                                        value="<?php echo $zrow['id']; ?>"
                                        data-url="<?php echo $data['objUrl']->getCurrent('action', false, array('action', 'rates', 'id', $item['id'], 'zid', $zrow['id'])); ?>"
                                    >
                                        <?php echo $zrow['name']; ?>
                                    </option>

                                <?php } ?>

                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <a
                            href="#"
                            data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'active', 'id', $item['id'])); ?>"
                            class="clickReplace"
                        ><?php echo $item['active'] == 1 ? 'Да' : 'Нет'; ?></a>
                    </td>

                    <td>
                        <a
                            href="#"
                            data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'duplicate', 'id', $item['id'])); ?>"
                            class="clickCallReload"
                        >Клонировать</a>
                    </td>

                    <td>
                        <a
                            href="#"
                            data-url="<?php echo $data['objUrl']->getCurrent(array('action', 'id'), false, array('action', 'remove', 'id', $item['id'])); ?>"
                            class="clickAddRowConfirm red"
                            data-span="7"
                        >Удалить</a>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>

<?php } else { ?>
    <p>Записи не найдены.</p>
<?php } ?>








