<?php
/**
 * List
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/shipping
 */
$shipping = $objShipping->getShippingByTypeCountry($id, $zid);

require '_header.php'; ?>

<h1>Тарифы для > <?php echo $country['name']; ?> > <?php echo $type['name']; ?></h1>

<form method="post" class="ajax" data-action="<?php echo $this->objUrl->getCurrent('call', false, array('call', 'add')); ?>">
    <table cellspacing="0" cellpadding="0" class="tbl_insert">
        <tr>
            <th><label for="weight" class="valid_weight"></label></th>
            <th><label for="cost" class="valid_cost"></label></th>
        </tr>
        <tr>
            <td>
                <input
                    type="text"
                    name="weight"
                    id="weight"
                    class="fld fldSmall"
                    placeholder="Лимит веса"
                />
            </td>
            <td>
                <input
                    type="text"
                    name="cost"
                    id="cost"
                    class="fld fldSmall"
                    placeholder="Цена"
                />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <label for="btn_add" class="sbm sbm_blue fl_l">
                    <input type="submit" id="btn_add" class="btn" value="Добавить" />
                </label>
            </td>
        </tr>
    </table>
</form>

<div class="dev br_td">&nbsp;</div>

<div id="shippingList">
    <?php echo Plugin::get('admin'.DS.'shipping-cost', array('rows' => $shipping, 'objUrl' => $this->objUrl)); ?>
</div>

<?php require '_footer.php' ; ?>









