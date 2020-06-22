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
 * @link     http://darket-shop/panel/clients/action/remove.html
 */

$id = $this->objUrl->get('id');

if (!empty($id)) {
    $objUser = new User();
    $user = $objUser->getUser($id);
    if (!empty($user)) {
        $objOrder = new Order();
        $orders = $objOrder->getClientOrders($id);
        // Удаление пользователя только если у него нет текущих заказов
        if (empty($orders)) {
            $yes = $this->objUrl->getCurrent() . '/remove/1';
            $no = 'javascript:history.go(-1)';

            $remove = Url::getParam('remove');
            if (!empty($remove)) {
                $objUser->removeUser($id);
                Helper::redirect(
                    $this->objUrl->getCurrent(
                        array('action','id', 'remove', 'srch', Paging::$key)
                    )
                );
            }
            include '_header.php';?>

<h1>Удаление записи о клиенте №<?php echo $user['id']; ?></h1>
<p>
Вы уверены что хотите удалить аккаунт клиента &laquo;<?php echo $user['first_name'] . " " . $user['last_name']; ?>&raquo;?<br />
Это действие нельзя отменить</p>
<a href="<?php echo $yes; ?>">Да</a> | <a href="<?php echo $no; ?>">Нет, вернуться в список клиентов</a>

            <?php
            include '_footer.php';
        }
    }
}