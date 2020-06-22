<?php

/**
 * Header
 * Шапка сайта
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel
 */
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Система управления содержанием</title>
    <meta http-equiv="imagetoolbar" content="no" />
    <link href="/css/core.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="header">
    <div id="header_in">
        <h5><a href="/">Система управления содержанием</a></h5>
        <?php
        if (Login::isLogged(Login::$login_admin)) {
            echo '<div id="logged_as">';
            echo '<strong>';
            echo $this->objAdmin->getFullNameAdmin(
                Session::getSession(Login::$login_admin)
            );
            echo '</strong> | ';
            echo '<a href="/panel/logout">Выйти</a></div>';
        } else {
            echo '<div id="logged_as"><a href="/panel/">Войти</a></div>';
        }
        ?>
    </div>
</div>
<div id="outer">
    <div id="wrapper">
        <div id="left">
            <?php if (Login::isLogged(Login::$login_admin)) { ?>
            <h2>Навигация</h2>
            <div class="dev br_td">&nbsp;</div>
                <ul id="navigation">
                    <li>
                        <a href="/panel/products"
                        <?php echo $this->objNavigation->active('products'); ?>>
                        Товары
                        </a>
                    </li>
                    <li>
                        <a href="/panel/categories"
                        <?php echo $this->objNavigation->active('categories'); ?>>
                        Категории
                        </a>
                    </li>
                    <li>
                        <a href="/panel/orders"
                        <?php echo $this->objNavigation->active('orders'); ?>>
                        Заказы
                        </a>
                    </li>
                    <li>
                        <a href="/panel/clients"
                        <?php echo $this->objNavigation->active('clients'); ?>>
                        Список клиентов
                        </a>
                    </li>
                    <li>
                        <a href="/panel/business"
                        <?php echo $this->objNavigation->active('business'); ?>>
                        Бизнес-профиль
                        </a>
                    </li>
                    <li>
                        <a href="/panel/shipping"
                        <?php echo $this->objNavigation->active('shipping'); ?>>
                        Параметры доставки
                        </a>
                    </li>
                    <li>
                        <a href="/panel/zones"
                        <?php echo $this->objNavigation->active('zones'); ?>>
                        Зоны доставки
                        </a>
                    </li>
                    <li>
                        <a href="/panel/country"
                        <?php echo $this->objNavigation->active('country'); ?>>
                        Страны
                        </a>
                    </li>
                </ul>
            <?php } else { ?>
                &nbsp;
            <?php } ?>
        </div>
        <div id="right">