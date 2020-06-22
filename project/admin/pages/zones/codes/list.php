<?php
/**
 * Страница отображения всех почтовых индексов
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/zones
 */

$postCodes = $objShipping->getPostCodes($zone['id']);

require_once '_header.php'; ?>

<h1>Почтовые индексы > <?php echo $zone['name']; ?></h1>

<form method="post" class="ajax" data-action="<?php echo $this->objUrl->getCurrent('call', false, array('call', 'add')); ?>">
    <table class="tbl_insert">
        <tr>
            <th><label for="post_code" class="valid_post_code"></label></th>
        </tr>
        <tr>
            <td>
                <input
                    type="text"
                    name="post_code"
                    id="post_code"
                    class="fld fldSmall"
                    placeholder="Индекс"
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

<div id="postCodeList">
    <?php echo Plugin::get('admin'.DS.'post-code', array('rows' => $postCodes, 'objUrl' => $this->objUrl)); ?>
</div>


<?php require_once '_footer.php'; ?>











