<?php

/**
 * Checkout
 * Страница подтверждения данных о пользователе
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/checkout.html
 */

Login::restrictFront($this->objUrl);

$objUser = new User();
$user = $objUser->getUser(Session::getSession(Login::$login_front));

if (!empty($user)) {

    $objForm = new Form();
    $objValid = new Validation($objForm);

    if ($objForm->isPost('first_name')) {

        $objValid->expected = array(
            'first_name',
            'last_name',
            'address_1',
            'address_2',
            'city',
            'state',
            'post_code',
            'country',
            'email',

            'same_address',
            'ship_address_1',
            'ship_address_2',
            'ship_city',
            'ship_state',
            'ship_post_code',
            'ship_country'
        );

        $objValid->required = array(
            'first_name',
            'last_name',
            'address_1',
            'city',
            'state',
            'post_code',
            'country',
            'email',
            'same_address'
        );

        $objValid->special = array(
            'email' => 'email'
        );

        $array = $objForm->getPostArray($objValid->expected);
        if (empty($array['same_address'])) {
            $objValid->required[] = 'ship_address_1';
            $objValid->required[] = 'ship_city';
            $objValid->required[] = 'ship_state';
            $objValid->required[] = 'ship_post_code';
            $objValid->required[] = 'ship_country';
        } else {
            $array['ship_address_1'] = null;
            $array['ship_city'] = null;
            $array['ship_state'] = null;
            $array['ship_post_code'] = null;
            $array['ship_country'] = null;
        }

        if ($objValid->isValid($array)) {
            if ($objUser->updateUser($objValid->post, $user['id'])) {
                Helper::redirect($this->objUrl->href('summary'));
            } else {
                $message  = "<p class=\"red\">";
                $message .= "Возникли проблемы с обновлением Вашей контактной информации.";
                $message .= "<br />";
                $message .= "Пожалуйста, свяжитесь с администратором.</p>";
            }
        }
    }

    include_once '_header.php'; ?>

    <h1>Контактная информация</h1>

    <?php echo !empty($message) ? $message : null; ?>

    <form action="" method="post">
        <table class="tbl_insert">
            <tbody id="billingAddress">
            <tr>
                <th><label for="first_name">Имя *</label></th>
                <td>
                <?php echo $objValid->validate('first_name'); ?>
                    <input
                        type="text"
                        name="first_name"
                        id="first_name"
                        class="fld"
                        value="<?php echo $objForm->stickyText('first_name', $user['first_name']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th><label for="last_name">Фамилия *</label></th>
                <td>
                <?php echo $objValid->validate('last_name'); ?>
                    <input
                        type="text"
                        name="last_name"
                        id="last_name"
                        class="fld"
                        value="<?php echo $objForm->stickyText('last_name', $user['last_name']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th><label for="country">Страна *</label></th>
                <td>
                    <?php echo $objValid->validate('country'); ?>
                    <?php echo $objForm->getCountriesSelect(134); ?>
                </td>
            </tr>

            <tr>
                <th><label for="city">Город *</label></th>
                <td>
                <?php echo $objValid->validate('city'); ?>
                    <input
                        type="text"
                        name="city"
                        id="city"
                        class="fld"
                        value="<?php echo $objForm->stickyText('city', $user['city']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th><label for="state">Область *</label></th>
                <td>
                <?php echo $objValid->validate('state'); ?>
                    <input
                        type="text"
                        name="state"
                        id="state"
                        class="fld"
                        value="<?php echo $objForm->stickyText('state', $user['state']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th><label for="address">Адрес *</label></th>
                <td>
                <?php echo $objValid->validate('address_1'); ?>
                    <input
                        type="text"
                        name="address_1"
                        id="address_1"
                        class="fld"
                        value="<?php echo $objForm->stickyText('address_1', $user['address_1']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th><label for="address_2">Доп. адрес</label></th>
                <td>
                <?php echo $objValid->validate('address_2'); ?>
                    <input
                        type="text"
                        name="address_2"
                        id="address_2"
                        class="fld"
                        value="<?php echo $objForm->stickyText('address_2', $user['address_2']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th><label for="post_code">Почтовый индекс *</label></th>
                <td>
                <?php echo $objValid->validate('post_code'); ?>
                    <input
                        type="text"
                        name="post_code"
                        id="post_code"
                        class="fld"
                        value="<?php echo $objForm->stickyText('post_code', $user['post_code']); ?>"
                    />
                </td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td>Использовать этот адрес для доставки товаров?</td>
            </tr>
            <tr>
                <th>&nbsp;</th>
                <td>
                    <?php echo $objValid->validate('same_address'); ?>
                    <label for="same_address_1">
                        <input
                            type="radio"
                            name="same_address"
                            id="same_address_1"
                            value="1"
                            class="showHideRadio"
                            <?php echo $objForm->stickyRadio('same_address', 1, $user['same_address']); ?>
                        /> Да
                    </label>
                    <label for="same_address_0">
                        <input
                            type="radio"
                            name="same_address"
                            id="same_address_0"
                            value="0"
                            class="showHideRadio"
                            <?php echo $objForm->stickyRadio('same_address', 0, $user['same_address']); ?>
                        /> Нет
                    </label>
                </td>
            </tr>
        </tbody>

        <tbody id="deliveryAddress" class="same_address<?php echo $objForm->stickyRemoveClass('same_address', 0, $user['same_address'], 'dn'); ?>">
            <tr>
                <th><label for="ship_country">Страна *</label></th>
                <td>
                    <?php echo $objValid->validate('ship_country'); ?>
                    <?php echo $objForm->getCountriesSelect($user['ship_country'], 'ship_country'); ?>
                </td>
            </tr>
            <tr>
                <th><label for="ship_city">Город *</label></th>
                <td>
                    <?php echo $objValid->validate('ship_city'); ?>
                    <input type="text" name="ship_city"
                        id="ship_city" class="fld"
                        value="<?php echo $objForm->stickyText('ship_city', $user['ship_city']); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="ship_state">Область *</label></th>
                <td>
                    <?php echo $objValid->validate('ship_state'); ?>
                    <input type="text" name="ship_state"
                        id="ship_state" class="fld"
                        value="<?php echo $objForm->stickyText('ship_state', $user['ship_state']); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="ship_address_1">Адрес *</label></th>
                <td>
                    <?php echo $objValid->validate('ship_address_1'); ?>
                    <input type="text" name="ship_address_1"
                        id="ship_address_1" class="fld"
                        value="<?php echo $objForm->stickyText('ship_address_1', $user['ship_address_1']); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="ship_address_2">Доп. адрес</label></th>
                <td>
                    <?php echo $objValid->validate('ship_address_2'); ?>
                    <input type="text" name="ship_address_2"
                        id="ship_address_2" class="fld"
                        value="<?php echo $objForm->stickyText('ship_address_2', $user['ship_address_2']); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="ship_post_code">Почтовый индекс *</label></th>
                <td>
                    <?php echo $objValid->validate('ship_post_code'); ?>
                    <input type="text" name="ship_post_code"
                        id="ship_post_code" class="fld"
                        value="<?php echo $objForm->stickyText('ship_post_code', $user['ship_post_code']); ?>" />
                </td>
            </tr>
        </tbody>

            <tr>
                <th>&nbsp;</th>
                <td>
                    <label for="btn" class="sbm sbm_blue fl_l">
                        <input
                            type="submit"
                            id="btn"
                            class="btn"
                            value="Продолжить"
                        />
                    </label>
                </td>
            </tr>
        </table>
    </form>

    <?php include_once '_footer.php';
} else {
    Helper::redirect($this->objUrl->href('error'));
}
?>