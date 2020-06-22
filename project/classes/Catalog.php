<?php

/**
 * Catalog
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/catalog.html
 */

/**
 * Класс Catalog, наследует класс Application
 * Содержит в себе методы для работы с каталогами
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/catalog.html
 */

class Catalog extends Application
{
    private $_table = 'categories';
    private $_table_2 = 'products';
    public $path = null;
    public static $currency = '₽';

    /**
     * Конструктор класса
     *
     * @return null
     */
    public function __construct()
    {
        parent::__construct();
        $this->path = DS . 'media' . DS . 'catalog' . DS;
    }

    /**
     * Возвращает все категории, существующие в БД
     *
     * @param $case - если null, отобразить все категории, если нет - все кроме 1
     *
     * @return результат выполнения SQL запроса
     */
    public function getCategories($case = null)
    {
        /*
        switch ($case) {
        case 1:
            $sql = "SELECT * FROM `{$this->_table}`
            WHERE `id` NOT IN ( 1 )";
            return $this->db->fetchAll($sql);
            break;
        default:
            $sql = "SELECT * FROM `{$this->_table}`";
            return $this->db->fetchAll($sql);
        }*/

        $sql = "SELECT * FROM `{$this->_table}`";
        if ($case == 1) {
            $sql .= " WHERE `id` NOT IN ( 1 )";
        }
        return $this->db->fetchAll($sql);
    }

