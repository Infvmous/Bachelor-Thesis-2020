<?php
/**
 * Added
 * страница успешного добавления товара в БД
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/categories/action/added.html
 */

$url = $this->objUrl->getCurrent(array('action', 'id'));
require '_header.php'; ?>

<h1>Добавление категории</h1>
<p>Новая категория товаров успешно добавлена<br />
<a href="<?php echo $url; ?>">Назад</a></p>

<?php require '_footer.php'; ?>