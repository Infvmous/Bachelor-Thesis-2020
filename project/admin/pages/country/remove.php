<?php
/**
 * Remove
 * страница удаления стран из БД
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/country
 */

if ($objCountry->remove($country['id'])) {
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
    throw new Exception('Запись не может быть удалена');
}