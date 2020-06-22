<?php
/**
 * Update
 * Редактирование зон доставки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/zones
 */
$objForm = new Form();

$value = $objForm->getPost('value');

if (!empty($value)) {
    if ($objShipping->updateZone(array('name' => $value), $zone['id'])) {
        echo Helper::json(array('error' => false));
    } else {
        throw new Exception('Запись не может быть изменена');
    }
} else {
    throw new Exception('Неверный ввод');
}