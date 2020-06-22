<?php
/**
 * Remove
 * Страница удаления заказа
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/orders/action/remove.html
 */

$id = $this->objUrl->get('id');

if (!empty($id)) {
    $objOrder = new Order();
    $order = $objOrder->getOrder($id);
    if (!empty($order)) {
        $yes = $this->objUrl->getCurrent() . '/remove/1';
        $no = 'javascript:history.go(-1)';

        $remove = $this->objUrl->get('remove');
        if (!empty($remove)) {
            $objOrder->removeOrder($id);
            Helper::redirect(
                $this->objUrl->getCurrent(
                    array('action','id', 'remove', 'srch', Paging::$key)
                )
            );
        }
        include '_header.php'; ?>

<h1>Удаление заказа</h1>
<p>
    Вы уверены что хотите удалить этот заказ?</br>
    Это действие нельзя отменить!</br>
    <a href="<?php echo $yes; ?>">Да</a> | <a href="<?php echo $no; ?>">Нет, вернуться назад</a>
</p>

        <?php
        include '_footer.php';
    }
}