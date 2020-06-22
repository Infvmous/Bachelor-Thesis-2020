<?php
/**
 * Navigation
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
 * Класс Navigation
 * Содержит в себе методы для работы с навигацией по URL
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru
 */
class Navigation
{
    public $objUrl;
    public $classActive = 'active';

    /**
     * Конструктор класса
     *
     * @param $objUrl - объект URL
     *
     * @return void
     */
    public function __construct($objUrl = null)
    {
        $this->objUrl = is_object($objUrl) ? $objUrl : new Url();
    }

    /**
     * Замена методу getAcive в Helper классе
     * Добавляет ссылкам класс Active, когда открыты
     *
     * @param $main   -
     * @param $pairs  -
     * @param $single -
     *
     * @return void
     */
    public function active($main = null, $pairs = null, $single = true)
    {
        if (!empty($main)) {
            if (empty($pairs)) {
                if ($main == $this->objUrl->main) {
                    return !$single ?
                        ' ' . $this->classActive :
                        ' class="' . $this->classActive . '"';
                }
            } else {
                $exceptions = array();
                foreach ($pairs as $key => $value) {
                    $paramUrl = $this->objUrl->get($key);
                    if ($paramUrl != $value) {
                        $exceptions[] = $key;
                    }
                }
                if ($main == $this->objUrl->main && empty($exceptions)) {
                    return !$single ?
                        ' ' . $this->classActive :
                        ' class="' . $this->classActive . '"';
                }
            }
        }
    }
}