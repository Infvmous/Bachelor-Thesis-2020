<?php
/**
 * Страница удаления почтовых индексов
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/zones
 */
if ($objShipping->removePostCode($code['id'])) {
    $replace = array();
    $postCodes = $objShipping->getPostCodes($zone['id']);
    $replace['#postCodeList'] = Plugin::get(
        'admin'.DS.'post-code', array(
        'rows' => $postCodes,
        'objUrl' => $this->objUrl
        )
    );
    echo Helper::json(array('error' => false, 'replace' => $replace));
} else {
    throw new Exception('Запись не может быть удалена');
}