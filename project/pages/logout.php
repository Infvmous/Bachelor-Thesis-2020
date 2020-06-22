<?php

/**
 * Выход из системы
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     hhttps://darket-shop.ru/logout.php
 */

Login::logout(Login::$login_front);
Login::restrictFront($this->objUrl);
