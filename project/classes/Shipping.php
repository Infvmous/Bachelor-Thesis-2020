<?php
/**
 * Shipping
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru
 */

/**
 * Класс Shipping
 * Включает в себя методы для работы с доставкой
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru
 */
class Shipping extends Application
{
    private $_table = 'shipping';
    private $_table_2 = 'shipping_type';
    private $_table_3 = 'zones';
    private $_table_4 = 'zones_post_codes';

    public $objCart;

    /**
     * Конструктор класс
     *
     * @param $objCart - объект класса Cart
     *
     * @return null
     */
    public function __construct($objCart = null)
    {
        parent::__construct();
        $this->objCart = is_object($objCart) ? $objCart : new Cart();
    }

    /**
     * Метод получения типа доставки
     *
     * @param $id - типа доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function getType($id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT *
                    FROM `{$this->_table_2}`
                    WHERE `id` = " . intval($id);
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод получения типа доставки
     *
     * @param $local - флаг, отображающий есть ли тип доставки в локальной стране
     *
     * @return результат выполнения SQL запроса
     */
    public function getTypes($local = 0)
    {
        $sql = "SELECT *
                FROM `{$this->_table_2}`
                WHERE `local` = " . intval($local) . "
                ORDER BY `order` ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Метод получения зон доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function getZones()
    {
        $sql = "SELECT `z`.*,
                (
                    SELECT GROUP_CONCAT(`post_code` ORDER BY `post_code` ASC SEPARATOR ', ')
                    FROM `{$this->_table_4}`
                    WHERE `zone` = `z`.`id`
                ) AS `post_codes`
                FROM `{$this->_table_3}` `z`
                ORDER BY `z`.`name` ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Метод получения зоны доставки по id
     *
     * @param $id - id зоны доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function getZoneById($id = null)
    {
        if (!empty($id)) {
            $sql = "SELECT *
                    FROM `{$this->_table_3}`
                    WHERE `id` = " . intval($id);
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод получения зоны доставки по id
     *
     * @param $typeId - id типа доставки
     * @param $zoneId - id зоны доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function getShippingByTypeZone($typeId = null, $zoneId = null)
    {
        if (!empty($typeId) && !empty($zoneId)) {
            $sql = "SELECT `s`.*,
            IF (
                (
                    SELECT COUNT(`weight`)
                    FROM `{$this->_table}`
                    WHERE `type` = `s`.`type`
                    AND `zone` = `s`.`zone`
                    AND `weight` < `s`.`weight`
                    ORDER BY `weight` DESC
                    LIMIT 0, 1
                ) > 0,
                (
                    SELECT `weight`
                    FROM `{$this->_table}`
                    WHERE `type` = `s`.`type`
                    AND `zone` = `s`.`zone`
                    AND `weight` < `s`.`weight`
                    ORDER BY `weight` DESC
                    LIMIT 0, 1
                ) + 0.01,
                0
            ) AS `weight_from`
            FROM `{$this->_table}` `s`
            WHERE `s`.`type` = " . intval($typeId) . "
            AND `s`.`zone` = " . intval($zoneId) . "
            ORDER BY `s`.`weight` ASC";
            return $this->db->fetchAll($sql);
        }
    }

    /**
     * Метод последнего типа
     *
     * @param $local - флаг отображения тип работает внутри страны или нет
     *
     * @return результат выполнения SQL запроса
     */
    private function _getLastType($local = 0)
    {
        $sql = "SELECT `order`
                FROM `{$this->_table_2}`
                WHERE `local` = {$local}
                ORDER BY `order` DESC
                LIMIT 0, 1";
        return $this->db->fetchOne($sql);
    }

    /**
     * Метод добавления типа доставки
     *
     * @param $params - флаг отображения тип работает внутри страны или нет
     *
     * @return результат выполнения SQL запроса
     */
    public function addType($params = null)
    {
        if (!empty($params)) {
            $params['local'] = !empty($params['local']) ? 1 : 0;
            $last = $this->_getLastType($params['local']);
            $params['order'] = !empty($last) ? $last['order'] + 1 : 1;
            $this->db->prepareInsert($params);
            return $this->db->insert($this->_table_2);
        }
        return false;
    }

    /**
     * Метод удаления типов доставки
     *
     * @param $id - id типа доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function removeType($id = null)
    {
        if (!empty($id)) {
            $sql = "DELETE FROM `{$this->_table_2}`
                    WHERE `id` = " . intval($id);
            if ($this->db->query($sql)) {
                $sql = "DELETE FROM `{$this->_table}`
                        WHERE `type` = " . intval($id);
                return $this->db->query($sql);
            }
            return false;
        }
        return false;
    }

    /**
     * Метод обновления типа доставки
     *
     * @param $params - параметры доставки
     * @param $id     - id типа доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function updateType($params = null, $id = null)
    {
        if (!empty($params) && !empty($id)) {
            $this->db->prepareUpdate($params);
            return $this->db->update($this->_table_2, $id);
        }
        return false;
    }

    /**
     * Метод обновления default
     *
     * @param $id    - id типа доставки
     * @param $local - локальный ли тип доставки (флаг)
     *
     * @return результат выполнения SQL запроса
     */
    public function setTypeDefault($id = null, $local = 0)
    {
        if (empty($id)) {
            $sql = "UPDATE `{$this->_table_2}`
                    SET `default` = 0
                    WHERE `local` = {$local}
                    AND `id` != " . intval($id);
            if ($this->db->query($sql)) {
                $sql = "UPDATE `{$this->_table_2}`
                    SET `default` = 1
                    WHERE `local` = {$local}
                    AND `id` != " . intval($id);
                return $this->db->query($sql);
            }
            return false;
        }
        return false;
    }

    /**
     * Метод дублирования типа доставки
     *
     * @param $id - id типа доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function duplicateType($id = null)
    {
        $type = $this->getType($id);
        if (!empty($type)) {
            $last = $this->_getLastType($type['local']);
            $order = !empty($last) ? $last['order'] + 1 : 1;
            $this->db->prepareInsert(
                array(
                    'name' => $type['name'] . ' копия',
                    'order' => $order,
                    'local' => $type['local'],
                    'active' => 0
                )
            );
            if ($this->db->insert($this->_table_2)) {
                $this->db->insert_keys = array();
                $this->db->insert_values = array();

                $newId = $this->db->id;

                $sql = "SELECT *
                        FROM `{$this->_table}`
                        WHERE `type` = {$id}";
                $list = $this->db->fetchAll($sql);

                if (!empty($list)) {
                    foreach ($list as $row) {
                        $this->db->prepareInsert(
                            array(
                                'type' => $newId,
                                'zone' => $row['zone'],
                                'country' => $row['country'],
                                'weight' => $row['weight'],
                                'cost' => $row['cost']
                            )
                        );
                        $this->db->insert($this->_table);
                        $this->db->insert_keys = array();
                        $this->db->insert_values = array();
                    }
                }
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Метод проверки есть ли вес в бд
     *
     * @param $typeId - id типа доставки
     * @param $zoneId - id зоны доставки
     * @param $weight - вес
     *
     * @return результат выполнения SQL запроса
     */
    public function isDuplicateLocal($typeId = null, $zoneId = null, $weight = null)
    {
        if (!empty($typeId) && !empty($zoneId) && !empty($weight)) {
            $sql = "SELECT *
                    FROM `{$this->_table}`
                    WHERE `type` = " . intval($typeid) . "
                    AND `zone` = " . intval($zoneId) . "
                    AND `weight` = " . floatval($weight);
            $result = $this->db->fetchOne($sql);
            return !empty($result) ? true : false;
        }
        return true;
    }

    /**
     * Метод дублирования типа доставки
     *
     * @param $array - массив полей
     *
     * @return результат выполнения SQL запроса
     */
    public function addShipping($array = null)
    {
        if (!empty($array)) {
            $array['type'] = intval($array['type']);
            $array['zone'] = intval($array['zone']);
            $array['country'] = intval($array['country']);
            $array['weight'] = floatval($array['weight']);
            $array['cost'] = floatval($array['cost']);
            $this->db->prepareInsert($array);
            return $this->db->insert($this->_table);
        }
        return false;
    }

    /**
     * Метод получения доставки по id, id типа и id зоны
     *
     * @param $id     - id доставки
     * @param $typeId - id типа доставки
     * @param $zoneId - id зоны доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function getShippingByIdTypeZone($id = null, $typeId = null, $zoneId = null)
    {
        if (!empty($id) && !empty($typeId) && !empty($zoneId)) {
            $sql = "SELECT *
                    FROM `{$this->_table}`
                    WHERE `id` = " . intval($id) . "
                    AND `type` = " . intval($typeId) . "
                    AND `zone` = " . intval($zoneId);
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод удаления доставки
     *
     * @param $id - id доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function removeShipping($id = null)
    {
        if (!empty($id)) {
            $sql = "DELETE FROM `{$this->_table}`
                    WHERE `id` = " . intval($id);
            return $this->db->query($sql);
        }
        return false;
    }

    /**
     * Метод получения доставки по id типа доставки и id страны
     *
     * @param $typeId    - id типа доставки
     * @param $countryId - id страны
     *
     * @return результат выполнения SQL запроса
     */
    public function getShippingByTypeCountry($typeId = null, $countryId = null)
    {
        if (!empty($typeId) && !empty($countryId)) {
            $sql = "SELECT `s`.*,
                    IF (
                        (
                            SELECT COUNT(`weight`)
                            FROM `{$this->_table}`
                            WHERE `type` = `s`.`type`
                            AND `country` = `s`.`country`
                            AND `weight` < `s`.`weight`
                            ORDER BY `weight` DESC
                            LIMIT 0, 1
                        ) > 0,
                        (
                            SELECT `weight`
                            FROM `{$this->_table}`
                            WHERE `type` = `s`.`type`
                            AND `country` = `s`.`country`
                            AND `weight` < `s`.`weight`
                            ORDER BY `weight` DESC
                            LIMIT 0, 1
                        ) + 0.01,
                        0
                    ) AS `weight_from`
                FROM `{$this->_table}` `s`
                WHERE `s`.`type` = " . intval($typeId) . "
                AND `s`.`country` = " . intval($countryId) . "
                ORDER BY `s`.`weight` ASC";
            return $this->db->fetchAll($sql);
        }
    }
    /**
     * Метод получения доставки по id типа доставки и id страны, id доставки
     *
     * @param $id        - id доставки
     * @param $typeId    - id типа доставки
     * @param $countryId - id страны
     *
     * @return результат выполнения SQL запроса
     */
    public function getShippingByIdTypeCountry($id = null, $typeId = null, $countryId = null)
    {
        if (!empty($id) && !empty($typeId) && !empty($countryId)) {
            $sql = "SELECT *
                    FROM `{$this->_table}`
                    WHERE `id` = " . intval($id) . "
                    AND `type` = " . intval($typeId) . "
                    AND `country` = " . intval($countryId);
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод позволяющий узнать является ли доставка клоном (для глобальных доставок)
     *
     * @param $typeId    - id типа доставки
     * @param $countryId - id страны
     * @param $weight    - вес
     *
     * @return результат выполнения SQL запроса
     */
    public function isDuplicateInternational($typeId = null, $countryId = null, $weight = null)
    {
        if (!empty($typeId) && !empty($countryId) && !empty($weight)) {
            $sql = "SELECT *
                    FROM `{$this->_table}`
                    WHERE `type` = " . intval($typeId) . "
                    AND `country` = " . intval($countryId) . "
                    AND `weight` = " . floatval($weight);
            $result = $this->db->fetchOne($sql);
            return !empty($result) ? true : false;
        }
        return true;
    }

    /**
     * Метод добавления зоны доставки
     *
     * @param $array - данные для ввода в бд
     *
     * @return результат выполнения SQL запроса
     */
    public function addZone($array = null)
    {
        if (!empty($array)) {
            $this->db->prepareInsert($array);
            return $this->db->insert($this->_table_3);
        }
        return false;
    }

    /**
     * Метод удаления зон доставки
     *
     * @param $id - id зоны доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function removeZone($id = null)
    {
        if (!empty($id)) {
            $sql = "DELETE FROM `{$this->_table_3}`
                    WHERE `id` = " . intval($id);
            return $this->db->query($sql);
        }
        return false;
    }

    /**
     * Метод обновления зон доставки
     *
     * @param $array - массив измененных данных
     * @param $id    - id зоны доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function updateZone($array = null, $id = null)
    {
        if (!empty($array) && !empty($id)) {
            $this->db->prepareUpdate($array);
            return $this->db->update($this->_table_3, $id);
        }
        return false;
    }

    /**
     * Метод получения почтового индекса
     *
     * @param $id     - id почтового индекса
     * @param $zoneId - id зоны доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function getPostCode($id = null, $zoneId = null)
    {
        if (!empty($id) && !empty($zoneId)) {
            $sql = "SELECT *
                    FROM `{$this->_table_4}`
                    WHERE `id` = " . intval($id) . "
                    AND `zone` = " . intval($zoneId);
            return $this->db->fetchOne($sql);
        }
    }

    /**
     * Метод полученияпочтовых индексов
     *
     * @param $zoneId - id зоны доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function getPostCodes($zoneId = null)
    {
        if (!empty($zoneId)) {
            $sql = "SELECT *
                    FROM `{$this->_table_4}`
                    WHERE `zone` = " . intval($zoneId) . "
                    ORDER BY `post_code` ASC";
            return $this->db->fetchAll($sql);
        }
    }

    /**
     * Метод проверки есть ли в БД дубликат почтового индекса
     *
     * @param $postCode - почтовый индекс
     *
     * @return результат выполнения SQL запроса
     */
    public function isDuplicatePostCode($postCode = null)
    {
        if (!empty($postCode)) {
            $sql = "SELECT *
                    FROM `{$this->_table_4}`
                    WHERE `post_code` = '" . $this->db->escape($postCode) . "'";
            $result = $this->db->fetchOne($sql);
            return !empty($result) ? true : false;
        }
        return true;
    }

    /**
     * Метод добавления нового почтового индекса
     *
     * @param $array - массив с данными(зона, наименование индекса)
     *
     * @return результат выполнения SQL запроса
     */
    public function addPostCode($array = null)
    {
        if (!empty($array)) {
            $this->db->prepareInsert($array);
            return $this->db->insert($this->_table_4);
        }
        return false;
    }

    /**
     * Метод удаления почтового индекса
     *
     * @param $id - id почтового индекса
     *
     * @return результат выполнения SQL запроса
     */
    public function removePostCode($id = null)
    {
        if (!empty($id)) {
            $sql = "DELETE FROM `{$this->_table_4}`
                    WHERE `id` = " . intval($id);
            return $this->db->query($sql);
        }
        return false;
    }

    /**
     * Метод получения деталей доставки
     *
     * @param $user - массив с данными о пользователе
     *
     * @return результат выполнения SQL запроса
     */
    public function getShippingOptions($user = null)
    {
        if (!empty($user)) {
            $weight = $this->objCart->weight;

            if (($user['same_address'] == 1 && $user['country'] == COUNTRY_LOCAL)
                || ($user['same_address' == 0] && $user['ship_country'] == COUNTRY_LOCAL)
            ) {
                $postCode = $user['same_address'] == 1 ? $user['post_code'] : $user['ship_post_code'];
                $postCode = strtoupper(Helper::alphaNumericalOnly($postCode));
                $zone = $this->getZone($postCode);

                // Если зоны доставки нет, вернуть 0
                if (empty($zone)) {
                    return null;
                }

                $zoneId = $zone['zone'];
                $sql = "SELECT `t`.*,
                        IF (
                            {$weight} > (
                                SELECT MAX(`weight`)
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `zone` = {$zoneId}
                            ),
                            (
                                SELECT `cost`
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `zone` = {$zoneId}
                                ORDER BY `weight` DESC
                                LIMIT 0, 1
                            ),
                            (
                                SELECT `cost`
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `zone` = {$zoneId}
                                AND `weight` >= {$weight}
                                ORDER BY `weight` ASC
                                LIMIT 0, 1
                            )
                        ) AS `cost`
                        FROM `{$this->_table_2}` `t`
                        WHERE `t`.`local` = 1
                        AND `t`.`active` = 1
                        ORDER BY `t`.`order` ASC";
                return $this->db->fetchAll($sql);
            } else {
                $countryId = $user['same_address'] == 1 ? $user['country'] : $user['ship_country'];
                $sql = "SELECT `t`.*,
                        IF (
                            {$weight} > (
                                SELECT MAX(`weight`)
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `country` = {$countryId}
                            ),
                            (
                                SELECT `cost`
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `country` = {$countryId}
                                ORDER BY `weight` DESC
                                LIMIT 0, 1
                            ),
                            (
                                SELECT `cost`
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `country` = {$countryId}
                                AND `weight` >= {$weight}
                                ORDER BY `weight` ASC
                                LIMIT 0, 1
                            )
                        ) AS `cost`
                        FROM `{$this->_table_2}` `t`
                        WHERE `t`.`local` = 0
                        AND `t`.`active` = 1
                        ORDER BY `t`.`order` ASC";
                return $this->db->fetchAll($sql);
            }
        }
        return null;
    }

    /**
     * Метод получения зоны доставки по индексу
     *
     * @param $postCode - почтовый индекс
     *
     * @return результат выполнения SQL запроса
     */
    public function getZone($postCode = null, $strLen = 6)
    {
        if (!empty($postCode)) {
            $pc = substr($postCode, 0, $strLen);
            $sql = "SELECT *
                    FROM `{$this->_table_4}`
                    WHERE `post_code` = '" . $this->db->escape($pc) . "'
                    LIMIT 0, 1";
            $result = $this->db->fetchOne($sql);
            if (empty($result) && $strLen > 1) {
                $strLen--;
                return $this->getZone($postCode, $strLen);
            } else {
                return $result;
            }
        }
    }

    /**
     * Метод получения типа доставки по-умолчанию
     *
     * @param $user - массив с данными о юзере
     *
     * @return результат выполнения SQL запроса
     */
    public function getDefault($user = null)
    {
        if (!empty($user)) {
            $countryId = $user['same_address'] == 1 ? $user['country'] : $user['ship_country'];
            if ($countryId == COUNTRY_LOCAL) {
                $sql = "SELECT `t`.*
                        FROM `{$this->_table_2}` `t`
                        WHERE `t`.`local` = 1
                        AND `t`.`active` = 1
                        AND `t`.`default` = 1";
                return $this->db->fetchOne($sql);
            } else {
                $sql = "SELECT `t`.*
                        FROM `{$this->_table_2}` `t`
                        WHERE `t`.`local` = 0
                        AND `t`.`active` = 1
                        AND `t`.`default` = 1";
                return $this->db->fetchOne($sql);
            }
        }
    }

    /**
     * Метод получения типа доставки
     *
     * @param $user       - массив с данными о юзере
     * @param $shippingId - id типа доставки
     *
     * @return результат выполнения SQL запроса
     */
    public function getShipping($user = null, $shippingId = null)
    {
        if (!empty($user && !empty($shippingId))) {
            $weight = $this->objCart->weight;

            if (($user['same_address'] == 1 && $user['country'] == COUNTRY_LOCAL)
                || ($user['same_address' == 0] && $user['ship_country'] == COUNTRY_LOCAL)
            ) {
                $postCode = $user['same_address'] == 1 ? $user['post_code'] : $user['ship_post_code'];
                $postCode = strtoupper(Helper::alphaNumericalOnly($postCode));
                $zone = $this->getZone($postCode);

                // Если зоны доставки нет, вернуть 0
                if (empty($zone)) {
                    return null;
                }

                $zoneId = $zone['zone'];
                $sql = "SELECT `t`.*,
                        IF (
                            {$weight} > (
                                SELECT MAX(`weight`)
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `zone` = {$zoneId}
                            ),
                            (
                                SELECT `cost`
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `zone` = {$zoneId}
                                ORDER BY `weight` DESC
                                LIMIT 0, 1
                            ),
                            (
                                SELECT `cost`
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `zone` = {$zoneId}
                                AND `weight` >= {$weight}
                                ORDER BY `weight` ASC
                                LIMIT 0, 1
                            )
                        ) AS `cost`
                        FROM `{$this->_table_2}` `t`
                        WHERE `t`.`local` = 1
                        AND `t`.`active` = 1
                        AND `t`.`id` = {$shippingId}";
                return $this->db->fetchOne($sql);
            } else {
                $countryId = $user['same_address'] == 1 ? $user['country'] : $user['ship_country'];
                $sql = "SELECT `t`.*,
                        IF (
                            {$weight} > (
                                SELECT MAX(`weight`)
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `country` = {$countryId}
                            ),
                            (
                                SELECT `cost`
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `country` = {$countryId}
                                ORDER BY `weight` DESC
                                LIMIT 0, 1
                            ),
                            (
                                SELECT `cost`
                                FROM `{$this->_table}`
                                WHERE `type` = `t`.`id`
                                AND `country` = {$countryId}
                                AND `weight` >= {$weight}
                                ORDER BY `weight` ASC
                                LIMIT 0, 1
                            )
                        ) AS `cost`
                        FROM `{$this->_table_2}` `t`
                        WHERE `t`.`local` = 0
                        AND `t`.`active` = 1
                        AND `t`.`id` = {$shippingId}";
                return $this->db->fetchOne($sql);
            }
        }
        return null;
    }
}