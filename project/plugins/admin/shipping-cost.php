<?php
/**
 * Shipping-cost
 * плагин тарифов доставки
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

<table class="tbl_repeat">
    <tr>
        <th class="col_15 ta_r">От</th>
        <th class="col_15 ta_r">До</th>
        <th class="ta_r">Цена</th>
        <th class="col_1 ta_r"></th>
    </tr>
    <tbody>
        <?php foreach ($data['rows'] as $item) { ?>
            <tr id="row-<?php echo $item['id']; ?>">
                <td class="ta_r">
                    <?php
                    echo round($item['weight_from'], 2);
                    echo ' кг'
                    ?>
                </td>
                <td class="ta_r">
                    <?php
                    echo round($item['weight'], 2);
                    echo ' кг'
                    ?>
                </td>
                <td class="ta_r">
                    <?php
                    echo Catalog::$currency;
                    echo number_format($item['cost'], 2);
                    ?>
                </td>
                <td class="ta_r">
                    <a
                        href="#"
                        class="clickAddRowConfirm red"
                        data-url="<?php echo $data['objUrl']->getCurrent('call', false, array('call', 'remove', 'rid', $item['id'])); ?>"
                        data-span="4"
                    >Удалить</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php } else { ?>
    <p>В настоящее время нет записей, связанных с этим типом доставки.</p>
<?php } ?>



