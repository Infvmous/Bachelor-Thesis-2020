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
 * @link     http://localhost
 */

/**
 * Класс Url
 * Класс, содержащий в себе методы для работы с URL веб приложения
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://localhost
 */

class Url
{
    public static $page = "page";
    public static $folder = PAGES_DIR;
    public static $params = array();

    /**
     * Возвращает параметр URL, если он существует
     *
     * @param $par - параметр URL
     *
     * @return параметр
     */
    public static function getParam($par)
    {
        return isset($_GET[$par]) && $_GET[$par] != "" ?
            $_GET[$par] : null;
    }

    /**
     * Возвращает URL текущей страницы,
     * если такая существует, если нет, то открывает index
     *
     * @return URL текущей страницы, если он существует
     */
    public static function currentPage()
    {
        return isset($_GET[self::$page]) ? $_GET[self::$page] :'index';
    }

    /**
     * Возвращает название файла определенной страницы
     * Если названия файла нет, открывает страницу ошибки
     *
     * @return URL определенной страницы, если файл этой страницы существует
     */
    public static function getPage()
    {
        $page = self::$folder . DS . self::currentPage() . ".php";
        $error = self::$folder . DS . "error.php";
        return is_file($page) ? $page : $error;
    }

    /**
     * Cобирает все параметры, значения и помещает в массив
     *
     * @return void
     */
    public static function getAll()
    {
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                if (!empty($value)) {
                    self::$params[$key] = $value;
                }
            }
        }
    }

    /**
     * Отображает текущую страницу:
     * /?page=about&category=coats
     * Если входной параметр по умолчанию null
     *
     * Отобразит текущую страницу:
     * /?page=about
     * Если входной параметр был категорией
     *
     * @param $remove - номер страницы
     *
     * @return кнопку удалить из корзины, или добавить в зависимости от того,
     * добавлен товар уже в корзину, или нет
     */
    public static function getCurrentUrl($remove = null)
    {
        self::getAll();
        $out = array();

        if (!empty($remove)) {
            $remove = !is_array($remove) ? array($remove) : $remove;

            foreach (self::$params as $key => $value) {
                if (in_array($key, $remove)) {
                    unset(self::$params[$key]);
                }
            }
        }

        foreach (self::$params as $key => $value) {
            $out[] = $key . "=" . $value;
        }

        return "/?" . implode("&", $out);
    }

    /**
     * Метод получения реферальной ссылки
     *
     * @return url с ссылкой на страницу
     */
    public static function getReferrerUrl()
    {
        $page = self::getParam(Login::$referrer);
        return !empty($page) ? "/?page={$page}" : null;
    }

    /**
     * Метод получения параметров для поиска
     *
     * @param $remove - массив с ключем srch и значение = номеру текущей страницы
     *
     * @return url с ссылкой на страницу
     */
    public static function getParamsForSearch($remove = null)
    {
        self::getAll();
        $out = array();
        if (!empty(self::$params)) {
            foreach (self::$params as $key => $value) {
                if (!empty($remove)) {
                    $remove = is_array($remove) ? $remove : array($remove);
                    if (!in_array($key, $remove)) {
                        $input  = '<input  type="hidden" name="'.$key;
                        $input .= '" value="'.$value.'" />';
                        $out[] = $input;
                    }
                } else {
                    $input  = '<input  type="hidden" name="'.$key;
                    $input .= '" value="'.$value.'" />';
                    $out[] = $input;
                }
            }
            return implode("", $out);
        }
    }
}
