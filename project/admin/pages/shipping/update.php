<?php
/**
 * Update
 * Страница редактирования зон доставки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/shipping/update.html
 */
$objForm = new Form();

$value = $objForm->getPost('value');

if (!empty($value)) {
    if ($objShipping->updateType(array('name' => $value), $type['id'])) {
        echo Helper::json(array('error' => false));
    } else {
        throw new Exception('Record could not be updated');
    }
} else {
    throw new Exception('Invalid entry');
}