<?php

/**
 * Helper
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
 * Helper
 * Класс помощник ()
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Helper
{
    /**
     * Добавляет ссылке css класс, если по этой ссылке открыта страница
     *
     * @param $page - открытая страница
     *
     * @return css класс active
     */
    /*
    public static function getActive($page = null)
    {
        if (!empty($page)) {
            if (is_array($page)) {
                $error = array();
                foreach ($page as $key => $value) {
                    if (Url::getParam($key) != $value) {
                        array_push($error, $key);
                    }
                }
                return empty($error) ? " class=\"active\"" : null;
            }
        }
        return $page == Url::currentPage() ? " class=\"active\"" : null;
    }*/

    /**
     * Преобразовывает все специальные символы, которые будут храниться в БД
     *
     * @param $string - строка
     * @param $case   - вариант форматирования
     *
     * @return $string - отформатированная строка
     */
    public static function encodeHTML($string, $case = 2)
    {
        switch ($case) {
        case 1:
            return htmlentities($string, ENT_NOQUOTES, 'UTF-8', false);
            break;
        case 2:
            $pattern = '<([a-zA-Z0-9\.\, "\'_\/\-\+~=;:\(\)?&#%![\]@]+)>';

            // Поместить только текст, разделенный HTML тегами в массив
            $textMatches = preg_split('/' . $pattern . '/', $string);

            // Массив для очищенного вывода
            $textSanitised = array();

            foreach ($textMatches as $key => $value) {
                $textSanitised[$key] = htmlentities(
                    html_entity_decode(
                        $value,
                        ENT_QUOTES,
                        'UTF-8'
                    ), ENT_QUOTES, 'UTF-8'
                );
            }

            foreach ($textMatches as $key => $value) {
                $string = str_replace($value, $textSanitised[$key], $string);
            }
            return $string;
            break;
        }
    }

    /**
     * Получает размер изображения
     *
     * @param $image - путь к изображению
     * @param $case  - какой параметр изображения получить
     *
     * @return размер изображения
     */
    public static function getImgSize($image, $case)
    {
        if (is_file($image)) {
            // 0 => Ширина, 1 => высота, 2 => тип файла, 3 => аттрибуты
            $size = getimagesize($image);
            return $size[$case];
        }
    }

    /**
     * Добавляет '...' в конце строки, если она слишком длинная
     *
     * @param $string - Строка
     * @param $length - длина строки
     *
     * @return обрезанна строка, с добавленными '...'
     */
    public static function shortenString($string, $length = 350)
    {
        if (strlen($string) > $length) {
            $string = trim(substr($string, 0, $length));
            $string = substr($string, 0, strrpos($string, " ")) . "&hellip;";
        } else {
            $string .= "&hellip;";
        }
        return $string;
    }

    /**
     * Редиректит пользователя
     *
     * @param $url - url страницы, на которую нужно сделать редирект
     *
     * @return void
     */
    public static function redirect($url = null)
    {
        if (!empty($url)) {
            header("Location: {$url}");
            exit;
        }
    }

    /**
     * Устанавливает дату по выбранному формату, если она пуста,
     * возвращает текущее время
     *
     * @param $case - выбор форматирования даты
     * @param $date - дата
     *
     * @return дату в указанном формате
     */
    public static function setDate($case = null, $date = null)
    {
        $date = empty($date) ? time() : strtotime($date);

        switch ($case) {
        case 1:
            // 01/01/2010
            return date('d/m/Y', $date);
            break;
        case 2:
            // Monday, 1st January 2010, 09:30:56
            //return date('l, jS F Y, H:i:s', $date);
            setlocale(LC_ALL, 'ru_RU.UTF-8');
            return strftime("%e %B %Y, %X", $date);
            break;
        case 3:
            // 2010-01-01-09-30-56
            return date('Y-m-d-H-i-s', $date);
            break;
        default:
            return date('Y-m-d H:i:s', $date);
        }
    }

    /**
     * Убирает из строки запрещенные символы, убираются прописные буквы
     *
     * @param $name - строка
     *
     * @return null
     */
    public static function cleanString($name = null)
    {
        if (!empty($name)) {
            return strtolower(preg_replace('/[^a-zA-Z0-9.]/', '-', $name));
        }
    }

    /**
     * Убирает из строки запрещенные символы, убираются прописные буквы, пробелы,
     * спец. символы
     *
     * @param $string - строка
     * @param $array  - массив строк
     *
     * @return $string
     */
    public static function clearString($string = null, $array = null)
    {
        if (!empty($string) && !self::isEmpty($array)) {
            $array = self::makeArray($array);
            foreach ($array as $key => $value) {
                $string = str_replace($value, '', $string);
            }
            return $string;
        }
    }

    /**
     * Проверка на пустоту и является ли значения числом или строкой с числом
     *
     * @param $value - значение
     *
     * @return true или false
     */
    public static function isEmpty($value = null)
    {
        return empty($value) && !is_numeric($value) ? true : false;
    }

    /**
     * Функция создания из значения массива
     *
     * @param $array - значение, или массив
     *
     * @return если значение является массивом,
     * возвращает его, если нет - делает из значения массив
     */
    public static function makeArray($array = null)
    {
        return is_array($array) ? $array : array($array);
    }

    /**
     * Печатает массив
     *
     * @param $array - массив
     *
     * @return значение текущего буфера и очищает его
     */
    public static function printArray($array = null)
    {
        ob_start();
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        return ob_get_clean();
    }

    /**
     * Удаляет все символы кроме цифр и букв
     *
     * @param $string - строка
     *
     * @return строку
     */
    public static function alphaNumericalOnly($string = null)
    {
        if (!empty($string)) {
            return preg_replace("/[^A-Za-z0-9]/", '', $string);
        }
    }

    /**
     * Метод фильтрации json, полученного от ajax
     *
     * @param $input - входные данные
     *
     * @return json
     */
    public static function json($input = null)
    {
        if (!empty($input)) {
            if (defined("JSON_UNESCAPED_UNICODE")) {
                return json_encode($input, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode($input, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
            }
        }
    }

    /**
     * Если массив пуст
     *
     * @param $array - массив
     *
     * @return пустой массив
     */
    public static function isArrayEmpty($array = null)
    {
        return (empty($array) || !is_array($array));
    }
}
