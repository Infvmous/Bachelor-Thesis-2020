<?php

/**
 * Order
 * PHP файл класса Order
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/orders.html
 */

/**
 * Класс Order
 * Отвечает за работу с заказами клиентов
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/orders.html
 */

class Order extends Application
{
    private $_table = 'orders';
    private $_table_2 = 'orders_items';
    private $_table_3 = 'statuses';
    private $_table_4 = 'countries';
    private $_table_5 = 'products';

    private $_cart = array();
    private $_items = array();

    private $_fields = array();
    private $_values = array();
    private $_id = null;

    /**
     * Метод получения товаров из корзины товаров
     *
     * @return null
     */
    public function getItems()
    {
        $this->_cart = Session::getSession('cart');
        if (!empty($this->_cart)) {
            $objCatalog = new Catalog();
            foreach ($this->_cart as $key => $value) {
                $this->_items[$key] = $objCatalog->getProduct($key);
            }
        }
    }

    /**
     * Метод создания заказа
     *
     * @param $user - массив с информацией о пользователе
     *
     * @return null
     */
    public function createOrder($user = null)
    {
        $this->getItems();

        if (!empty($user) && !empty($this->_items)) {
            $objCart = new Cart($user);
            $objBusiness = new Business();
            $business = $objBusiness->getBusiness();

            $this->_fields[] = 'vat_number';
            $this->_values[] = $business['vat_number'];

            $this->_fields[] = 'client';
            $this->_values[] = $this->db->escape($user['id']);

            // Данные клиента
            $this->_fields[] = 'first_name';
            $this->_values[] = $this->db->escape($user['first_name']);

            $this->_fields[] = 'last_name';
            $this->_values[] = $this->db->escape($user['last_name']);

            $this->_fields[] = 'address_1';
            $this->_values[] = $this->db->escape($user['address_1']);

            $this->_fields[] = 'address_2';
            $this->_values[] = $this->db->escape($user['address_2']);

            $this->_fields[] = 'city';
            $this->_values[] = $this->db->escape($user['city']);

            $this->_fields[] = 'state';
            $this->_values[] = $this->db->escape($user['state']);

            $this->_fields[] = 'post_code';
            $this->_values[] = $this->db->escape($user['post_code']);

            $this->_fields[] = 'country';
            $this->_values[] = $this->db->escape($user['country']);

            // Данные заказа
            if ($user['same_address'] == 1) {
                $this->_fields[] = 'ship_address_1';
                $this->_values[] = $this->db->escape($user['address_1']);

                $this->_fields[] = 'ship_address_2';
                $this->_values[] = $this->db->escape($user['address_2']);

                $this->_fields[] = 'ship_city';
                $this->_values[] = $this->db->escape($user['city']);

                $this->_fields[] = 'ship_state';
                $this->_values[] = $this->db->escape($user['state']);

                $this->_fields[] = 'ship_post_code';
                $this->_values[] = $this->db->escape($user['post_code']);

                $this->_fields[] = 'ship_country';
                $this->_values[] = $this->db->escape($user['country']);
            } else {
                $this->_fields[] = 'ship_address_1';
                $this->_values[] = $this->db->escape($user['ship_address_1']);

                $this->_fields[] = 'ship_address_2';
                $this->_values[] = $this->db->escape($user['ship_address_2']);

                $this->_fields[] = 'ship_city';
                $this->_values[] = $this->db->escape($user['ship_city']);

                $this->_fields[] = 'ship_state';
                $this->_values[] = $this->db->escape($user['ship_state']);

                $this->_fields[] = 'ship_post_code';
                $this->_values[] = $this->db->escape($user['ship_post_code']);

                $this->_fields[] = 'ship_country';
                $this->_values[] = $this->db->escape($user['ship_country']);
            }

            $this->_fields[] = 'shipping_type';
            $this->_values[] = $this->db->escape($objCart->final_shipping_type);

            $this->_fields[] = 'shipping_cost';
            $this->_values[] = $this->db->escape($objCart->final_shipping_cost);

            $this->_fields[] = 'vat_rate';
            $this->_values[] = $this->db->escape($objCart->vat_rate);

            $this->_fields[] = 'vat';
            $this->_values[] = $this->db->escape($objCart->final_vat);

            $this->_fields[] = 'subtotal_items';
            $this->_values[] = $this->db->escape($objCart->sub_total);

            $this->_fields[] = 'subtotal';
            $this->_values[] = $this->db->escape($objCart->final_sub_total);

            $this->_fields[] = 'total';
            $this->_values[] = $this->db->escape($objCart->final_total);

            $this->_fields[] = 'date';
            $this->_values[] = Helper::setDate();

            $this->_fields[] = 'token';
            $this->_values[] = date('YmdHis') . mt_rand() . md5(time());

            $sql  = "INSERT INTO `{$this->_table}` (`";
            $sql .= implode("`, `", $this->_fields);
            $sql .= "`) VALUES ('";
            $sql .= implode("', '", $this->_values);
            $sql .= "')";

            $this->db->query($sql);
            $this->_id = $this->db->lastId();

            if (!empty($this->_id)) {
                $this->_fields = array();
                $this->_values = array();
                return $this->_addItems($this->_id);
            }
        }
        return false;
    }

