<?php
/**
 * Страница доставки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/shipping
 */
$objValid = new Validation();
$objCountry = new Country();

$countries = $objCountry->getAllExceptLocal();
$zones = $objShipping->getZones();

$international = $objShipping->getTypes();
$local = $objShipping->getTypes(1);

$urlSort = $this->objUrl->getCurrent(
    array('action', 'id'), false, array('action', 'sort')
);

require '_header.php';
?>

<h1>Параметры доставки</h1>
<form method="post" class="ajax" data-action="<?php echo $this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'add')); ?>">
    <table cellspacing="0" cellpadding="0" class="tbl_insert">
        <tr>
            <th><label for="name" class="valid_name"></label></th>
        </tr>
        <tr>
            <td>
                <label for="local" class="fl_r">
                    <input type="checkbox" name="local" id="local" checked="checked" /> Локально
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="fld mr_r4"
                    placeholder="Название типа доставки"
                />
            </td>
        </tr>
        <tr>
            <td>
                <label for="btn_add" class="sbm sbm_blue fl_l">
                    <input type="submit" id="btn_add" class="btn" value="Добавить" />
                </label>
            </td>
        </tr>
    </table>
</form>

<div class="dev br_td"></div>
    <form method="post" data-url="<?php echo $this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'update', 'id')).'/'; ?>">
        <h3>Локальные</h3>
        <div id="typesLocal">
            <?php echo Plugin::get(
                'admin'.DS.'shipping', array(
                    'rows' => $local,
                    'zones' => $zones,
                    'objUrl' => $this->objUrl,
                    'urlSort' => $urlSort
                )
            ); ?>
        </div>

        <h3>Международные</h3>
        <div id="typesInternational">
            <?php echo Plugin::get(
                'admin'.DS.'shipping', array(
                    'rows' => $international,
                    'countries' => $countries,
                    'objUrl' => $this->objUrl,
                    'urlSort' => $urlSort
                )
            ); ?>
        </div>
    </form>

<?php require '_footer.php'; ?>