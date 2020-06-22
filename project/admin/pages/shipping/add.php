<?php
/**
 * Add
 * Страница добавления зон доставки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/shipping/add.html
 */
$objForm = new Form();
$objValid = new Validation($objForm);
$objValid->expected = array('name', 'local');
$objValid->required = array('name');

try {
    if ($objValid->isValid()) {
        if ($objShipping->addType($objValid->post)) {
            $replace = array();
            $urlSort = $this->objUrl->getCurrent(
                array('action', 'id'), false, array('action', 'sort')
            );
            if (!empty($objValid->post['local'])) {
                $rows = $objShipping->getTypes(1);
                $zones = $objShipping->getZones();
                $replace['#typesLocal'] = Plugin::get(
                    'admin'.DS.'shipping', array(
                        'rows' => $rows,
                        'objUrl' => $this->objUrl,
                        'urlSort' => $urlSort,
                        'zones' => $zones
                    )
                );
            } else {
                $rows = $objShipping->getTypes();
                $objCountry = new Country();
                $countries = $objCountry->getAllExceptLocal();
                $replace['#typesInternational'] = Plugin::get(
                    'admin'.DS.'shipping', array(
                        'rows' => $rows,
                        'objUrl' => $this->objUrl,
                        'urlSort' => $urlSort,
                        'countries' => $countries
                    )
                );
            }
            echo Helper::json(array('error' => false, 'replace' => $replace));
        } else {
            $objValid->add2Errors('name', 'Запись не может быть добавлена');
            throw new Exception('Запись не может быть добавлена');
        }
    } else {
        throw new Exception('Missing parameter');
    }
} catch (Exception $e) {
    echo Helper::json(
        array('error' => true, 'validation' => $objValid->errorsMessages)
    );
}