<?php

/**
 * Url
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
 * Класс Url
 * Класс, содержащий в себе методы для работы с URL веб приложения
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Url
{
    public $key_page = 'page';
    public $key_modules = array('panel');
    public $module = 'front';
    public $main = 'index';
    public $cpage = 'index';
    public $c = 'login';
    public $a = 'action';
    public $params = array();
    public $paramsRaw = array();
    public $stringRaw;

    /**
     * Конструктор класса
     *
     * @return null
     */
    public function __construct()
    {
        $this->process();
    }

    /**
     * Инициализация статического URL
     *
     * @return null
     */
    public function process()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (!empty($uri)) {
            $uriQ = explode('?', $uri);
            $uri = $uriQ[0];
            if (count($uriQ) > 1) {
                $this->stringRaw = $uriQ[1];
                $uriRaw = explode('&', $uriQ[1]);
                if (count($uriRaw) > 1) {
                    foreach ($uriRaw as $key => $row) {
                        $this->splitRaw($row);
                    }
                } else {
                    $this->splitRaw($uriRaw[0]);
                }
            }
            $uri = Helper::clearString($uri, PAGE_EXT);
            $firstChar = substr($uri, 0, 1);
            if ($firstChar == '/') {
                $uri = substr($uri, 1);
            }
            $lastChar = substr($uri, -1);
            if ($lastChar == '/') {
                $uri = substr($uri, 0, -1);
            }
            if (!empty($uri)) {
                $uri = explode('/', $uri);
                $first = array_shift($uri);
                if (in_array($first, $this->key_modules)) {
                    $this->module = $first;
                    $first = empty($uri) ? $this->main : array_shift($uri);
                }
                $this->main = $first;
                $this->cpage = $this->main;
                if (count($uri) > 1) {
                    $pairs = array();
                    foreach ($uri as $key => $value) {
                        $pairs[] = $value;
                        if (count($pairs) > 1) {
                            if (!Helper::isEmpty($pairs[1])) {
                                if ($pairs[0] == $this->key_page) {
                                    $this->cpage = $pairs[1];
                                } else if ($pairs[0] == 'c') {
                                    $this->c = $pairs[1];
                                } else if ($pairs[0] == 'a') {
                                    $this->a = $pairs[1];
                                }
                                $this->params[$pairs[0]] = $pairs[1];
                            }
                            $pairs = array();
                        }
                    }
                }
            }
        }
    }

    /**
     * Разделить "RAW" параметр Url (?param=value)
     *
     * @param $item - массив параметров или url параметр
     *
     * @return null
     */
    public function splitRaw($item = null)
    {
        if (!empty($item) && !is_array($item)) {
            $itemRaw = explode('=', $item);
            if (count($itemRaw) > 1 && !Helper::isEmpty($itemRaw[1])) {
                $this->paramsRaw[$itemRaw[0]] = $itemRaw[1];
            }
        }
    }

    /**
     * Получить URL параметр
     *
     * @param $param - url параметр
     *
     * @return null
     */
    public function getRaw($param = null)
    {
        if (!empty($param) && array_key_exists($param, $this->paramsRaw)) {
            return $this->paramsRaw[$param];
        }
    }

    /**
     * Получить URL
     *
     * @param $param - url параметр
     *
     * @return null
     */
    public function get($param = null)
    {
        if (!empty($param) && array_key_exists($param, $this->params)) {
            return $this->params[$param];
        }
    }

    /**
     * Редирект по статическому URL
     *
     * @param $main   - название файла, по которому идет поиск, чтобы открыть страницу
     * @param $params - массив url параметров
     *
     * @return null
     */
    public function href($main = null, $params = null)
    {
        if (!empty($main)) {
            $out = array($main);
            if (!empty($params) && is_array($params)) {
                foreach ($params as $key => $value) {
                    $out[] = $value;
                }
            }
            return '/' . implode('/', $out) . PAGE_EXT;
        }
    }

    /**
     * Редирект по статическому URL
     *
     * @param $exclude   -
     * @param $extension - расширение файла (html по умолчанию)
     * @param $add       -
     *
     * @return null
     */
    public function getCurrent($exclude = null, $extension = false, $add = null)
    {
        $out = array();
        if ($this->module != 'front') {
            $out[] = $this->module;
        }
        $out[] = $this->main;
        if (!empty($this->params)) {
            if (!empty($exclude)) {
                $exclude = Helper::makeArray($exclude);
                foreach ($this->params as $key => $value) {
                    if (!in_array($key, $exclude)) {
                        $out[] = $key;
                        $out[] = $value;
                    }
                }
            } else {
                foreach ($this->params as $key => $value) {
                        $out[] = $key;
                        $out[] = $value;
                }
            }
        }
        if (!empty($add)) {
            $add = Helper::makeArray($add);
            foreach ($add as $item) {
                $out[] = $item;
            }
        }
        $url = '/' . implode('/', $out);
        $url .= $extension ? PAGE_EXT : null;
        return $url;
    }
}
