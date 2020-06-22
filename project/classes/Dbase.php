<?php

/**
 * Dbase
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
 * Класс Dbase
 * Класс, содержащий в себе методы для подключения и взаимодействия с БД
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Dbase
{
    private $_host = "localhost";
    private $_user = "NAME";
    private $_password = "PASSWORD";
    private $_name = "DB_NAME";

    private $_conndb = false;
    public $last_query = null;
    public $affected_rows = 0;

    public $insert_keys = array();
    public $insert_values = array();
    public $update_sets = array();

    public $id;

    /**
     * Конструктор класса
     * Вызывает метод подключения
     *
     * @return void
     */
    public function __construct()
    {
        $this->_connect();
    }

    /**
     * Метод подключения к БД
     *
     * @return void
     */
    private function _connect()
    {
        $this->_conndb = mysqli_connect(
            $this->_host,
            $this->_user,
            $this->_password
        );

        if (!$this->_conndb) {
            die("Database connection failed:<br />" . mysqli_error());
        } else {
            $_select = mysqli_select_db($this->_conndb, $this->_name);

            if (!$_select) {
                die("Database selection failed:<br />" . mysqli_error());
            }
        }
        mysqli_set_charset($this->_conndb, "utf8");
    }

    /**
     * Метод отключения от БД
     *
     * @return void
     */
    public function close()
    {
        if (!mysql_close($this->_conndb)) {
            die("Closing connection failed.");
        }
    }

    /**
     * Метод защиты от SQL инъекций
     *
     * @param $value - строка
     *
     * @return void
     */
    public function escape($value)
    {
        if (function_exists("mysql_real_escape_string")) {
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value);
            }
            $value = mysql_real_escape_string($value);
        } else {
            if (!get_magic_quotes_gpc()) {
                $value = addslashes($value);
            }
        }
        return $value;
    }

    /**
     * Метод выполнения SQL запроса
     *
     * @param $sql - SQL запрос
     *
     * @return результат SQL запроса
     */
    public function query($sql)
    {
        $this->last_query = $sql;
        $result = mysqli_query($this->_conndb, $sql);
        $this->displayQuery($result);
        return $result;
    }

    /**
     * Метод отображения SQL запроса
     * Если результат существует, то отобразить
     * Если не существует, то отобразить ошибку
     *
     * @param $result - результат SQL запроса
     *
     * @return void
     */
    public function displayQuery($result)
    {
        if (!$result) {
            $output  = "Database query failed: " . mysqli_error($this->_conndb) . "<br />";
            $output .= "Last SQL query was: " . $this->last_query;
            die($output);
        } else {
            $this->affected_rows = mysqli_affected_rows($this->_conndb);
        }
    }

    /**
     * Получить все результаты выполненного SQL запроса
     *
     * @param $sql - SQL запрос
     *
     * @return результат выполнения запроса
     */
    public function fetchAll($sql)
    {
        $result = $this->query($sql);
        $out = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $out[] = $row;
        }

        mysqli_free_result($result);
        return $out;
    }

    /**
     * Получить один результат выполненного SQL запроса
     *
     * @param $sql - SQL запрос
     *
     * @return результат выполнения запроса
     */
    public function fetchOne($sql)
    {
        $out = $this->fetchAll($sql);
        return array_shift($out);
    }

    /**
     * Возвращает автоматически генерируемый ID, используя последний запрос
     *
     * @return id
     */
    public function lastId()
    {
        return mysqli_insert_id($this->_conndb);
    }

    /**
     * Подготовка к записи
     *
     * @param $array -
     *
     * @return void
     */
    public function prepareInsert($array = null)
    {
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                $this->insert_keys[] = $key;
                $this->insert_values[] = $this->escape($value);
            }
        }
    }

    /**
     * Запись данных о пользователе в БД
     *
     * @param $table - данные о пользователе
     *
     * @return true, если запрос был выполнен успешно
     */
    public function insert($table = null)
    {
        if (!empty($table)
            && !empty($this->insert_keys)
            && !empty($this->insert_values)
        ) {

            $sql  = "INSERT INTO `{$table}` (`";
            $sql .= implode("`, `", $this->insert_keys);
            $sql .= "`) VALUES ('";
            $sql .= implode("', '", $this->insert_values);
            $sql .= "')";

            if ($this->query($sql)) {
                $this->id = $this->lastId();
                return true;
            }
            return false;
        }
    }

    /**
     * Метод подготовки к обновлению данных в БД
     *
     * @param $array - массив с данными
     *
     * @return void
     */
    public function prepareUpdate($array = null)
    {
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                $this->update_sets[] = "`{$key}` = '" . $this->escape($value) . "'";
            }
        }
    }

    /**
     * Метод обновления данных в БД
     *
     * @param $table - таблица, в которой будет происходить обновление данных
     * @param $id    - идентификатор
     *
     * @return результат выполнения запроса
     */
    public function update($table = null, $id = null)
    {
        if (!empty($table) && !empty($id) && !empty($this->update_sets)) {
            $sql  = "UPDATE `{$table}` SET ";
            $sql .= implode(", ", $this->update_sets);
            $sql .= " WHERE `id` = '" . $this->escape($id) . "'";
            return $this->query($sql);
        }
    }
}
