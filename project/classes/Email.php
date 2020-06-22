<?php

/**
 * Email
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/Exception.php';
require 'libs/PHPMailer/PHPMailer.php';
require 'libs/PHPMailer/SMTP.php';

/**
 * Класс Email
 * Класс, предназначенный для работы с электронной почтой
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru
 */

class Email
{
    private $_objMailer;
    public $objUrl;

    /**
     * Конструктор класса
     *
     * @param $objUrl - обьект класса Url
     *
     * @return void
     */
    public function __construct($objUrl = null)
    {
        $this->objUrl = is_object($objUrl) ? $objUrl : new Url();
        $this->_objMailer = new PHPMailer(true);
        $this->_objMailer->CharSet = 'UTF-8';

        try {
            //Server settings
            $this->_objMailer->isSMTP();
            //$this->_objMailer->SMTPDebug = 2;
            $this->_objMailer->Host       = 'smtp.yandex.ru';
            $this->_objMailer->SMTPAuth   = true;
            $this->_objMailer->Username   = 'admin@darket-shop.ru';
            $this->_objMailer->Password   = 'fuckyou123!';
            $this->_objMailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->_objMailer->Port       = 587;

            //Recipients
            $this->_objMailer->setFrom('admin@darket-shop.ru', 'DARKET');
            $this->_objMailer->addAddress('admin@darket-shop.ru', 'DARKET');

            $this->_objMailer->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error:
            {$this->_objMailer->ErrorInfo}";
        }
    }

    /**
     * Метод создающий упаковки имейла
     *
     * @param $case  - флаг
     * @param $array - массив с данными о пользователе
     *
     * @return true, если письмо отправлено
     */
    public function process($case = null, $array = null)
    {
        if (!empty($case) && !empty($array)) {
            switch($case) {
            case 1:
                //Добавить URL в массив
                $link  = "<a href=\"";
                $link .= SITE_URL . $this->objUrl->href(
                    'activate', array('code', $array['hash'])
                );
                $link .= "\">";
                $link .= SITE_URL . $this->objUrl->href(
                    'activate', array('code', $array['hash'])
                );
                $link .= "</a>";
                $array['link'] = $link;

                $this->_objMailer->Subject = "Активируйте ваш аккаунт";

                //Создает body для письма
                $this->_objMailer->MsgHTML($this->fetchEmail($case, $array));
                $this->_objMailer->AddAddress(
                    $array['email'],
                    $array['first_name'] . ' ' . $array['last_name']
                );
                break;
            }

            // Отправка емейла
            if ($this->_objMailer->send()) {
                $this->_objMailer->ClearAddresses();
                return true;
            }
            return false;
        }
    }

    /**
     * Метод получения емейла
     *
     * @param $case  - флаг
     * @param $array - массив с данными о пользователе
     *
     * @return void
     */
    public function fetchEmail($case = null, $array = null)
    {
        if (!empty($case)) {

            if (!empty($array)) {
                foreach ($array as $key => $value) {
                    ${$key} = $value;
                }
            }

            ob_start();
            include EMAILS_PATH . DS . $case . ".php";
            // получает текущее содержание буфера и удаляет его
            $out = ob_get_clean();

            return $this->wrapEmail($out);
        }
    }

    /**
     * Возвращает содержание письма
     *
     * @param $content - контент письма
     *
     * @return div и $content
     */
    public function wrapEmail($content = null)
    {
        if (!empty($content)) {
            $out = "<div style=\"font-family:Arial,Verdana,Sans-serif;";
            $out .= " font-size:16px; color:#333; line-height:21px;";
            $out .= "\">";
            $out .= $content;
            $out .= "</div>";
            return $out;
        }
    }
}
