<p>
    <?php echo $first_name; ?>,  добро пожаловать в DARKET!
    <?php if (!empty($password)) { ?>
        <p>Ваши персональные данные, необходимые для входа</p>
        <p>Адрес эл. почты: <?php echo $email; ?><br />
        <!--Пароль: <?php //echo $password; ?></p>-->
    <?php } ?>
</p>

<p>Перейдите по ссылке ниже, чтобы завершить регистрацию.</p>
<p><?php echo $link; ?></p>