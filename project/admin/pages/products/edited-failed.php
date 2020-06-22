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
 * @link     https://darket-shop.ru/panel/products/edited-failed.html
 */
$url = $this->objUrl->getCurrent(array('action', 'id'));
require '_header.php'; ?>

<h1>Ошибка</h1>
<p>Произошла ошибка при редактировании информации о товаре.</p>
<a href="<?php echo $url; ?>">В список товаров</a>

<?php require '_footer.php'; ?>