<?php
/**
 * Autoloader
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

/**
 * Класс Autoloader
 * Замена autoloader.php
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */
class AutoLoader
{
    /**
     * Метод инициализации классов
     *
     * @param $className - имя класса
     *
     * @return null
     */
    public static function load($className)
    {
        $class = str_replace('\\', DS, ltrim($className, '\\'));
        $class = str_replace('_', DS, $className) . '.php';
        @include_once CLASSES_PATH . DS . $class;
    }
}