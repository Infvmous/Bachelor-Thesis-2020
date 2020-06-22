<?php

/**
 * Validation
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
 * Класс Validation
 * Работает с полями ввода, не позволяет отправлять в БД пустые, или
 * некорректно заполненные поля
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Validation
{
    private $_objForm;

    // Для сбора ID всех ошибок
    public $errors = array();

    // Накопленные сообщения об ошибке
    public $errorsMessages = array();

    // Сообщения валидации
    public $message = array(
        "first_name"         => "Укажите имя",
        "last_name"          => "Укажите фамилию",
        "address_1"          => "Укажите почтовый адрес",
        "address_2"          => "Укажите дополнительный почтовый адрес",
        "city"               => "Укажите название города",
        "state"              => "Укажите название области",
        "post_code"          => "Укажите почтовый индекс",
        "country"            => "Выберите страну из списка",

        "same_address"       => "Выберите один вариант",
        "ship_address_1"     => "Укажите адрес доставки",
        "ship_address_2"     => "Укажите дополнительный адрес доставки",
        "ship_city"          => "Укажите название города",
        "ship_state"         => "Укажите название области",
        "ship_post_code"     => "Укажите почтовый индекс",
        "ship_country"       => "Укажите страну доставки",

        "email"              => "Укажите действительный адрес эл. почты",
        "email_duplicate"    => "Адрес эл. почты уже занят",
        "login"              => "Адрес эл. почты и / или пароль введены неверно",
        "password"           => "Укажите пароль",
        "confirm_password"   => "Подтвердите пароль",
        "password_mismatch"  => "Пароли не совпадают",
        "name"               => "Укажите имя",
        "price"              => "Укажите цену товара",
        "description"        => "Укажите описание товара",
        "category"           => "Выберите категорию",
        "name_duplicate"     => "Категория с таким названием уже существует",
        "telephone"          => "Укажите номер телефона компании",
        "website"            => "Укажите действительный адрес веб-сайта компании",
        "vat_rate"           => "Укажите процент НДС",
        "address"            => "Укажите действительный адрес компании",

        "identity"           => "Укажите действительный идентификатор URL",
        "duplicate_identity" => "Этот идентификатор URL уже занят",
        "meta_title"         => "Укажите мета заголовок",
        "meta_description"   => "Укажите мета описание",
        "meta_keywords"      => "Укажите мета ключевые слова",

        "weight"             => "Укажите вес",
        "cost"               => "Укажите цену",
        "qty"                => "Укажите кол-во товара"
    );

    // Ожидаемые поля
    public $expected = array();

    // Требуемые поля
    public $required = array();

    // Специальные поля валидации
    // array('field_name' => 'format')
    public $special = array();

    public $post = array();

    // Поля, которые будут удалены из $_post
    public $post_remove = array();

    // Поля которые должны быть специально отформатированы
    // array('field_name' => 'format'
    public $post_format = array();

    /**
     * Конструктор класса
     *
     * @param $_objForm - объект формы
     *
     * @return void
     */
    public function __construct($_objForm = null)
    {
        $this->_objForm = is_object($_objForm) ? $_objForm : new Form();
    }

    /**
     * Проверяет наличие ожидаемых полей в массиве _POST и перебирает их значения
     *
     * @return void
     */
    public function process()
    {
        if ($this->_objForm->isPost()) {
            // получить только ожидаемые поля
            $this->post = !empty($this->post) ? $this->post
                : $this->_objForm->getPostArray($this->expected);
            if (!empty($this->post)) {
                foreach ($this->post as $key => $value) {
                    $this->check($key, $value);
                }
            }
        }
    }

    /**
     * Добавляет в массив ошибок неправильно заполненные поля
     *
     * @param $key   - ключ перебираемого массива в массиве _POST
     * @param $value - значение
     *
     * @return void
     */
    public function addToErrors($key = null, $value = null)
    {
        if (!empty($key)) {
            $this->errors[] = $key;
            if (!empty($value)) {
                $this->errorsMessages['valid_' . $key] = $this->wrapWarning($value);
            } else if (array_key_exists($key, $this->message)) {
                $this->errorsMessages['valid_' . $key] = $this->wrapWarning($this->message[$key]);
            }
        }
    }

    /**
     * Проверяет ожидаемые, специальные, требуемые поля
     *
     * @param $key   - ключ перебираемого массива в массиве _POST
     * @param $value - значение перебираемого массива
     *
     * @return void
     */
    public function check($key, $value)
    {
        //проверяет есть ли в массиве указанный ключ или индекс
        if (!empty($this->special) && array_key_exists($key, $this->special)) {
            $this->checkSpecial($key, $value);
        } else {
            //проверяет есть ли в массиве значение
            if (in_array($key, $this->required) && Helper::isEmpty($value)) {
                $this->addToErrors($key);
            }
        }
    }

    /**
     * Проверяет наличие специальных полей
     *
     * @param $key   - ключ перебираемого массива в массиве _POST
     * @param $value - значение перебираемого массива
     *
     * @return void
     */
    public function checkSpecial($key, $value)
    {
        switch ($this->special[$key]) {
        case 'email':
            if (!$this->isEmail($value)) {
                $this->addToErrors($key);
            }
            break;
        }
    }

    /**
     * Валидация адреса электронной почты
     *
     * @param $email - адрес электронной почты
     *
     * @return false если поле пустое, или заполнено неправильно
     */
    public function isEmail($email = null)
    {
        if (!empty($email)) {
            $result = filter_var($email, FILTER_VALIDATE_EMAIL);
            return !$result ? false : true;
        }
        return false;
    }

    /**
     * Если поля заполнены правильно удаляет данные из массива _POST
     * И форматирует обязательные поля
     *
     * @param $array - массив
     *
     * @return true или false
     */
    public function isValid($array = null)
    {
        if (!empty($array)) {
            $this->post = $array;
        }
        $this->process();
        if (empty($this->errors) && !empty($this->post)) {
            // Удалить ненужные поля
            if (!empty($this->post_remove)) {
                foreach ($this->post_remove as $value) {
                    unset($this->post[$value]);
                }
            }
            // Форматировать обязательные поля
            if (!empty($this->post_format)) {
                foreach ($this->post_format as $key => $value) {
                    $this->format($key, $value);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Форматирует пароль в HASH
     *
     * @param $key   - ключ перебираемого массива в массиве _POST
     * @param $value - значение перебираемого массива
     *
     * @return void
     */
    public function format($key, $value)
    {
        switch ($value) {
        case 'password':
            $this->post[$key] = Login::stringToHash($this->post[$key]);
            break;
        }
    }

    /**
     * Вызывает метод, который отображает ошибку валидации
     *
     * @param $key - ключ перебираемого массива в массиве _POST
     *
     * @return void
     */
    public function validate($key)
    {
        if (!empty($this->errors) && in_array($key, $this->errors)) {
            return $this->wrapWarning($this->message[$key]);
        }
    }

    /**
     * Отображает ошибку валидации на HTML странице
     *
     * @param $message - сообщение ошибки
     *
     * @return html тег с классом warn, для отображения ошибки валидации
     */
    public function wrapWarning($message = null)
    {
        if (!empty($message)) {
            return "<span class=\"warn\">{$message}</span>";
        }
    }
}