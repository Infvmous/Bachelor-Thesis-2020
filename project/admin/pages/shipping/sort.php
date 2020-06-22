<?php
/**
 * Sort
 * Страница сортировки зон доставки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/shipping/sort.html
 */
if (!empty($_POST)) {
    $errors = array();
    foreach ($_POST as $row) {
        foreach ($row as $order => $id) {
            $order++;
            if (!$objShipping->updateType(array('order' => $order), $id)) {
                $errors[] = $id;
            }
        }
    }

    if (empty($errors)) {
        echo Helper::json(array('error' => false));
    } else {
        throw new Exception(count($errors) . ' Запись не может быть обновлена');
    }
} else {
    throw new Exception('Не введен параметр');
}