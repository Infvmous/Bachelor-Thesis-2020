<?php
/**
 * Страница добавления нового почтового индекса
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
$objValid = new Validation($objForm);
$objValid->expected = array('post_code');
$objValid->required = array('post_code');

try {
    if ($objValid->isValid()) {
        $postCode = strtoupper(
            Helper::alphaNumericalOnly($objValid->post['post_code'])
        );

        if ($objShipping->isDuplicatePostCode($postCode)) {
            $objValid->addToErrors('post_code', 'Дубликат почтового индекса');
            throw new Exception('Дубликат почтового индекса');
        }

        $array = array(
            'post_code' => $postCode,
            'zone' => $zone['id']
        );

        if ($objShipping->addPostCode($array)) {
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
            $objValid->addToErrors('post_code', 'Запись не может быть добавлена');
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













