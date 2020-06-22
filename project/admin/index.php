<?php

/**
 * Index
 * PHP документ, подгружающий autoload и ядро веб приложения
 * на всех страницах админ панели
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel
 */

require '../include/config.php';

$core = new Core();
$core->run();