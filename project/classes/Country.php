<?php

/**
 * Country
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
 * Класс Country
 * Класс, содержащий в себе методы для SQL запросов, возвращающих список стран из БД
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Country extends Application
{
    private $_table = 'countries';
    /**
     * Метод, выполняющий запрос на получение всех стран из БД
     *
     * @return результат выполнения SQL запроса
     */
    public function getCountries()
    {
        $sql = "SELECT *
                FROM `{$this->_table}`
                WHERE `include` = 1
				ORDER BY `name` ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Метод, выполняющий запрос на получение страны с определенным id из БД
     *
     * @param $id - идентификатор страны
     *
     * @return результат выполнения SQL запроса
     */
    public function getCountry($id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT *
					FROM `{$this->_table}`
					WHERE `id` = " . intval($id) . "
					AND `include` = 1";
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод, возвращающий список всех стран, кроме локальных
     *
     * @return результат выполнения SQL запроса
     */
    public function getAllExceptLocal()
    {
        $sql = "SELECT *
                FROM `{$this->_table}`
                WHERE `id` != " . COUNTRY_LOCAL . "
                ORDER BY `name` ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Метод, возвращающий список всех стран
     *
     * @return результат выполнения SQL запроса
     */
    public function getAll()
    {
        $sql = "SELECT *
                FROM `{$this->_table}`
                ORDER BY `name` ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Метод, возвращающий страну по id
     *
     * @param $id - id страны
     *
     * @return результат выполнения SQL запроса
     */
    public function getOne($id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT *
                    FROM `{$this->_table}`
                    WHERE `id` = " . intval($id);
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод, позволяющий добавить страну
     *
     * @param $array - массив с данными о стране из бд
     *
     * @return результат выполнения SQL запроса
     */
    public function addCountry($array = null)
    {
        if (!empty($array)) {
            $this->db->prepareInsert($array);
            return $this->db->insert($this->_table);
        }
        return false;
    }

    /**
     * Метод, позволяющий обновить информацию о стране в бд
     *
     * @param $array - массив с данными о стране из бд
     * @param $id    - id страны
     *
     * @return результат выполнения SQL запроса
     */
    public function update($array = null, $id = null)
    {
        if (!empty($array) && !empty($id)) {
            $this->db->prepareUpdate($array);
            return $this->db->update($this->_table, $id);
        }
        return false;
    }


    /**
     * Метод, позволяющий удалить информацию о стране
     *
     * @param $id - id страны
     *
     * @return результат выполнения SQL запроса
     */
    public function remove($id)
    {
        if (!empty($id)) {
            $sql = "DELETE FROM `{$this->_table}`
                    WHERE `id` = " . intval($id);
            return $this->db->query($sql);
        }
        return false;
    }
}