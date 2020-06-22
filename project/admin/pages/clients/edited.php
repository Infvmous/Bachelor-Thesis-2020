<?php
/**
 * Edited
 * страница успешного изменения товара
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/clients/action/edited.html
 */
$url = $this->objUrl->getCurrent(array('action', 'id'));
require '_header.php'; ?>

<h1>Изменение клиентской информации</h1>
<p>Данные выбранного клиента успешно изменены<br />
<a href="<?php echo $url; ?>">Назад</a></p>

<?php require '_footer.php'; ?>