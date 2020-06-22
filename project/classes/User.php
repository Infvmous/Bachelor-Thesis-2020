<?php

/**
 * User
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/login.html
 */

/**
 * Класс User
 * Предназначен для работы с таблицей пользователей в БД
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/login.html
 */

class User extends Application
{
    public $objUrl;

    private $_table = "clients";
    public $id;

    /**
     * Конструктор класса
     *
     * @param $objUrl - обьект класса Url
     *
     * @return null
     */
    public function __construct($objUrl = null)
    {
        parent::__construct();
        $this->objUrl = is_object($objUrl) ? $objUrl : new Url();
    }

    /**
     * Проверяет существует ли пользователь в базе данных
     *
     * @param $email    - адрес электронной почты пользователя
     * @param $password - пароль
     *
     * @return true, если пользователь найден в БД
     * false, если не найден
     */
    public function isUser($email, $password)
    {
        $password = Login::stringToHash($password);
        $sql = "SELECT * FROM `{$this->_table}`
                WHERE `email` = '" . $this->db->escape($email) . "'
                AND `password` = '" . $this->db->escape($password) . "'
                AND `active` = 1";
        $result = $this->db->fetchOne($sql);

        if (!empty($result)) {
            $this->id = $result['id'];
            return true;
        }
        return false;
    }

    /**
     * Подготовка к добавлению пользователя в ДБ
     *
     * @param $params   - массив post с данными о пользователе
     * @param $password - пароль пользователя
     *
     * @return true, если
     */
    public function addUser($params = null, $password = null)
    {
        if (!empty($params) && !empty($password)) {
            $this->db->prepareInsert($params);

            if ($this->db->insert($this->_table)) {
                // Отправить письмо на емейл
                $objEmail = new Email();

                if ($objEmail->process(
                    1, array(
                        'email'      => $params['email'],
                        'first_name' => $params['first_name'],
                        'last_name'  => $params['last_name'],
                        'password'   => $password,
                        'hash'       => $params['hash']
                    )
                )
                ) {
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }

    /**
     * Получение данных о пользователе по полю hash
     *
     * @param $hash - хеш пользователя
     *
     * @return результат выполнения sql запроса
     */
    public function getUserByHash($hash = null)
    {
        if (!empty($hash)) {
            $sql  = "SELECT * FROM `{$this->_table}`
                    WHERE `hash` = '";
            $sql .= $this->db->escape($hash) . "'";
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод активации пользователя в БД
     * Обновляет поле active с 0 на 1
     *
     * @param $id - идентификатор пользователя
     *
     * @return результат выполнения sql запроса
     */
    public function makeActive($id = null)
    {
        if (!empty($id)) {
            $sql = "UPDATE `{$this->_table}`
                    SET `active` = 1
                    WHERE `id` = '" . $this->db->escape($id) . "'";
            return $this->db->query($sql);
        }
    }

    /**
     * Возвращает id активированного пользователя, по соответствующему эмейлу
     *
     * @param $email - емейл юзера
     *
     * @return результат выполнения sql запроса
     */
    public function getByEmail($email = null)
    {
        if (!empty($email)) {
            $sql = "SELECT `id` FROM `{$this->_table}`
                    WHERE `email` = '" . $this->db->escape($email) . "'";
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Возвращает все данные об пользователе из БД
     *
     * @param $id - идентификатор пользователя
     *
     * @return результат выполнения sql запроса
     */
    public function getUser($id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT * FROM `{$this->_table}`
                    WHERE `id` = '" . $this->db->escape($id) . "'";
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Возвращает имена и фамилии всех пользователей
     *
     * @param $srch - критерий поиска пользователя в БД
     *
     * @return результат выполнения sql запроса
     */
    public function getUsers($srch = null)
    {
        $sql = "SELECT * FROM `{$this->_table}`
                WHERE `active` = 1";
        if (!empty($srch)) {
            $srch = $this->db->escape($srch);
            $sql .= " AND (`first_name` LIKE '%{$srch}%' || `last_name` LIKE '%{$srch}%' || `email` LIKE '%{$srch}%')";
        }
        $sql .= " ORDER BY `last_name`, `first_name` ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Обновляет данные о пользователе
     *
     * @param $array - данные о пользователе
     * @param $id    - идентификатор пользователя
     *
     * @return true, если данные обновлены
     */
    public function updateUser($array = null, $id = null)
    {
        if (!empty($array) && !empty($id)) {
            $this->db->prepareUpdate($array);
            if ($this->db->update($this->_table, $id)) {
                return true;
            }
            return false;
        }
    }

    /**
     * Удаляет данные о пользователе
     *
     * @param $id - идентификатор пользователя
     *
     * @return результат выполнения sql запроса
     */
    public function removeUser($id = null)
    {
        if (!empty($id)) {
            $sql = "DELETE FROM `{$this->_table}`
                    WHERE `id` = '".$this->db->escape($id)."'";
            return $this->db->query($sql);
        }
    }
}