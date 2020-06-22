<?php

/**
 * PayPal
 * Модуль, отвечающий за работу с PayPal
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/modules/paypal.php
 */

require_once '../include/config.php';

try {

    // Токены
    $token2 = Session::getSession('token2');
    $objForm = new Form();
    $token1 = $objForm->getPost('token');

    if ($token2 == Login::stringToHash($token1)) {
        $objUser = new User();
        $user = $objUser->getUser(Session::getSession(Login::$login_front));

        // Создать заказ
        $objOrder = new Order();

        if (!empty($user) && $objOrder->createOrder($user)) {
            // Заполнение деталей заказа
            $order = $objOrder->getOrder();
            $items = $objOrder->getOrderItems();

            if (!empty($order) && !empty($items)) {
                $objCart = new Cart($user);
                $objCatalog = new Catalog();
                $objPayPal = new PayPal();

                foreach ($items as $item) {
                    $product = $objCatalog->getProduct($item['product']);
                    $objPayPal->addProduct(
                        $item['product'],
                        $product['name'],
                        $item['price'],
                        $item['qty']
                    );
                    // Уменьшить кол-во товара в БД
                    $objOrder->updateQty($item['product'], $item['qty']);
                }

                $objPayPal->tax_cart = $objCart->final_vat;
                $objPayPal->shipping = $objCart->final_shipping_cost;

                // Получить страну пользователя
                $objCountry = new Country();
                $country = $objCountry->getCountry($user['country']);

                // Отправка всех данных клиента в PayPal
                $objPayPal->populate = array(
                    'address1'   => $user['address_1'],
                    'address2'   => $user['address_2'],
                    'city'       => $user['city'],
                    'state'      => $user['state'],
                    'zip'        => $user['post_code'],
                    'country'    => $country['code'],
                    'email'      => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name'  => $user['last_name']
                );
                // Редирект клиента в PayPal
                $form = $objPayPal->run($order['token']);
                echo Helper::json(array('error' => false, 'form' => $form));
            } else {
                throw new Exception('Произошла ошибка при создании заказа');
            }
        } else {
            throw new Exception('Заказ не может быть создан');
        }
    } else {
        throw new Exception('Неверный запрос');
    }
} catch (Exception $e) {
    echo Helper::json(array('error' => true, 'message' => $e->getMessage()));
}