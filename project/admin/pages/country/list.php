<?php
/**
 * Страница отображения списка стран
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/country
 */
$countries = $objCountry->getAll();

require_once '_header.php'; ?>

<h1>Список стран</h1>

<form method="post" class="ajax" data-action="<?php echo $this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'add')); ?>">
    <table class="tbl_insert">
        <tr>
            <th><label for="name" class="valid_name"></label></th>
        </tr>
        <tr>
            <td>
                <input
                    type="text"
                    name="name"
                    id="name"
                    class="fld"
                    placeholder="Название страны"
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

<div class="dev br_td">&nbsp;</div>

<form method="post" data-url="<?php echo $this->objUrl->getCurrent(array('action', 'id'), false, array('action', 'update', 'id')).'/'; ?>">
    <div id="countryList">
        <?php
        echo Plugin::get(
            'admin'.DS.'country', array(
                'rows' => $countries,
                'objUrl' => $this->objUrl
            )
        );
        ?>
    </div>
</form>

<?php require_once '_footer.php'; ?>















