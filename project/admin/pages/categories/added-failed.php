<?php
/**
 * Added failed
 * страница ошибки при добавлении категории
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/categories/action/added-failed.html
 */

$url = $this->objUrl->getCurrent(array('action', 'id'));
require '_header.php'; ?>

<h1>Добавление категории</h1>
<p>При добавлении категории возникла ошибка.<br />
<a href="<?php echo $url; ?>">Назад</a></p>

<?php require '_footer.php'; ?>