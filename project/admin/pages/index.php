<?php
/**
 * Index
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel
 */

if (Login::isLogged(Login::$login_admin)) {
    Helper::redirect(Login::$dashboard_admin);
}

$objForm = new Form();
$objValid = new Validation($objForm);

if ($objForm->isPost('login_email')) {
    $objAdmin = new Admin();

    if ($objAdmin->isUser($objForm->getPost('login_email'), $objForm->getPost('login_password'))) {
        Login::loginAdmin(
            $objAdmin->id,
            $this->objUrl->href($this->objUrl->get(Login::$referrer))
        );
    } else {
        $objValid->addToErrors('login');
    }
}

require_once '_header.php'; ?>

<form action="" method="post">
    <h1>Авторизация</h1>
    <table cellpadding="0" cellspacing="0" class="tbl_insert">
        <tr>
            <th><label for="login_email">Логин</label></th>
            <td>
                <?php echo $objValid->validate('login'); ?>
                <input
                    type="text"
                    name="login_email"
                    id="login_email"
                    class="fld"
                    value=""
                >
            </td>
        </tr>
        <tr>
            <th><label for="login_password">Пароль</label></th>
            <td>
                <input
                    type="password"
                    name="login_password"
                    id="login_password"
                    class="fld"
                    value=""
                >
            </td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td>
                <label for="btn_login" class="sbm sbm_blue fl_l">
                    <input type="submit" id="btn_login" class="btn" value="Войти">
                </label>
            </td>
        </tr>
    </table>
</form>

<?php require_once '_footer.php'; ?>