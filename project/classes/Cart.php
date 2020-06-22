<?php

/**
 * Cart
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/cart.html
 */

/**
 * Класс Cart
 * Содержит в себе методы для работы корзиной покупок
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/cart.html
 */

class Cart
{
    public $inst_catalog;
    public $empty_cart;
    public $vat_rate;
    public $number_of_items;
    public $sub_total;
    public $vat;
    public $total;

    public $weight;
    private $_array_weight;

    public $final_shipping_type;
    public $final_shipping_cost;
    public $final_sub_total;
    public $final_vat;
    public $final_total;
    public $user;

    /**
     * Конструктор класса
     * Выполняет все методы класса
     *
     * @param $user - массив с данными о пользователе
     *
     * @return void
     */
    public function __construct($user = null)
    {
        if (!empty($user)) {
            $this->user = $user;
        }
        $this->inst_catalog = new Catalog();
        $this->empty_cart = empty($_SESSION['cart']) ? true : false;


        if (!empty($this->user) && ($this->user['country'] == COUNTRY_LOCAL || INTERNATIONAL_VAT)) {
            $objBusiness = new Business();
            $this->vat_rate = $objBusiness->getVatRate();
        } else {
            $this->vat_rate = 0;
        }
        $this->numberOfItems();
        $this->subtotal();
        $this->vat();
        $this->total();
        $this->_process();
    }

    /**
     * Считает количество товаров в корзине
     *
     * @return void
     */
    public function numberOfItems()
    {
        $value = 0;

        if (!$this->empty_cart) {
            foreach ($_SESSION['cart'] as $key => $cart) {
                $value += $cart['qty'];
            }
        }
        $this->number_of_items = $value;
    }

    /**
     * Считает цену за все товары в корзине без учета НДС
     *
     * @return void
     */
    public function subtotal()
    {
        $value = 0;
        if (!$this->empty_cart) {
            foreach ($_SESSION['cart'] as $key => $cart) {
                $product = $this->inst_catalog->getProduct($key);
                $value += ($cart['qty'] * $product['price']);
                $this->_array_weight[] = ($cart['qty'] * $product['weight']);
            }
        }
        if ($this->_array_weight > 0) {
            $this->weight = array_sum($this->_array_weight);
        }
        $this->sub_total = round($value, 2);
    }

    /**
     * Считает процент НДС от стоимости всех товаров в корзине
     *
     * @return void
     */
    public function vat()
    {
        $value = 0;

        if (!$this->empty_cart) {
            $value = ($this->vat_rate * ($this->sub_total / 100));
        }
        $this->vat = round($value, 2);
    }

    /**
     * Считает стоимость всех товаров в корзине с учетом НДС
     *
     * @return void
     */
    public function total()
    {
        $this->total = round(($this->sub_total + $this->vat), 2);
    }

    /**
     * Возвращает все категории, существующие в БД
     *
     * @param $session_id - идентификатор продукта в текущей сессии
     *
     * @return кнопку удалить из корзины, или добавить в зависимости от того,
     * добавлен товар уже в корзину, или нет
     */
    public static function activeButton($session_id)
    {
        if (isset($_SESSION['cart'][$session_id])) {
            $id = 0;
            $label = "Удалить из корзины";
        } else {
            $id = 1;
            $label = "Добавить в корзину";
        }
        $out  = "<a href=\"#\" class=\"add_to_cart";
        $out .= $id == 0 ? " red" : null;
        $out .= "\" rel=\"";
        $out .= $session_id . "_" . $id;
        $out .= "\">{$label}</a>";
        return $out;
    }

    /**
     * Возвращает неактивную кнопку
     *
     * @return кнопку "Нет на складе"
     */
    public static function inactiveButton()
    {
        return "<a class=\"inactive\">Распродано</a>";
    }

    /**
     * Пересчитывает общую стоимость в корзине товаров
     *
     * @param $price - цена за один товар
     * @param $qty   - количество товара одного типа в корзине
     *
     * @return стоимость за $qty товаров
     */
    public function itemTotal($price = null, $qty = null)
    {
        if (!empty($price) && !empty($qty)) {
            return round(($price * $qty), 2);
        }
    }

    /**
     * Добавляет HTML код кнопки удаления товара из корзины
     *
     * @param $id - идентификатор удаляемого из сессии товара
     *
     * @return HTML кнопку удаления из корзины
     */
    public static function removeButton($id = null)
    {
        if (!empty($id)) {
            if (isset($_SESSION['cart'][$id])) {
                $out  = "<a href=\"#\" class=\"remove_cart red";
                $out .= "\" rel=\"{$id}\">Удалить</a>";
                return $out;
            }
        }
    }

    /**
     * Метод записи информации о заказе в сессию
     *
     * @return null
     */
    private function _process()
    {
        $this->final_shipping_type = Session::getSession('shipping_type');
        $this->final_shipping_cost = Session::getSession('shipping_cost');
        $this->final_sub_total = round(($this->sub_total + $this->final_shipping_cost), 2);
        $this->final_vat = round(($this->vat_rate * ($this->final_sub_total / 100)), 2);
        $this->final_total = round(($this->final_sub_total + $this->final_vat), 2);

    }

    /**
     * Метод записи информации о доставке в сессию
     *
     * @param $shipping - массив с информацией о доставке (цена, id, имя)
     *
     * @return true если все было добавлено в сессию успешно
     */
    public function addShipping($shipping = null)
    {
        if (!empty($shipping)) {
            Session::setSession('shipping_id', $shipping['id']);
            Session::setSession('shipping_cost', $shipping['cost']);
            Session::setSession('shipping_type', $shipping['name']);
            $this->_process();
            return true;
        }
        return false;
    }

    /**
     * Метод удаления из сесии информации о заказе
     *
     * @return null
     */
    public function clearShipping()
    {
        Session::clear('shipping_id');
        Session::clear('shipping_cost');
        Session::clear('shipping_type');

        $this->final_shipping_type = null;
        $this->final_shipping_cost = null;
        $this->final_sub_total = null;
        $this->final_vat = null;
        $this->final_total = null;
    }
}