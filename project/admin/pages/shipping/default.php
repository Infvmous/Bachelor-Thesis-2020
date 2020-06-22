<?php
/**
 * Default
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/shipping/default.html
 */
if ($type['default'] == 1) {
    throw new Exception('Операция не разрешена');
}
if ($objShipping->setTypeDefault($type['id'], $type['local'])) {
    echo Helper::json(array('error' => false));
} else {
    throw new Exception('Запись не может быть изменена');
}