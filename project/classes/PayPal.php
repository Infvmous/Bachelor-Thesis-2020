<?php

/**
 * PayPal
 * PHP файл класса Order
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

/**
 * Класс PayPal
 * Отвечает за работу с PayPal
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class PayPal
{
    public $objUrl;

    // Среда
    private $_environment = 'sandbox'; // Среда для тестирования

    // Ссылки
    private $_url_production = 'https://www.paypal.com/cgi-bin/webscr';
    private $_url_sandbox = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    // Используемые ссылки
    private $_url;

    // Тип транзакции:
    // _xclick = кнопки 'КУПИТЬ СЕЙЧАС'
    // _cart = cart
    private $_cmd;

    // Массив со всеми товарами
    private $_products = array();

    // Массив со всеми полями ввода
    private $_inputs = array();

    // PayPal id
    private $_business = 'PAYPAL_BUSINESS_EMAIL';

    // Стиль страницы
    private $_page_style = null;

    // Ссылка возврата
    private $_return;

    // Ссылка отмены
    private $_cancel_payment;

    // Ссылка уведомления (IPN - мгновенное уведомление о платеже)
    private $_notify_url;

    // Код валюты
    private $_currency_code = 'RUB';

    // Полученные данные от PayPal
    private $_ipn_data = array();

    // Путь к файлу логам для IPN
    private $_log_file = null;

    // Результат отправки данных назад в PayPal
    private $_ipn_result;

    // Налог / НДС для _cart
    public $tax_cart = 0;

    // Налог / НДС для _xclick
    public $tax = 0;

    // Доставка
    public $shipping = 0;

    // Преинициализация контактной информации
    // Адрес1 *, Адрес2, Город*, Область*, Почтовый индекс*
    // Страна*, Почта*, Имя*, Фамилия*
    // Должны быть заполнены все обязательные поля, иначе не будет работать
    public $populate = array();

    /**
     * Конструктор класса PayPal
     *
     * @param $objUrl - обьект класса Url
     * @param $cmd    - тип транзакции
     *
     * @return null
     */
    public function __construct($objUrl = null, $cmd = '_cart')
    {
        $this->objUrl = is_object($objUrl) ? $objUrl : new Url();

        $this->_url = $this->_environment == 'sandbox' ?
            $this->_url_sandbox :
            $this->_url_production;

        $this->_cmd = $cmd;

        $this->_cancel_payment = SITE_URL . $this->objUrl->href('cancel');
        $this->_notify_url = SITE_URL . $this->objUrl->href('ipn');
        $this->_log_file = ROOT_PATH . DS . "log" . DS . "ipn.log";
    }

    /**
     * Метод добавления товара
     *
     * @param $number - номер товара
     * @param $name   - название товара
     * @param $price  - цена
     * @param $qty    - количество
     *
     * @return null
     */
    public function addProduct($number, $name, $price = 0, $qty = 1)
    {
        switch($this->_cmd) {
        case '_cart':
            $id = count($this->_products) + 1;
            $this->_products[$id]['item_number_'.$id] = $number;
            $this->_products[$id]['item_name_'.$id] = $name;
            $this->_products[$id]['amount_'.$id] = $price;
            $this->_products[$id]['quantity_'.$id] = $qty;
            break;
        case '_xclick':
            if (empty($this->_products)) {
                $this->_products[0]['item_number'] = $number;
                $this->_products[0]['item_name'] = $name;
                $this->_products[0]['amount'] = $price;
                $this->_products[0]['quantity'] = $qty;
            }
            break;
        }
    }

    /**
     * Метод запуска PayPal
     *
     * @param $name  - имя
     * @param $value - значение
     *
     * @return null
     */
    private function _addField($name, $value = null)
    {
        if (!empty($name) && !empty($value)) {
            $field  = '<input type="hidden" name="'.$name.'" ';
            $field .= 'value="'.$value.'" />';
            $this->_fields[] = $field;
        }
    }

    /**
     * Метод возвращающий поля в HTML
     *
     * @return HTML код
     */
    private function _getFields()
    {
        $this->_processFields();
        if (!empty($this->_fields)) {
            return implode("", $this->_fields);
        }
    }

    /**
     * Метод инициализации передаваемых в ПП полей ввода
     *
     * @return null
     */
    private function _processFields()
    {
        $this->_standardFields();
        if (!empty($this->_products)) {
            foreach ($this->_products as $product) {
                foreach ($product as $key => $value) {
                    $this->_addField($key, $value);
                }
            }
        }
        $this->_prePopulate();
    }

    /**
     * Метод, содержащий в себе все обязательные поля для заполнения
     *
     * @return null
     */
    private function _standardFields()
    {
        $this->_addField('cmd', $this->_cmd);
        $this->_addField('business', $this->_business);
        if ($this->_page_style != null) {
            $this->_addField('page_style', $this->_page_style);
        }
        $this->_addField('return', $this->_return);
        $this->_addField('notify_url', $this->_notify_url);
        $this->_addField('cancel_payment', $this->_cancel_payment);
        $this->_addField('currency_code', $this->_currency_code);
        $this->_addField('rm', 2);

        if (!empty($this->shipping)) {
            $this->_addField('shipping_1', $this->shipping);
        }

        switch($this->_cmd) {
        case '_cart':
            if ($this->tax_cart != 0) {
                $this->_addField('tax_cart', $this->tax_cart);
            }
            $this->_addField('upload', 1);
            break;
        case '_xclick':
            if ($this->tax != 0) {
                $this->_addField('tax', $this->tax);
            }
            break;
        }
    }

    /**
     * Метод преЗаписи полей, отправляемых в ПП
     *
     * @return null
     */
    private function _prePopulate()
    {
        if (!empty($this->populate)) {
            foreach ($this->populate as $key => $value) {
                $this->_addField($key, $value);
            }
        }
    }

    /**
     * Метод возвращающий форму для PayPal
     *
     * @return $out - срендеренный html код
     */
    private function _render()
    {
        $out  = '<form action="'.$this->_url.'" method="post" id="frm_paypal">';
        $out .= $this->_getFields();
        $out .= '<input type="submit" value="Submit" />';
        $out .= '</form>';
        return $out;
    }

    /**
     * Метод запуска PayPal
     *
     * @param $transaction_token - token транзакции
     *
     * @return null
     */
    public function run($transaction_token = null)
    {
        if (!empty($transaction_token)) {
            $this->_return = SITE_URL . $this->objUrl->href('return', array('token', $transaction_token));
            $this->_addField('custom', $transaction_token);
        }
        return $this->_render();
    }

    /**
     * Метод валидации полученного постбека
     *
     * @return null
     */
    private function _validateIpn()
    {
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        // Проверка получен ли POST от paypal.com
        if (!preg_match('/paypal\.com$/', $hostname)) {
            return false;
        }

        // Получить все данные из POST, полученные от paypal
        // И запись их в массив
        $objForm = new Form();
        $this->_ipn_data = $objForm->getPostArray();

        // Проверка если полученная бизнес почта совпадает с полученной почтой
        // в POST в IPN
        if (!empty($this->_ipn_data)
            && array_key_exists('receiver_email', $this->_ipn_data)
            && strtolower($this->_ipn_data['receiver_email'])
            != strtolower($this->_business)
        ) {
            return false;
        }
        return true;
    }

    /**
     * Метод получения возвращаемых параметров от paypal
     * по средствам IPN
     *
     * @return null
     */
    private function _getReturnParams()
    {
        $out = array('cmd=_notify-validate');

        if (!empty($this->_ipn_data)) {
            foreach ($this->_ipn_data as $key => $value) {
                $value = function_exists('get_magic_quotes_gpc') ?
                    urlencode(stripslashes($value)) :
                    urlencode($value);
                $out[] = "{$key}={$value}";
            }
        }
        return implode("&", $out);
    }

    /**
     * Метод отправки cURL
     *
     * @return null
     */
    private function _sendCurl()
    {
        $response = $this->_getReturnParams();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $response);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: " . strlen($response))
        );
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $this->_ipn_result = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Метод обновления заказа с помощью IPN
     * Если получили постбек от PayPal что заказ оплачен, меняем статус
     *
     * @return null
     */
    public function ipn()
    {
        if ($this->_validateIpn()) {
            $this->_sendCurl();

            if (strcmp($this->_ipn_result, "VERIFIED") == 0) {
                $objOrder = new Order();

                // Обновить заказ
                if (!empty($this->_ipn_data)) {
                    $objOrder->approve(
                        $this->_ipn_data,
                        $this->_ipn_result
                    );
                }
            }
        }
    }
}