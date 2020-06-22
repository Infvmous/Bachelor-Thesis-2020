<?php
/**
 * Add
 * Страница добавления новых зон доставки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/clients/action/edit.html
 */

$objForm = new Form();
$objValid = new Validation($objForm);
$objValid->expected = array('name');
$objValid->required = array('name');

try {
    if ($objValid->isValid()) {
        if ($objShipping->addZone($objValid->post)) {
            $replace = array();
            $zones = $objShipping->getZones();
            $replace['#zoneList'] = Plugin::get(
                'admin'.DS.'zones', array(
                'rows' => $zones,
                'objUrl' => $this->objUrl
                )
            );
            echo Helper::json(array('error' => false, 'replace' => $replace));
        } else {
            $objValid->addToErrors('name', 'Запись не может быть добавлена');
            throw new Exception('Запись не может быть добавлена');
        }
    } else {
        throw new Exception('Неверный ввод');
    }
} catch (Exception $e) {
    echo Helper::json(
        array('error' => true, 'validation' => $objValid->errorsMessages)
    );
}