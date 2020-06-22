<?php
/**
 * Remove
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/shipping
 */
$rid = $this->objUrl->get('rid');

if (!empty($rid)) {
    $record = $objShipping->getShippingByIdTypeZone($rid, $id, $zid);
    if (empty($record)) {
        throw new Exception('Такой записи не существует');
    }
    if ($objShipping->removeShipping($record['id'])) {
        $replace = array();
        $shipping = $objShipping->getShippingByTypeZone($id, $zid);
        $replace['#shippingList'] = Plugin::get(
            'admin'.DS.'shipping-cost', array(
            'rows' => $shipping,
            'objUrl' => $this->objUrl
            )
        );
        echo Helper::json(array('error' => false, 'replace' => $replace));
    } else {
        throw new Exception('Запись не может быть удалена');
    }
} else {
    throw new Exception('Параметр не найден');
}