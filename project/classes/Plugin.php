<?php
/**
 * Plugin
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/catalog.html
 */

/**
 * Класс Plugin, наследует класс Application
 * Содержит в себе методы для работы с каталогами
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/catalog.html
 */
class Plugin
{
    /**
     * Позволяет включать файл
     *
     * @param $file - файл
     * @param $data - данные файла
     *
     * @return строку, сгенерированную из этого файла
     */
    public static function get($file = null, $data = null)
    {
        $path = PLUGINS_PATH.DS.$file.'.php';
        if (!empty($file) && is_file($path)) {
            ob_start();
            @include $path;
            return ob_get_clean();
        }
        return null;
    }
}