<?php
/**
 * Added failed
 * страница ошибки при изменении категории
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/categories/action/edited-failed.html
 */
$url = $this->objUrl->getCurrent(array('action', 'id'));
require '_header.php'; ?>

<h1>Изменение категории</h1>
<p>Произошла ошибка при изменении категории<br />
<a href="<?php echo $url; ?>">Назад</a></p>

<?php require '_footer.php'; ?>