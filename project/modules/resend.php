<?php
/**
 * Resend
 * Модуль отвечающий за повторную отправку письма с подтверждением аккаунта
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/modules/resend.php
 */
require_once '../include/config.php';

$objUrl = new Url();
$id = $objUrl->getRaw('id');
if (!empty($id)) {
    $objUser = new User($objUrl);
    $user = $objUser->getUser($id);

    if (!empty($user)) {
        $objEmail = new Email($objUrl);

        if ($objEmail->process(
            1, array(
                'email'      => $user['email'],
                'first_name' => $user['first_name'],
                'last_name'  => $user['last_name'],
                'hash'       => $user['hash']
            )
        )
        ) {
            return true;
        } else {
            echo Helper::json(
                array('error' => true, 'email' => 'Письмо не отправлено')
            );
        }
    } else {
        echo Helper::json(
            array('error' => true, 'user' => 'Информация о пользователе не найдена')
        );
    }
} else {
    echo Helper::json(
        array('error' => true, 'id' => 'Id пользователя не найден')
    );
}