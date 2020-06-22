<?php
/**
 * Summary-update
 * Модуль обновления стоимости в зависимости от выбранной доставки
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

try {
    if (!empty($_GET['shipping'])) {
        Login::restrictFront();

        $objUser = new User();
        $user = $objUser->getUser(Session::getSession(Login::$login_front));

        if (!empty($user)) {
            $objCart = new Cart($user);
            $objShipping = new Shipping($objCart);
            $shippingSelected = $objShipping->getShipping($user, $_GET['shipping']);

            if (!empty($shippingSelected)) {
                if ($objCart->addShipping($shippingSelected)) {
                    $out = array();

                    $out['cartSubTotal'] = Catalog::$currency;
                    $out['cartSubTotal'] .= number_format($objCart->final_sub_total);

                    $out['cartVat'] = Catalog::$currency;
                    $out['cartVat'] .= number_format($objCart->final_vat);

                    $out['cartTotal'] = Catalog::$currency;
                    $out['cartTotal'] .= number_format($objCart->final_total);

                    echo Helper::json(array('error' => false, 'totals' => $out));
                } else {
                    throw new Exception('Доставка не может быть добавлена');
                }
            } else {
                throw new Exception('Доставка не может быть найдена');
            }
        } else {
            throw new Exception('Пользователь не найден');
        }
    } else {
        throw new Exception('Неверный запрос');
    }
} catch (Exception $e) {
    echo Helper::json(array('error' => true, $e->getMessage()));
}



