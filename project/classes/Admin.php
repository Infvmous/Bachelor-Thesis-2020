<?php

/**
 * Admin
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru
 */

/**
 * Класс Admin
 * Содержит в себе методы для работы с контроль-панелью
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru
 */

class Admin extends Application
{
    private $_table = 'admins';
    public $id;

    /**
     * Добавление администраторов в БД
     *
     * @param $email    - эл.почта
     * @param $password - пароль
     *
     * @return null
     */
    public function isUser($email = null, $password = null)
    {
        if (!empty($email) && !empty($password)) {
            $password = Login::stringToHash($password);
            $sql = "SELECT * FROM `{$this->_table}`
                    WHERE `email` = '".$this->db->escape($email)."'
                    AND `password` = '".$this->db->escape($password)."'";
            $result = $this->db->fetchOne($sql);
            if (!empty($result)) {
                $this->id = $result['id'];
                return true;
            }
            return false;
        }
    }

    /**
     * Возвращает имя и фамилию администратора
     *
     * @param $id - id администратора
     *
     * @return результат выполнения SQL запроса
     */
    public function getFullNameAdmin($id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT *,
                    CONCAT_WS(' ', `first_name`, `last_name`) AS `full_name`
                    FROM `{$this->_table}`
                    WHERE `id` = " . intval($id);
            $result = $this->db->fetchOne($sql);
            if (!empty($result)) {
                return $result['full_name'];
            }
        }
    }
}