    /**
     * Метод добавление товаров в заказ
     *
     * @param $order_id идентификатор заказа
     *
     * @return null
     */
    private function _addItems($order_id = null)
    {
        if (!empty($order_id)) {
            $error = array();
            foreach ($this->_items as $item) {
                $sql = "INSERT INTO `{$this->_table_2}`
					    (`order`, `product`, `price`, `qty`)
						VALUES ('{$order_id}', '" . $item['id'] . "',
                        '" . $item['price'] . "',
                        '" . $this->_cart[$item['id']]['qty'] . "')";

                if (!$this->db->query($sql)) {
                    $error[] = $sql;
                }
            }
            return empty($error) ? true : false;
        }
        return false;
    }

    /**
     * Метод получения заказа из БД
     *
     * @param $id идентификатор заказа
     *
     * @return результат выполнения sql запроса
     */
    public function getOrder($id = null)
    {
        $id = !empty($id) ? $id : $this->_id;

        $sql = "SELECT `o`.*,
				DATE_FORMAT(`o`.`date`, '%D %M %Y %r') AS `date_formatted`,
				CONCAT_WS(' ', `o`.`first_name`, `o`.`last_name`) AS `full_name`,
				IF (
					`o`.`address_2` != '',
					CONCAT_WS(', ', `o`.`address_1`, `o`.`address_2`),
					`o`.`address_1`
				) AS `address`,
				IF (
					`o`.`ship_address_2` != '',
					CONCAT_WS(', ', `o`.`ship_address_1`, `o`.`ship_address_2`),
					`o`.`ship_address_1`
				) AS `ship_address`,
				(
					SELECT `name`
					FROM `{$this->_table_4}`
					WHERE `id` = `o`.`country`
				) AS `country_name`,
				(
					SELECT `name`
					FROM `{$this->_table_4}`
					WHERE `id` = `o`.`ship_country`
				) AS `ship_country_name`
				FROM `{$this->_table}` `o`
				WHERE `o`.`id` = " . intval($id);
        return $this->db->fetchOne($sql);
    }

