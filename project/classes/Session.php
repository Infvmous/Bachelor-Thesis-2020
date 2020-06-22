<?php

/**
 * Session
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
 * Класс Session
 * Передает все переменные сессии
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Session
{
    /**
     * Добавляет товар в сессию
     *
     * @param $id  - идентификатор товара
     * @param $qty - количество товара
     *
     * @return void
     */
    public static function setItem($id, $qty = 1)
    {
        $_SESSION['cart'][$id]['qty'] = $qty;
    }

    /**
     * Удаляет товар из сесии
     *
     * @param $id  - идентификатор товара
     * @param $qty - количество товара
     *
     * @return void
     */
    public static function removeItem($id, $qty = null)
    {
        if ($qty != null && $qty < $_SESSION['cart'][$id]['qty']) {
            $_SESSION['cart'][$id]['qty'] = ($_SESSION['cart'][$id]['qty'] - $qty);
        } else {
            $_SESSION['cart'][$id] = null;
            unset($_SESSION['cart'][$id]);
        }
    }

    /**
     * Достает из сессии параметры и значения определенного объекта
     *
     * @param $name - имя объекта, находящегося в сессии
     *
     * @return сессию объекта
     */
    public static function getSession($name = null)
    {
        if (!empty($name)) {
            return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
        }
    }

    /**
     * Добавляет объект в сессию
     *
     * @param $name  - имя объекта
     * @param $value - значение объекта
     *
     * @return void
     */
    public static function setSession($name = null, $value = null)
    {
        if (!empty($name) && !empty($value)) {
            $_SESSION[$name] = $value;
        }
    }

    /**
     * Очищает сессию
     *
     * @param $id - идентификатор объекта в сессии
     *
     * @return void
     */
    public static function clear($id = null)
    {
        if (!empty($id)) {
            if (isset($_SESSION[$id])) {
                $_SESSION[$id] = null;
                unset($_SESSION[$id]);
            }
        } else {
            session_destroy();
        }
    }
}