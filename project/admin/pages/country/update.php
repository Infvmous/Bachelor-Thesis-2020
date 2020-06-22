<?php
/**
 * Update
 * страница редактирования списка стран
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/country
 */

$objForm = new Form();
$value = $objForm->getPost('value');
if (!empty($value)) {
    if ($objCountry->update(array('name' => $value), $country['id'])) {
        echo Helper::json(array('error' => false));
    } else {
        throw new Exception('Запись не может быть обновлена');
    }
} else {
    throw new Exception('Неверный ввод');
}