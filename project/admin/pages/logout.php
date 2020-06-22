<?php
/**
 * Logout
 * Модуль выхода из контроль-панели
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/logout.php
 */
Login::logout(Login::$login_admin);
Login::restrictAdmin();
