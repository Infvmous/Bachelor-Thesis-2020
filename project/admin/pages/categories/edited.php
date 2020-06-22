<?php
/**
 * Edited
 * страница успешного изменения категории
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/categories/action/edited.html
 */

$url = $this->objUrl->getCurrent(array('action', 'id'));
require '_header.php'; ?>

<h1>Успешно</h1>
<p>Выбранная категория успешно переименована</p>
<a href="<?php echo $url; ?>">В список категорий</a>

<?php require '_footer.php'; ?>