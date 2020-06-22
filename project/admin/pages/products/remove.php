<?php
/**
 * Remove
 * Страница удаления товара
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/products/remove.html
 */

$id = $this->objUrl->get('id');

if (!empty($id)) {
    $objCatalog = new Catalog();
    $product = $objCatalog->getProduct($id);

    if (!empty($product)) {
        $yes = $this->objUrl->getCurrent() . '/remove/1';
        $no = 'javascript:history.go(-1)';

        $remove = $this->objUrl->get('remove');
        if (!empty($remove)) {
            $objCatalog->removeProduct($id);
            Helper::redirect(
                $this->objUrl->getCurrent(
                    array('action','id', 'remove', 'srch', Paging::$key)
                )
            );
        }

        include '_header.php'; ?>

<h1>Удаление товара №<?php echo $id; ?></h1>
<p>
    Вы уверены что хотите удалить товар &laquo;<?php echo $product['name']; ?>&raquo;?</br>
    Это действие нельзя отменить.
</p>
<a href="<?php echo $yes; ?>">Да</a> | <a href="<?php echo $no; ?>">Нет, вернуться в список товаров</a>

        <?php include '_footer.php';
    }
}