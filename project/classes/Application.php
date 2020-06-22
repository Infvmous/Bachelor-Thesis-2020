<?php

/**
 * Application
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
 * Класс Application
 * Содержит в себе конструктор для подключения к базе данных
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Application
{
    public $db;

    /**
     * Конструктор приложения, создает экземпляр класса Dbase
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = new Dbase();
    }
}
