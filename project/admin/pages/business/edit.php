<?php

/**
 * Edit
 * Страница редактирования данных о бизнес профиле компании
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/panel/business/action/edit.html
 */

$objBusiness = new Business();
$business = $objBusiness->getBusiness();

$objCountry = new Country();
$countries = $objCountry->getCountries();

if (!empty($business)) {
    $objForm = new Form();
    $objValidation = new Validation($objForm);
    if ($objForm->isPost('name')) {
        $objValidation->expected = array(
            'name',
            'address',
            'country',
            'telephone',
            'email',
            'website',
            'vat_rate',
            'vat_number'
        );
        $objValidation->required = array(
            'name',
            'address',
            'country',
            'telephone',
            'vat_rate'
        );
        $objValidation->special = array(
            'email' => 'email'
        );

        $vars = $objForm->getPostArray($objValidation->expected);

        if ($objValidation->isValid()) {
            if ($objBusiness->updateBusiness($vars)) {
                Helper::redirect(
                    $this->objUrl->getCurrent(
                        array('action', 'id'), false, array('action', 'edited')
                    )
                );
            } else {
                Helper::redirect(
                    $this->objUrl->getCurrent(
                        array('action', 'id'), false, array('action', 'edited-failed')
                    )
                );
            }
        }
    }
    include '_header.php';?>

    <h1>Редактирование бизнес профиля</h1>
    <form action="" method="post">
        <table cellpadding="0" cellspacing="0" class="tbl_insert">
            <tr>
                <th><label for="name">Имя компании *</label></th>
                <td>
                <?php echo $objValidation->validate('name'); ?>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="fld"
                        value="<?php echo $objForm->stickyText('name', $business['name']); ?>"
                    />
                </td>
            </tr>
            <tr>
                <th><label for="address">Адрес *</label></th>
                <td>
                <?php echo $objValidation->validate('address'); ?>
                    <textarea
                        name="address"
                        id="address"
                        class="tar"
                        cols=""
                        rows=""
                    ><?php echo $objForm->stickyText('address', $business['address']); ?></textarea>
                </td>
            </tr>

            <?php if (!empty($countries)) { ?>
            <tr>
                <th><label for="country">Страна *</label></th>
                <td>
                    <?php echo $objValidation->validate('country'); ?>
                    <select name="country" id="country" class="sel">
                        <?php foreach ($countries as $row) { ?>
                            <option value="<?php echo $row['id']; ?>"
                            <?php echo $objForm->stickySelect('country', $row['id'], $business['country']); ?>>
                                <?php echo $row['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <?php } ?>

            <tr>
                <th><label for="telephone">Номер телефона *</label></th>
                <td>
                <?php echo $objValidation->validate('telephone'); ?>
                    <input
                        type="text"
                        name="telephone"
                        id="telephone"
                        class="fld"
                        value="<?php echo $objForm->stickyText('telephone', $business['telephone']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th><label for="email">E-mail *</label></th>
                <td>
                <?php echo $objValidation->validate('email'); ?>
                    <input
                        type="text"
                        name="email"
                        id="email"
                        class="fld"
                        value="<?php echo $objForm->stickyText('email', $business['email']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th><label for="website">Веб-сайт</label></th>
                <td>
                <?php echo $objValidation->validate('website'); ?>
                    <input
                        type="text"
                        name="website"
                        id="website"
                        class="fld"
                        value="<?php echo $objForm->stickyText('website', $business['website']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th><label for="vat_rate">Процент НДС *</label></th>
                <td>
                <?php echo $objValidation->validate('vat_rate'); ?>
                    <input
                        type="text"
                        name="vat_rate"
                        id="vat_rate"
                        class="fld"
                        value="<?php echo $objForm->stickyText('vat_rate', $business['vat_rate']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th><label for="vat_number">Номер НДС</label></th>
                <td>
                <?php echo $objValidation->validate('vat_number'); ?>
                    <input
                        type="text"
                        name="vat_number"
                        id="vat_number"
                        class="fld"
                        value="<?php echo $objForm->stickyText('vat_number', $business['vat_number']); ?>"
                    />
                </td>
            </tr>

            <tr>
                <th>&nbsp;</th>
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
