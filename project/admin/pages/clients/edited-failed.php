<?php
/**
 * Added failed
 * страница ошибки при изменении товара
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/clients/action/edited-failed.html
 */
$url = $this->objUrl->getCurrent(array('action', 'id'));
require '_header.php'; ?>

<h1>Изменение клиентской информации</h1>
<p>Произошла ошибка при изменении данных выбранного клиента<br />
<a href="<?php echo $url; ?>">Назад</a></p>

<?php require '_footer.php'; ?>