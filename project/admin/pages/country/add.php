<?php
/**
 * Add
 * страница добавления новых стран в БД
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
$objValid = new Validation($objForm);
$objValid->expected = array('name');
$objValid->required = array('name');

try {
    if ($objValid->isValid()) {
        if ($objCountry->addCountry($objValid->post)) {
            $replace = array();
            $countries = $objCountry->getAll();
            $replace['#countryList'] = Plugin::get(
                'admin'.DS.'country', array(
                'rows' => $countries,
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