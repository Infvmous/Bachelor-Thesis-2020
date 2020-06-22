<?php
/**
 * Upload
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
 * Класс Upload
 * Содержит в себе методы для загрузки файлов с фронтэнда на сервер
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru
 */

class Upload
{
    public $files = array();
    public $overwrite = false;
    public $errors = array();
    public $names = array();

    /**
     * Конструктор класса
     *
     * @return null
     */
    public function __construct()
    {
        $this->getUploads();
    }

    /**
     * Получает значения и ключи из глобального массива
     * и помещает в переменную класса
     *
     * @return null
     */
    public function getUploads()
    {
        if (!empty($_FILES)) {
            foreach ($_FILES as $key => $value) {
                $this->files[$key] = $value;
            }
        }
    }

    /**
     * Загружает изображение на сервер, добавляет запись об этом изображении
     * в БД для конкретного товара
     * Чтобы не было повторений, меняет имя файла на формат:
     * Дата+имя
     *
     * @param $path - путь к директории с изображениями
     *
     * @return true, если не найдено ошибок
     */
    public function upload($path = null)
    {
        if (!empty($path) && is_dir($path) && !empty($this->files)) {
            foreach ($this->files as $key => $value) {
                $name = Helper::cleanString($value['name']);
                if ($this->overwrite == false && is_file($path.DS.$name)) {
                    $prefix = date('YmdHis', time());
                    $name = $prefix."-".$name;
                }
                // Перемещение файла из временного каталога в каталог назначения,
                // то есть на сервер
                if (!move_uploaded_file($value['tmp_name'], $path.DS.$name)) {
                    $this->errors[] = $key;
                }
                $this->names[] = $name;
            }
            return empty($this->errors) ? true : false;
        }
        return false;
    }
}