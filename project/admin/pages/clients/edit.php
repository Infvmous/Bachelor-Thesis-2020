<?php

/**
 * Edit
 * Страница редактирования данных о пользователе в контроль-панели
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/clients/action/edit.html
 */

$id = $this->objUrl->get('id');

if (!empty($id)) {

    $objUser = new User();
    $user = $objUser->getUser($id);

    if (!empty($user)) {

        $objForm = new Form();
        $objValidation = new Validation($objForm);

        if ($objForm->isPost('first_name')) {

            $objValidation->expected = array(
                'first_name',
                'last_name',

                'address_1',
                'address_2',
                'city',
                'state',
                'post_code',
                'country',

                'ship_address_1',
                'ship_address_2',
                'ship_city',
                'ship_state',
                'ship_post_code',
                'ship_country',

                'email'
            );

            $objValidation->required = array(
                'first_name',
                'last_name',
                'address_1',
                'city',
                'state',
                'post_code',
                'country',
                'email'
            );

            $objValidation->special = array(
                'email' => 'email'
            );

            $email = $objForm->getPost('email');
            $duplicate = $objUser->getByEmail($email);

            // Если при редактировании почты у юзера
            // Проверка существует ли уже такая почта
            // Если да, показать ошибку валидации
            if (!empty($duplicate) && $duplicate['id'] != $user['id']) {
                $objValidation->addToErrors('email_duplicate');
            }

            if ($objValidation->isValid()) {
                if ($objUser->updateUser($objValidation->post, $user['id'])) {
                    Helper::redirect(
                        $this->objUrl->getCurrent(
                            array('action', 'id'),
                            false,
                            array('action', 'edited')
                        )
                    );
                } else {
                    Helper::redirect(
                        $this->objUrl->getCurrent(
                            array('action', 'id'),
                            false,
                            array('action', 'edited-failed')
                        )
                    );
                }
            }
        }
        include '_header.php'; ?>

        <h1>Редактирование информации о клиенте №<?php echo $user['id']; ?></h1>
        <form action="" method="post">
            <table cellpadding="0" cellspacing="0" class="tbl_insert">
                <tr>
                    <th><label for="first_name">Имя *</label></th>
                    <td>
                    <?php echo $objValidation->validate('first_name'); ?>
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
                    <?php echo $objValidation->validate('last_name'); ?>
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
                    <th><label for="email">Адрес эл. почты *</label></th>
                    <td>
                    <?php echo $objValidation->validate('email'); ?>
                        <input
                            type="text"
                            name="email"
                            id="email"
                            class="fld"
                            value="<?php echo $objForm->stickyText('email', $user['email']); ?>"
                        />
                    </td>
                </tr>
            </table>

            <h3>Платежный адрес</h3>
            <table cellpadding="0" cellspacing="0" class="tbl_insert">
                <tr>
                    <th><label for="address">Адрес *</label></th>
                    <td>
                    <?php echo $objValidation->validate('address_1'); ?>
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
                    <?php echo $objValidation->validate('address_2'); ?>
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
                    <th><label for="country">Страна *</label></th>
                    <td>
                        <?php echo $objValidation->validate('country'); ?>
                        <?php echo $objForm->getCountriesSelect($user['country']); ?>
                    </td>
                </tr>

                <tr>
                    <th><label for="city">Город *</label></th>
                    <td>
                    <?php echo $objValidation->validate('city'); ?>
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
                    <?php echo $objValidation->validate('state'); ?>
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
                    <th><label for="post_code">Почтовый индекс *</label></th>
                    <td>
                    <?php echo $objValidation->validate('post_code'); ?>
                        <input
                            type="text"
                            name="post_code"
                            id="post_code"
                            class="fld"
                            value="<?php echo $objForm->stickyText('post_code', $user['post_code']); ?>"
                        />
                    </td>
                </tr>
            </table>

            <h3>Адрес доставки</h3>
            <table cellpadding="0" cellspacing="0" class="tbl_insert">
                <tr>
                    <th><label for="ship_address">Адрес</label></th>
                    <td>
                    <?php echo $objValidation->validate('ship_address_1'); ?>
                        <input
                            type="text"
                            name="ship_address_1"
                            id="ship_address_1"
                            class="fld"
                            value="<?php echo $objForm->stickyText('ship_address_1', $user['ship_address_1']); ?>"
                        />
                    </td>
                </tr>

                <tr>
                    <th><label for="ship_address_2">Доп. адрес</label></th>
                    <td>
                    <?php echo $objValidation->validate('ship_address_2'); ?>
                        <input
                            type="text"
                            name="ship_address_2"
                            id="ship_address_2"
                            class="fld"
                            value="<?php echo $objForm->stickyText('ship_address_2', $user['ship_address_2']); ?>"
                        />
                    </td>
                </tr>

                <tr>
                    <th><label for="ship_country">Страна</label></th>
                    <td>
                        <?php echo $objValidation->validate('ship_country'); ?>
                        <?php echo $objForm->getCountriesSelect($user['ship_country'], 'ship_country', true); ?>
                    </td>
                </tr>

                <tr>
                    <th><label for="ship_city">Город</label></th>
                    <td>
                    <?php echo $objValidation->validate('ship_city'); ?>
                        <input
                            type="text"
                            name="ship_city"
                            id="ship_city"
                            class="fld"
                            value="<?php echo $objForm->stickyText('ship_city', $user['ship_city']); ?>"
                        />
                    </td>
                </tr>

                <tr>
                    <th><label for="ship_state">Область</label></th>
                    <td>
                    <?php echo $objValidation->validate('ship_state'); ?>
                        <input
                            type="text"
                            name="ship_state"
                            id="ship_state"
                            class="fld"
                            value="<?php echo $objForm->stickyText('ship_state', $user['ship_state']); ?>"
                        />
                    </td>
                </tr>

                <tr>
                    <th><label for="post_code">Почтовый индекс</label></th>
                    <td>
                    <?php echo $objValidation->validate('ship_post_code'); ?>
                        <input
                            type="text"
                            name="ship_post_code"
                            id="ship_post_code"
                            class="fld"
                            value="<?php echo $objForm->stickyText('ship_post_code', $user['ship_post_code']); ?>"
                        />
                    </td>
                </tr>
            </table>

            <table cellpadding="0" cellspacing="0" class="tbl_insert">
                <tr>
                    <td>
                        <label for="btn" class="sbm sbm_blue fl_l">
                            <input
                                type="submit"
                                id="btn"
                                class="btn"
                                value="Сохранить"
                            />
                        </label>
                    </td>
                </tr>
            </table>
        </form>

        <?php include '_footer.php';
    }
}