    /**
     * Метод получения заказа по токену
     *
     * @param $token токен заказа
     *
     * @return результат выполнения sql запроса
     */
    public function getOrderByToken($token = null)
    {
        if (!empty($token)) {
            $sql = "SELECT `o`.*,
                    DATE_FORMAT(`o`.`date`, '%D %M %Y %r') AS `date_formatted`,
                    CONCAT_WS(' ', `o`.`first_name`, `o`.`last_name`) AS `full_name`,
                    IF (
                        `o`.`address_2` != '',
                        CONCAT_WS(', ', `o`.`address_1`, `o`.`address_2`),
                        `o`.`address_1`
                    ) AS `address`,
                    IF (
                        `o`.`ship_address_2` != '',
                        CONCAT_WS(', ', `o`.`ship_address_1`, `o`.`ship_address_2`),
                        `o`.`ship_address_1`
                    ) AS `ship_address`,
                    (
                        SELECT `name`
                        FROM `{$this->_table_4}`
                        WHERE `id` = `o`.`country`
                    ) AS `country_name`,
                    (
                        SELECT `name`
                        FROM `{$this->_table_4}`
                        WHERE `id` = `o`.`ship_country`
                    ) AS `ship_country_name`
                    FROM `{$this->_table}` `o`
                    WHERE `o`.`token` = '" . $this->db->escape($token) . "'";
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод получения заказов из БД
     *
     * @param $srch - критерий поиска
     *
     * @return результат выполнения sql запроса
     */
    public function getOrders($srch = null)
    {
        $sql  = "SELECT * FROM `{$this->_table}`";
        $sql .= !empty($srch) ?
                " WHERE `id` = '".$this->db->escape($srch)."'" :
                null;
        $sql .= " ORDER BY `date` DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Метод получения всех товаров из конкретного заказа
     *
     * @param $id идентификатор заказа
     *
     * @return результат выполнения sql запроса
     */
    public function getOrderItems($id = null)
    {
        $id = !empty($id) ? $id : $this->_id;

        $sql = "SELECT `i`.*,
				`p`.`name`,
				(`i`.`price` * `i`.`qty`) AS `price_total`
				FROM `{$this->_table_2}` `i`
				LEFT JOIN `{$this->_table_5}` `p`
					ON `p`.`id` = `i`.`product`
				WHERE `i`.`order` = " . intval($id);
        return $this->db->fetchAll($sql);
    }

    /**
     * Метод получения всех заказов пользователя
     *
     * @param $client_id - айди юзера
     *
     * @return результат выполнения sql запроса
     */
    public function getClientOrders($client_id = null)
    {
        if (!empty($client_id)) {
            $sql = "SELECT * FROM `{$this->_table}`
                    WHERE `client` = '" . $this->db->escape($client_id) . "'
                    ORDER BY `date` DESC";
            return $this->db->fetchAll($sql);
        }
    }

    /**
     * Метод получения статуса заказа
     *
     * @param $id - айди пользователя
     *
     * @return результат выполнения sql запроса
     */
    public function getStatus($id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT * FROM `{$this->_table_3}`
                    WHERE `id` = '" . $this->db->escape($id) . "'";
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод получения всех статусов из БД
     *
     * @return результат выполнения sql запроса
     */
    public function getStatuses()
    {
        $sql = "SELECT * FROM `{$this->_table_3}`
                ORDER BY `id` ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Метод изменение заказа
     *
     * @param $id    - id заказа
     * @param $array - массив со всеми полями из таблицы заказов
     *
     * @return результат выполнения sql запроса
     */
    public function updateOrder($id = null, $array = null)
    {
        if (!empty($id)
            && !empty($array)
            && is_array($array)
            && array_key_exists('status', $array)
            && array_key_exists('notes', $array)
        ) {
            $sql = "UPDATE `{$this->_table}`
                    SET `status` = '".$this->db->escape($array['status'])."',
                    `notes` = '".$this->db->escape($array['notes'])."'
                    WHERE `id` = '".$this->db->escape($id)."'";
            return $this->db->query($sql);
        }
    }

    /**
     * Метод удаления заказа
     *
     * @param $id - id заказа
     *
     * @return результат выполнения sql запроса
     */
    public function removeOrder($id = null)
    {
        if (!empty($id)) {
            $sql = "DELETE FROM `{$this->_table}`
                    WHERE `id` = '".$this->db->escape($id)."'";
            return $this->db->query($sql);
        }
    }

    /**
     * Метод получения статуса заказа
     *
     * @param $array  - массив со всеми полями таблицы заказа
     * @param $result -
     *
     * @return null
     */
    public function approve($array = null, $result = null)
    {
        if (!empty($array) && !empty($result)) {
            if (array_key_exists('txn_id', $array)
                && array_key_exists('payment_status', $array)
                && array_key_exists('custom', $array)
            ) {
                $active = $array['payment_status'] == 'Completed' ? 1 : 0;
                $out = array();

                foreach ($array as $key => $value) {
                    $out[] = "{$key} : {$value}";
                }

                $out = implode("\n", $out);
                $sql = "UPDATE `{$this->_table}`
                        SET `pp_status` = '".$this->db->escape($active)."',
                        `txn_id` = '".$this->db->escape($array['txn_id'])."',
                        `payment_status` = '".$this->db->escape($array['payment_status'])."',
                        `ipn` = '".$this->db->escape($out)."',
                        `response` = '".$this->db->escape($result)."'
                        WHERE `token` = '".$this->db->escape($array['custom'])."'";
                $this->db->query($sql);
            }
        }
    }

    /**
     * Метод обновления количества товара на складе
     *
     * @param $id  - id товара
     * @param $qty - на какое количество товара уменьшить
     *
     * @return результат выполнения SQL запроса
     */
    public function updateQty($id = null, $qty = null)
    {
        if (!empty($id) && !empty($qty)) {
            $sql = "UPDATE `{$this->_table_5}`
                    SET `qty` = `qty` - '" . intval($qty) . "'
                    WHERE `id` = '" . $this->db->escape($id) . "'";
            return $this->db->query($sql);
        }
        return false;
    }
}