<?php

/**
 * Autoload
 * Файл автозагрузки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

require 'config.php';

/**
 * Конструктор, который Делит имена классов с помощью "_" и помещает в массив
 *
 * @param $class_name - имя класса
 *
 * @return void
 */
function __autoload($class_name)
{
    $class = explode("_", $class_name);

    // Путь к классу и расширение
    $path = implode("/", $class) . ".php";
    @include ROOT_PATH . DS . CLASSES_DIR . DS . $path;
}
