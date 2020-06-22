<?php
/**
 * Remove
 * Страница удаления категории
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/categories/action/remove.html
 */

$id = $this->objUrl->get('id');

if (!empty($id)) {
    $objCatalog = new Catalog();
    $category = $objCatalog->getCategory($id);
    if (!empty($category)) {
        $yes = $this->objUrl->getCurrent() . '/remove/1';
        $no = 'javascript:history.go(-1)';

        $remove = $this->objUrl->get('remove');
        if (!empty($remove) && $category['products_count'] == 0) {
            $objCatalog->removeCategory($id);
            Helper::redirect(
                $this->objUrl->getCurrent(
                    array('action','id', 'remove', 'srch', Paging::$key)
                )
            );
        }
        include '_header.php'; ?>

<h1>Удаление категории &laquo;<?php echo $category['name']; ?>&raquo;</h1>
<p>Вы уверены что хотите удалить категорию?</br>
Это действие нельзя отменить.</p>
<a href="<?php echo $yes; ?>">Да</a> | <a href="<?php echo $no; ?>">Нет, вернуться в список категорий</a>

        <?php include '_footer.php';
    }
}