    /**
     * Возвращает категорию по identity
     *
     * @param $identity - столбец в таблице categories содержащий
     *                  в себе сокращенное название на англ
     *
     * @return результат выполнения SQL запроса
     */
    public function getCategoryByIdentity($identity = null)
    {
        if (!empty($identity)) {
            $sql = "SELECT * FROM `{$this->_table}`
                    WHERE `identity` = '" . $this->db->escape($identity) . "'";
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Возвращает товар по identity
     *
     * @param $identity - столбец в таблице categories содержащий
     *                  в себе сокращенное название на англ
     *
     * @return результат выполнения SQL запроса
     */
    public function getProductByIdentity($identity = null)
    {
        if (!empty($identity)) {
            $sql = "SELECT * FROM `{$this->_table_2}`
                    WHERE `identity` = '" . $this->db->escape($identity) . "'";
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Проверка на дупликат товара по id и identity
     *
     * @param $identity - столбец в таблице categories содержащий
     *                  в себе сокращенное название на англ
     * @param $id       - идентификатор товара
     *
     * @return true или false в зависимости от наличия дупликата в БД
     */
    public function isDuplicateProduct($identity = null, $id = null)
    {
        if (!empty($identity)) {
            $sql = "SELECT * FROM `{$this->_table_2}`
                    WHERE `identity` = '" . $this->db->escape($identity) . "'";
            if (!empty($id)) {
                $sql .= " AND `id` != '" . $this->db->escape($id) . "'";
            }
            $result = $this->db->fetchAll($sql);
            return !empty($result) ? true : false;
        }
        return false;
    }

    /**
     * Проверка на дупликат категории по id и identity
     *
     * @param $identity - столбец в таблице categories содержащий
     *                  в себе сокращенное название на англ
     * @param $id       - идентификатор товара
     *
     * @return true или false в зависимости от наличия дупликата в БД
     */
    public function isDuplicateCategory($identity = null, $id = null)
    {
        if (!empty($identity)) {
            $sql = "SELECT * FROM `{$this->_table}`
                    WHERE `identity` = '" . $this->db->escape($identity) . "'";
            if (!empty($id)) {
                $sql .= " AND `id` != '" . $this->db->escape($id) . "'";
            }
            $result = $this->db->fetchAll($sql);
            return !empty($result) ? true : false;
        }
        return false;
    }

    /**
     * Возвращает категорию, с определенным $id
     *
     * @param $id - идентификатор категории
     *
     * @return результат выполнения SQL запроса
     */
    public function getCategory($id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT `c`.*,
                    (
                        SELECT COUNT(`id`)
                        FROM `{$this->_table_2}`
                        WHERE `category` = `c`.`id`
                    ) AS `products_count`
                FROM `{$this->_table}` `c`
                WHERE `c`.`id` = '" . $this->db->escape($id) . "'";
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод добавления категории в БД
     *
     * @param $array - массив с данными для добавления категории
     *
     * @return результат выполнения SQL запроса
     */
    public function addCategory($array = null)
    {
        if (!empty($array) && is_array($array)) {
            $sql = "INSERT INTO `{$this->_table}`
                    (
                        `name`,
                        `identity`,
                        `meta_title`,
                        `meta_description`,
                        `meta_keywords`
                    )
                    VALUES (
                        '".$this->db->escape($array['name'])."',
                        '".$this->db->escape($array['identity'])."',
                        '".$this->db->escape($array['meta_title'])."',
                        '".$this->db->escape($array['meta_description'])."',
                        '".$this->db->escape($array['meta_keywords'])."'
                    )";
            return $this->db->query($sql);
        }
    }

    /**
     * Метод редактирования категории в БД
     *
     * @param $array - массив с данными о категории
     * @param $id    - id категории
     *
     * @return результат выполнения SQL запроса
     */
    public function updateCategory($array = null, $id = null)
    {
        if (!empty($array) && is_array($array) && !empty($id)) {
            $sql = "UPDATE `{$this->_table}`
                    SET `name` = '".$this->db->escape($array['name'])."',
                        `identity` = '".$this->db->escape($array['identity'])."',
                        `meta_title` = '".$this->db->escape($array['meta_title'])."',
                        `meta_description` = '".$this->db->escape($array['meta_description'])."',
                        `meta_keywords` = '".$this->db->escape($array['meta_keywords'])."'
                    WHERE `id` = '".$this->db->escape($id)."'";
            return $this->db->query($sql);
        }
        return false;
    }

    /**
     * Метод удаления категории в БД
     *
     * @param $id - id категории
     *
     * @return результат выполнения SQL запроса
     */
    public function removeCategory($id = null)
    {
        if (!empty($id)) {
            $sql = "DELETE FROM `{$this->_table}`
                    WHERE `id` = '".$this->db->escape($id)."'";
            $this->db->query($sql);
        }
        return false;
    }

    /**
     * Метод проверки на существующую категорию с набранным именем
     *
     * @param $name - имя категории
     * @param $id   - id категории
     *
     * @return результат выполнения SQL запроса
     */
    public function duplicateCategory($name = null, $id = null)
    {
        if (!empty($name)) {
            $sql  = "SELECT * FROM `{$this->_table}`
                    WHERE `name` = '".$this->db->escape($name)."'";
            $sql .= !empty($id) ?
                    " AND `id` != '".$this->db->escape($id)."'" :
                    null;
            return $this->db->fetchOne($sql);
        }
        return false;
    }

    /**
     * Возвращает товары определенной категории
     *
     * @param $cat - идентификатор категории
     *
     * @return результат выполнения SQL запроса
     */
    public function getProducts($cat)
    {
        $sql = "SELECT * FROM `{$this->_table_2}`
                WHERE `category` = '" . $this->db->escape($cat) . "'
                ORDER BY `date` DESC";
        return $this->db->fetchAll($sql);

    }

    /**
     * Возвращает один товар с определенным id
     *
     * @param $id - идентификатор продукта
     *
     * @return результат выполнения SQL запроса
     */
    public function getProduct($id)
    {
        $sql = "SELECT * FROM `{$this->_table_2}`
				WHERE `id` = '" . $this->db->escape($id) . "'";
        return $this->db->fetchOne($sql);
    }

    /**
     * Возвращает все товары
     *
     * @param $srch - критерий поиска
     *
     * @return результат выполнения SQL запроса
     */
    public function getAllProducts($srch = null)
    {
        $sql = "SELECT * FROM `{$this->_table_2}`";
        if (!empty($srch)) {
            $srch = $this->db->escape($srch);
            $sql .= " WHERE `name` LIKE '%{$srch}%' || `id` = '{$srch}'";
        }
        $sql .= " ORDER BY `date` DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Добавляет товар в БД
     *
     * @param $params - данные полей
     *
     * @return результат выполнения SQL запроса
     */
    public function addProduct($params = null)
    {
        if (!empty($params)) {
            $params['date'] = Helper::setDate();
            $this->db->prepareInsert($params);
            $out = $this->db->insert($this->_table_2);
            $this->id = $this->db->id;
            return $out;
        }
        return false;
    }

    /**
     * Обновляет данные о товаре в БД
     *
     * @param $params - данные полей
     * @param $id     - id товара
     *
     * @return результат выполнения SQL запроса
     */
    public function updateProduct($params = null, $id = null)
    {
        if (!empty($params) && !empty($id)) {
            $this->db->prepareUpdate($params);
            return $this->db->update($this->_table_2, $id);
        }
    }

    /**
     * Данные о товаре из БД
     *
     * @param $id - id товара
     *
     * @return результат выполнения SQL запроса
     */
    public function removeProduct($id = null)
    {
        if (!empty($id)) {
            $product = $this->getProduct($id);
            if (!empty($product)) {
                if (is_file(CATALOGUE_PATH.DS.$product['image'])) {
                    unlink(CATALOGUE_PATH.DS.$product['image']);
                }
                $sql = "DELETE FROM `{$this->_table_2}`
                        WHERE `id` = '".$this->db->escape($id)."'";
                return $this->db->query($sql);
            }
            return false;
        }
        return false;
    }
}
