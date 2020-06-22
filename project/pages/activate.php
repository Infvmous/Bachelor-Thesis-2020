<?php

/**
 * Страница с сообщением об успешной регистрации
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/activate.html
 */

$code = $this->objUrl->get('code');

if (!empty($code)) {

    $objUser = new User();
    $user = $objUser->getUserByHash($code);

    if (!empty($user)) {
        if ($user['active'] == 0) {
            if ($objUser->makeActive($user['id'])) {
                $message  = "<h1>Спасибо</h1>";
                $message .= "<p>Ваш аккаунт был успешно подтвержден.";
                $message .= "<br />";
                $message .= "Теперь вы можете";
                $message .= ' <a href="/checkout">войти';
                $message .= '</a> и продолжить покупки.</p>';
            } else {
                $message  = "<h1>Ошибка активации аккаунта</h1>";
                $message .= "<p>Возникла проблема с активацией Вашего аккаунта.";
                $message .= "<br />";
                $message .= "Пожалуйста, свяжитесь с администатором.</p>";
            }
        } else {
                $message  = "<h1>Аккаунт уже активирован</h1>";
                $message .= "<p>Ссылка активации аккаунта больше недействительна.";
                $message .= "</p>";
        }
    } else {
        Helper::redirect($this->objUrl->href('error'));
    }

    include '_header.php';
    echo $message;
    include '_footer.php';

} else {
    Helper::redirect($this->objUrl->href('error'));
}






