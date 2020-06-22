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
 * @link     http://darket-shop/panel/orders/action/edited.html
 */

$url = $this->objUrl->getCurrent(array('action', 'id'));
require '_header.php'; ?>

<h1>Изменение деталей заказа</h1>
<p>Выбранный заказ успешно изменен</p>
<a href="<?php echo $url; ?>">В список заказов</a>

<?php require '_footer.php'; ?>