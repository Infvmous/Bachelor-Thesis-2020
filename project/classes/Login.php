<?php

/**
 * Login
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/login.html
 */

/**
 * Класс Login
 * Работает с авторизацией пользователя
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/login.html
 */

class Login
{
    public static $login_page_front = "/login";
    public static $dashboard_front = "/orders";
    public static $login_front = "cid"; //client id

    public static $login_page_admin = "/panel/";
    public static $dashboard_admin = "/panel/products";
    public static $login_admin = "aid"; // admin id

    public static $valid_login = "valid";

    public static $referrer = "refer";

    /**
     * Проверка вошел пользователь в систему или нет
     *
     * @param $case - идентификатор пользователя
     *
     * @return true, если в массиве _SESSION найдена запись о пользователе
     * false, если нет
     */
    public static function isLogged($case = null)
    {
        if (!empty($case)) {
            if (isset($_SESSION[self::$valid_login])
                && $_SESSION[self::$valid_login] == 1
            ) {
                return isset($_SESSION[$case]) ? true : false;
            }
            return false;
        }
        return false;
    }

    /**
     * Записывает в сессию client id и valid login, а затем редиректит
     *
     * @param $id  - идентификатор пользователя
     * @param $url - url, куда перекинет пользователя после входа в систему
     *
     * @return void
     */
    public static function loginFront($id = null, $url = null)
    {
        if (!empty($id)) {
            $url = !empty($url) ? $url : self::$dashboard_front . PAGE_EXT;
            $_SESSION[self::$login_front] = $id;
            $_SESSION[self::$valid_login] = 1;
            Helper::redirect($url);
        }
    }

    /**
     * Метод авторизации админа
     *
     * @param $id  - идентификатор пользователя
     * @param $url - реферальная ссылка
     *
     * @return null
     */
    public static function loginAdmin($id = null, $url = null)
    {
        if (!empty($id)) {
            $url = !empty($url) ? $url : self::$dashboard_admin;
            $_SESSION[self::$login_admin] = $id;
            $_SESSION[self::$valid_login] = 1;
            Helper::redirect($url);
        }
    }

    /**
     * Ограничивает вход пользователям, не вошедшим в систему
     *
     * @param $objUrl - обьект Url класса
     *
     * @return void
     */
    public static function restrictFront($objUrl = null)
    {
        if (!self::isLogged(self::$login_front)) {
            $objUrl = is_object($objUrl) ? $objUrl : new Url();
            $url = $objUrl->cpage != "logout" ?
                self::$login_page_front . "/" . self::$referrer . "/" . $objUrl->cpage . PAGE_EXT :
                self::$login_page_front . PAGE_EXT;
            Helper::redirect($url);
        }
    }

    /**
     * Ограничивает доступ на страницу, если не залогинен как админ
     *
     * @return void
     */
    public static function restrictAdmin()
    {
        if (!self::isLogged(self::$login_admin)) {
            Helper::redirect(self::$login_page_admin);
        }
    }

    /**
     * Преобразовывает пароль в HASH
     *
     * @param $string - строка с паролем
     *
     * @return пароль хешированный
     */
    public static function stringToHash($string = null)
    {
        if (!empty($string)) {
            return hash('sha512', $string);
        }
    }

    /**
     * Получает полное имя залогиненного юзера
     *
     * @param $id - айди юзера
     *
     * @return имя юзера
     */
    public static function getFullNameFront($id = null)
    {
        if (!empty($id)) {
            $objUser = new User();
            $user = $objUser->getUser($id);
            if (!empty($user)) {
                return $user['first_name']." ".$user['last_name'];
            }
        }
    }

    /**
     * Очищает сессию пользователя
     *
     * @param $case - айди юзера
     *
     * @return null
     */
    public static function logout($case = null)
    {
        if (!empty($case)) {
            $_SESSION[$case] = null;
            $_SESSION[self::$valid_login] = null;
            unset($_SESSION[$case]);
            unset($_SESSION[self::$valid_login]);
        } else {
            session_destroy();
        }
    }
}