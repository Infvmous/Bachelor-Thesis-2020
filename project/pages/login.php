<?php

/**
 * Страница логина
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/login.html
 */

if (Login::isLogged(Login::$login_front)) {
    Helper::redirect(Login::$dashboard_front);
}

$objForm = new Form();
$objValidation = new Validation($objForm);
$objUser = new User($this->objUrl);

// Форма авторизации
if ($objForm->isPost('login_email')) {
    if ($objUser->isUser(
        $objForm->getPost('login_email'),
        $objForm->getPost('login_password')
    )
    ) {
        Login::loginFront(
            $objUser->id,
            $this->objUrl->href($this->objUrl->get(Login::$referrer))
        );
    } else {
        $objValidation->addToErrors('login');
    }
}

// Форма регистрации
if ($objForm->isPost('first_name')) {
    $objValidation->expected = array(
        'first_name',
        'last_name',
        'email',
        'password',
        'confirm_password'
    );

    $objValidation->required = array(
        'first_name',
        'last_name',
        'email',
        'password',
        'confirm_password'
    );

    $objValidation->special = array(
        'email' => 'email'
    );

    $objValidation->post_remove = array(
        'confirm_password'
    );

    $objValidation->post_format = array(
        'password' => 'password'
    );

    // Валидация пароля
    $pass_1 = $objForm->getPost('password');
    $pass_2 = $objForm->getPost('confirm_password');

    if (!empty($pass_1) && !empty($pass_2) && $pass_1 != $pass_2) {
        $objValidation->addToErrors('password_mismatch');
    }

    // Если почтовый адрес есть в БД, то показать ошибку валидации
    $email = $objForm->getPost('email');
    $user = $objUser->getByEmail($email);

    if (!empty($user)) {
        if ($user['active'] != 1) {
            $emailInactive = '<a href="#" id="emailInactive" ';
            $emailInactive .= 'data-id="';
            $emailInactive .= $user['id'];
            $emailInactive .= '">Адрес эл. почты уже занят. Отправить письмо активации заново</a>';
            $objValidation->message['email_inactive'] = $emailInactive;
            $objValidation->addToErrors('email_inactive');
        } else {
            $objValidation->addToErrors('email_duplicate');
        }
    }

    // Вызов метода валидации
    if ($objValidation->isValid()) {
        // Отправить письмо подверждения и редирект юзера на страницу,
        // что аккаунт подтвержден

        // Добавить хеш для аккаунта, который должен быть подвержден
        $objValidation->post['hash'] = mt_rand() . date('YmdHis') . mt_rand();

        // Добавить дату регистрации
        $objValidation->post['date'] = Helper::setDate();

        // Создание пользователя
        if ($objUser->addUser($objValidation->post, $objForm->getPost('password'))) {
            Helper::redirect($this->objUrl->href('registered'));
        } else {
            Helper::redirect($this->objUrl->href('registered-failed'));
        }
    }
}
require '_header.php'; ?>

<h1>Авторизация</h1>
<form action="" method="post">
    <table cellpadding="0" cellspacing="0" class="tbl_insert">

    <tr>
        <td>
            <?php echo $objValidation->validate('login'); ?>
            <input
                type="text"
                name="login_email"
                id="login_email"
                class="fld"
                placeholder="E-mail"
                value="<?php echo $objForm->stickyText('login_email'); ?>"
            />
        </td>
    </tr>

    <tr>
        <td>
            <input
                type="password"
                name="login_password"
                id="login_password"
                class="fld"
                placeholder="Пароль"
                value=""
            />
        </td>
    </tr>

    <tr>
        <td>
            <label for="btn_login" class="sbm sbm_blue fl_l">
                <input
                    type="submit"
                    id="btn_login"
                    class="btn"
                    value="Войти"
                />
            </label>
        </td>
    </tr>

    </table>
</form>

<!-- РЕГИСТРАЦИЯ -->
<div class="dev br_td">&#160;</div>
<h2>Впервые в DARKET?</h2>
<!-- ДОБАВИТЬ ССЫЛКУ НА СТРАНИЦУ РЕГИСТРАЦИИ -->

<form action="" method="post">
    <table cellpadding="0" cellspacing="0" class="tbl_insert">

    <tr>
        <td>
            <?php echo $objValidation->validate('first_name'); ?>
            <input
                type="text"
                name="first_name"
                id="first_name"
                class="fld"
                placeholder="Ваше имя"
                value="<?php echo $objForm->stickyText('first_name'); ?>"
            />
        </td>
    </tr>

    <tr>
        <td>
            <?php echo $objValidation->validate('last_name'); ?>
            <input
                type="text"
                name="last_name"
                id="last_name"
                class="fld"
                placeholder="Ваша фамилия"
                value="<?php echo $objForm->stickyText('last_name'); ?>"
            />
        </td>
    </tr>

    <tr>
        <td>
            <?php echo $objValidation->validate('email'); ?>
            <?php echo $objValidation->validate('email_duplicate'); ?>
            <?php echo $objValidation->validate('email_inactive'); ?>
            <input
                type="text"
                name="email"
                id="email"
                class="fld"
                placeholder="E-mail"
                value="<?php echo $objForm->stickyText('email'); ?>"
            />
        </td>
    </tr>

    <tr>
        <td>
            <?php echo $objValidation->validate('password'); ?>
            <?php echo $objValidation->validate('password_mismatch'); ?>
            <input
                type="password"
                name="password"
                id="password"
                class="fld"
                placeholder="Пароль"
                value=""
            />
        </td>
    </tr>

    <tr>
        <td>
            <?php echo $objValidation->validate('confirm_password'); ?>
            <input
                type="password"
                name="confirm_password"
                id="confirm_password"
                class="fld"
                placeholder="Повторите пароль"
                value=""
            />
        </td>
    </tr>

    <tr>
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

<?php require '_footer.php'; ?>