<?php
/**
 * Edited no upload
 * страница успешного изменения товара без загрузки изображения
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/products/edited-no-upload.html
 */

$url = $this->objUrl->getCurrent(array('action', 'id'));
require '_header.php'; ?>

<h1>Успешно</h1>
<p>Товар был успешно отредактирован<br />
Изображение изменено не было.</p>
<a href="<?php echo $url; ?>">В список товаров</a>

<?php require '_footer.php'; ?>