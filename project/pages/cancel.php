<?php

/**
 * Страница, на которую попадает пользователь после отмены покупки
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/cancel.php
 */

Login::restrictFront();
require '_header.php'; ?>

<h1>Ошибка!</h1>
<p>
    Возникла проблема с Вашим заказом
    </br>
    Пожалуйста, попробуйте снова!
</p>

<?php require '_footer.php' ?>