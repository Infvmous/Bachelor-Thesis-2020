<?php

/**
 * Index
 * Главная страница сайта
 * Здесь подгрудаются основные модули, которые отображаются на всех страницах
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

require_once '_header.php';
    Helper::redirect('/catalog/category/all.html');
require_once '_footer.php';
?>
