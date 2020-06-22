<?php

/**
 * Form
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
 * Класс Form
 * Класс, содержащий в себе методы для работы с html тегом FORM
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Form
{
    /**
     * Метод, проверяющий наличие поля $field в массиве _POST
     *
     * @param $field - поле ввода (<input</input>)
     *
     * @return true или false
     */
    public function isPost($field = null)
    {
        if (!empty($field)) {
            if (isset($_POST[$field])) {
                return true;
            }
            return false;
        } else {
            if (!empty($_POST)) {
                return true;
            }
                return false;
        }
    }

    /**
     * Метод, возвращающий поле из массива _POST
     *
     * @param $field - поле ввода (<input</input>)
     *
     * @return $field из массива _POST
     */
    public function getPost($field = null)
    {
        if (!empty($field)) {
            return $this->isPost($field) ?
                strip_tags($_POST[$field]) : null;
        }
    }

    /**
     * Метод, позволяющий запомнить выбранное значение в теге select
     *
     * @param $field   - поле выбора <select></select>
     * @param $value   - значение поля
     * @param $default - значение поля по умолчанию
     *
     * @return $field из массива _POST
     */
    public function stickySelect($field, $value, $default = null)
    {
        if ($this->isPost($field) && $this->getPost($field) == $value) {
            return " selected=\"selected\"";
        } else {
            return !empty($default) && $default == $value ?
            " selected=\"selected\"" : null;
        }
    }

    /**
     * Метод, позволяющий запомнить выбранное значение в теге input
     *
     * @param $field - поле выбора <input></input>
     * @param $value - значение поля
     *
     * @return $value в соответствующие поля после обновления страницы
     */
    public function stickyText($field, $value = null)
    {
        if ($this->isPost($field)) {
            return stripslashes($this->getPost($field));
        } else {
            return !empty($value) ? $value : null;
        }
    }

    /**
     * Метод, позволяющий запомнить выбранное значение (radio button)
     *
     * @param $field - поле
     * @param $value - имя параметра
     * @param $data  - данные для ajax запросов
     *
     * @return checked атрибут для html тега
     */
    public function stickyRadio($field = null, $value = null, $data = null)
    {
        $post = $this->getPost($field);
        if (!Helper::isEmpty($post)) {
            if ($post == $value) {
                return ' checked="checked"';
            }
        } else {
            return !Helper::isEmpty($data) && $value == $data ? ' checked="checked"'
                : null;
        }
    }

    /**
     * Метод, позволяющий запомнить выбранное значение (radio button)
     *
     * @param $field  - поле
     * @param $value  - имя параметра
     * @param $data   - данные для ajax запросов
     * @param $class  - css класс
     * @param $single - один класс у тега или нет
     *
     * @return css класс
     */
    public function stickyRemoveClass($field = null, $value = null, $data = null, $class = null, $single = false)
    {
        $post = $this->getPost($field);
        if (!Helper::isEmpty($post)) {
            if ($post != $value) {
                return $single ? ' class="' . $class . '"' : ' ' . $class;
            }
        } else {
            if ($value != $data) {
                return $single ? ' class="' . $class . '"' : ' ' . $class;
            }
        }
    }

    /**
     * Метод, отображающий тег SELECT с выбором страны
     * Список стран берется из БД
     *
     * @param $record       - выбранная страна в input теге
     * @param $name         - имя параметра
     * @param $selectOption -
     *
     * @return $out - массив стран
     */
    public function getCountriesSelect($record = null, $name = 'country', $selectOption = false)
    {
        $objCountry = new Country();
        $countries = $objCountry->getCountries();

        if (!empty($countries)) {
            $out = "<select name=\"{$name}\" id=\"{$name}\" class=\"sel\">";
            if (empty($record) || $selectOption == true) {
                $out .= "<option value=\"\">Выберите страну&hellip;</option>";
            }
            foreach ($countries as $country) {
                    $out .= "<option value=\"";
                    $out .= $country['id'];
                    $out .= "\"";
                    $out .= $this->stickySelect($name, $country['id'], $record);
                    $out .= ">";
                    $out .= $country['name'];
                    $out .= "</option>";
            }
            $out .= "</select>";
            return $out;
        }
    }

    /**
     * Метод, возвращающий массив полей из массива _POST
     *
     * @param $expected - ожидаемые поля
     *
     * @return $out - массив ожидаемых полей
     */
    public function getPostArray($expected = null)
    {
        $out = array();

        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                if (!empty($expected)) {
                    if (in_array($key, $expected)) {
                        $out[$key] = strip_tags($value);
                    }
                } else {
                    $out[$key] = strip_tags($value);
                }
            }
        }
        return $out;
    }
}