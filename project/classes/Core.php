<?php

/**
 * Core
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
 * Класс Core
 * Ядро веб-приложения
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Core
{
    public $objUrl;
    public $objNavigation;
    public $objAdmin;

    public $meta_title = 'DARKET shop';
    public $meta_description = 'DARKET shop';
    public $meta_keywords = 'DARKET shop';

    /**
     * Конструктор класса
     *
     * @return void
     */
    public function __construct()
    {
        $this->objUrl = new Url();
        $this->objNavigation = new Navigation($this->objUrl);
    }

    /**
     * Включает буферизацию, инициализирует URL для фронтенда и админ-панели
     *
     * @return void
     */
    public function run()
    {
        ob_start();

        switch($this->objUrl->module) {
        case 'panel':
            set_include_path(
                implode(
                    PATH_SEPARATOR, array(
                        realpath(ROOT_PATH . DS . 'admin' . DS . TEMPLATES_DIR),
                        realpath(ROOT_PATH . DS . 'admin' . DS . PAGES_DIR),
                        get_include_path()
                    )
                )
            );
            $this->objAdmin = new Admin();
            // @ отключает сообщения об ошибке о несуществующем URL
            include_once ROOT_PATH . DS . 'admin' . DS . PAGES_DIR . DS . $this->objUrl->cpage . '.php';
            break;
        default:
            set_include_path(
                implode(
                    PATH_SEPARATOR, array(
                        realpath(ROOT_PATH . DS . TEMPLATES_DIR),
                        realpath(ROOT_PATH . DS . PAGES_DIR),
                        get_include_path()
                    )
                )
            );
            // @ отключает сообщения об ошибке о несуществующем URL
            include_once ROOT_PATH . DS . PAGES_DIR . DS . $this->objUrl->cpage . '.php';
        }

        ob_get_flush();
    }
}
