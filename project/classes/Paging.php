<?php

/**
 * Paging
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
 * Класс Paging
 * Содержит в себе методы для осуществления пагинации
 * То есть переключения страниц сайта
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Paging
{
    public $objUrl;

    private $_records;
    private $_max_pp; // максимальное кол-во товаров на странице
    private $_number_of_pages;
    private $_number_of_records;
    private $_current;
    private $_offset = 0;

    public static $key = 'pg';
    public $url;

    /**
     * Конструктор пагинации, инициализирующий все методы этого класса
     *
     * @param $objUrl - объект класса Url
     * @param $rows   - количество товаров в категории
     * @param $max    - максимальное количество отображаемых товаров
     *
     * @return void
     */
    public function __construct($objUrl = null, $rows = null, $max = 10)
    {
        $this->objUrl = is_object($objUrl) ? $objUrl : new Url();
        $this->_records = $rows;
        $this->_number_of_records = count($this->_records);
        $this->_max_pp = $max;
        $this->url = $this->objUrl->getCurrent(self::$key);
        $current = $this->objUrl->get(self::$key);
        $this->_current = !empty($current) ? $current : 1;
        $this->_numberOfPages();
        $this->_getOffset();
    }

    /**
     * Автоматически присваивает значение деления между количеством отображаемых
     * товаров и максимальным количеством отображаемых товаров
     *
     * @return void
     */
    private function _numberOfPages()
    {
        $this->_number_of_pages = ceil($this->_number_of_records / $this->_max_pp);
    }

    /**
     * Автоматически присваивает значение результату умножения
     * между текущей страницей -1 и максимальным кол-вом записей на странице
     *
     * @return void
     */
    private function _getOffset()
    {
        $this->_offset = ($this->_current - 1) * $this->_max_pp;
    }

    /**
     * Отображает товары на странице
     *
     * @return товары на странице, в зависимости от max_pp
     */
    public function getRecords()
    {
        $out = array();

        if ($this->_number_of_pages > 1) {
            $last = ($this->_offset + $this->_max_pp); //последняя страница

            for ($i = $this->_offset; $i < $last; $i++) {
                if ($i < $this->_number_of_records) {
                    $out[] = $this->_records[$i];
                }
            }
        } else {
            $out = $this->_records;
        }
        return $out;
    }

    /**
     * Отображение ссылок на страницы
     * Первая страница, предыдущая, следующая, последняя
     *
     * @return меню с ссылками на страницы
     */
    private function _getLinks()
    {
        if ($this->_number_of_pages > 1) {
            $out = array();

            // Первая ссылка
            if ($this->_current > 1) {
                $out[] = "<a href=\"" . $this->url . PAGE_EXT . "\">Первая</a>";
            } else {
                $out[] = "<span>Первая</span>";
            }

            // Пред страница
            if ($this->_current > 1) {
                // Номер пред страницы
                $id = ($this->_current - 1);
                $url = $id > 1 ?
                    $this->url . "/" . self::$key . "/" . $id . PAGE_EXT :
                    $this->url . PAGE_EXT;
                $out[] = "<a href=\"{$url}\">&laquo;</a>";
            } else {
                $out[] = "<span>&laquo;</span>";
            }

            $out[] = "<a><strong>" . $this->_current . "</strong></a>";

            // След ссылка
            if ($this->_current != $this->_number_of_pages) {
                // Номер след страницы
                $id = ($this->_current + 1);
                $url = $this->url . "/" . self::$key . "/" . $id . PAGE_EXT;
                $out[] = "<a href=\"{$url}\">&raquo;</a>";
            } else {
                $out[] = "<span>&raquo;</span>";
            }

            // Последня страница
            if ($this->_current != $this->_number_of_pages) {
                $url = $this->url . "/" . self::$key . "/";
                $url .= $this->_number_of_pages . PAGE_EXT;
                $out[] = "<a href=\"{$url}\">Последняя</a>";
            } else {
                $out[] = "<span>Последняя</span>";
            }

            return "<li>" . implode("</li><li>", $out) . "</li>";
        }
    }

    /**
     * Отображение ссылок на страницы
     * Первая страница, предыдущая, следующая, последняя
     *
     * @return меню с ссылками на страницы
     */
    public function getPaging()
    {
        $links = $this->_getLinks();

        if (!empty($links)) {
            $out  = "<ul class=\"paging\">";
            $out .= $links;
            $out .= "</ul>";
            return $out;
        }
    }
}