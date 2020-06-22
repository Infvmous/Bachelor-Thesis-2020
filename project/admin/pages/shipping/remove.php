<?php
/**
 * Страница удаления типов доставки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/shipping/remove.html
 */
if ($objShipping->removeType($type['id'])) {
    echo Helper::json(array('error' => false));
} else {
    throw new Exception('Запись не может быть удалена');
}