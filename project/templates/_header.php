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
 * @link     https://darket-shop.ru
 */

$objCatalog = new Catalog();
$cats = $objCatalog->getCategories();

$objBusiness = new Business();
$business = $objBusiness->getBusiness();

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $this->meta_title; ?></title>
    <meta name="description" content="<?php echo $this->meta_description; ?>" />
    <meta name="keywords" content="<?php echo $this->meta_keywords; ?>" />
    <meta http-equiv="imagetoolbar" content="no" />
    <link href="/css/core.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div id="header">
        <div id="header_in">
            <h5><a href="/" id="logo"><?php echo $business['name']; ?></a></h5>
            <?php
            if (Login::isLogged(Login::$login_front)) {
                echo '<div id="logged_as"><strong>';
                echo Login::getFullNameFront(Session::getSession(Login::$login_front));
                echo '</strong> | <a href="';
                echo $this->objUrl->href('orders');
                echo '">Мои заказы</a>';
                echo ' | <a href="';
                echo $this->objUrl->href('logout');
                echo '">Выйти</a></div>';
            } else {
                echo '<div id="logged_as"><a href="';
                echo $this->objUrl->href('login');
                echo '">Войти / Регистрация</a></div>';
            }
            ?>
        </div>
    </div>
    <div id="outer">
        <div id="wrapper">
            <div id="left">

            <?php
            if ($this->objUrl->cpage != 'summary') {
                include_once 'cart_small.php';
            }
            ?>

            <?php if (!empty($cats)) { ?>

            <h2>Категории</h2>
            <ul id="navigation">
                <?php
                foreach ($cats as $cat) {
                    echo '<li><a href="';
                    echo $this->objUrl->href('catalog', array('category', $cat['identity']));
                    echo '"';
                    echo $this->objNavigation->active('catalog', array('category' => $cat['identity']));
                    echo '>';
                    echo Helper::encodeHtml($cat['name']);
                    echo '</a></li>';
                }
                ?>
            </ul>

            <?php } ?>

            </div>
            <div id="right">