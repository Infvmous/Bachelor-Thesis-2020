<?php

/**
 * Config
 * Конфигурационный файл веб приложения
 * Определяет пути к директориям, URL сайта, включает сессию
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

if (!isset($_SESSION)) {
    session_start();
}

// ID локальной страны в БД
defined("COUNTRY_LOCAL")
    || define("COUNTRY_LOCAL", 134);

// По умолчанию НДС выключено для международных покупок
// НДС работает только при покупок внутри страны (локально)
defined("INTERNATIONAL_VAT")
    || define("INTERNATIONAL_VAT", false);

defined("PAGE_EXT")
    || define("PAGE_EXT", ".html");

defined("SITE_URL")
    || define("SITE_URL", "http://" . $_SERVER['SERVER_NAME']);

defined("DS")
    || define("DS", DIRECTORY_SEPARATOR);

defined("ROOT_PATH")
    || define("ROOT_PATH", realpath(dirname(__FILE__) . DS . ".." . DS));

defined("CLASSES_DIR")
    || define("CLASSES_DIR", "classes");

defined("CLASSES_PATH")
    || define("CLASSES_PATH", ROOT_PATH . DS . CLASSES_DIR);

// Путь к плагинам
defined("PLUGINS_PATH")
    || define("PLUGINS_PATH", ROOT_PATH . DS . "plugins");

defined("PAGES_DIR")
    || define("PAGES_DIR", "pages");

defined("MODULES_DIR")
    || define("MODULES_DIR", "modules");

defined("INCLUDE_DIR")
    || define("INCLUDE_DIR", "include");

defined("TEMPLATES_DIR")
    || define("TEMPLATES_DIR", "templates");

defined("EMAILS_PATH")
    || define("EMAILS_PATH", ROOT_PATH . DS . "emails");

defined("CATALOG_PATH")
    || define("CATALOG_PATH", ROOT_PATH . DS . "media" . DS . "catalog");

set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(ROOT_PATH . DS . MODULES_DIR),
            realpath(ROOT_PATH . DS . INCLUDE_DIR),
            get_include_path()
        )
    )
);

require_once CLASSES_PATH . DS . 'Autoloader.php';

spl_autoload_register(array('Autoloader', 'load'));
