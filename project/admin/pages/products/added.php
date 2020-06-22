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
 * @link     https://darket-shop.ru/panel/products/added.html
 */
$url = $this->objUrl->getCurrent(array('action', 'id'));
require '_header.php'; ?>

<h1>Успешно</h1>
<p>Новый товар был успешно добавлен.<br /></p>
<a href="<?php echo $url; ?>">В список товаров</a>

<?php require '_footer.php'; ?>