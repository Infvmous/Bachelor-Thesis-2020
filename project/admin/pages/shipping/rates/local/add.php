<?php
/**
 * Add
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/shipping
 */
$objForm = new Form();
$objValid = new Validation($objForm);
$objValid->expected = array('weight', 'cost');
$objValid->required = array('weight', 'cost');

try {
    if ($objValid->isValid()) {
        if ($objShipping->isDuplicateLocal($id, $zid, $objValid->post['weight'])) {
            $objValid->addToErrors('weight', 'Дубликат веса');
            throw new Exception('Дубликат веса');
        }
        $objValid->post['type'] = $id;
        $objValid->post['zone'] = $zid;
        $objValid->post['country'] = COUNTRY_LOCAL;
        if ($objShipping->addShipping($objValid->post)) {
            $replace = array();
            $shipping = $objShipping->getShippingByTypeZone($id, $zid);
            $replace['#shippingList'] = Plugin::get(
                'admin'.DS.'shipping-cost',
                array(
                'rows' => $shipping,
                'objUrl' => $this->objUrl
                )
            );
            echo Helper::json(array('error' => false, 'replace' => $replace));
        } else {
            $objValid->addToErrors('weight', 'Запись не может быть добавлена');
            throw new Exception('Запись не может быть добавлена');
        }
    } else {
        throw new Exception('Неверный запрос');
    }
} catch (Exception $e) {
    echo Helper::json(
        array('error' => true, 'validation' => $objValid->errorsMessages)
    );
}









