<?php
/**
 * Add
 * Страница добавления товара
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/panel/products/add.html
 */

$objForm = new Form();
$objValid = new Validation($objForm);

$objCatalog = new Catalog();
$categories = $objCatalog->getCategories(1);

if ($objForm->isPost('name')) {
    $objValid->expected = array(
        'category',
        'name',
        'description',
        'price',
        'weight',
        'qty',
        'identity',
        'meta_title',
        'meta_description',
        'meta_keywords'
    );

    $objValid->required = array(
        'category',
        'name',
        'description',
        'price',
        'weight',
        'identity',
        'meta_title',
        'meta_description',
        'meta_keywords'
    );

    if ($objValid->isValid()) {
        $objValid->post['identity'] = Helper::cleanString($objValid->post['identity']);
        if ($objCatalog->isDuplicateProduct($objValid->post['identity'])) {
            $objVaid->addToErrors('duplicate_identity');
        } else {
            if ($objCatalog->addProduct($objValid->post)) {
                $objUpload = new Upload();

                if ($objUpload->upload(CATALOG_PATH)) {
                    $objCatalog->updateProduct(
                        array('image' => $objUpload->names[0]),
                        $objCatalog->id
                    );
                    // Если товар успешно добавлен
                    Helper::redirect(
                        $this->objUrl->getCurrent(
                            array('action', 'id'),
                            false,
                            array('action', 'added')
                        )
                    );
                } else {
                    Helper::redirect(
                        $this->objUrl->getCurrent(
                            array('action', 'id'),
                            false,
                            array('action', 'added-no-upload')
                        )
                    );
                }
            } else {
                // Если произошла ошибка добавления
                Helper::redirect(
                    $this->objUrl->getCurrent(
                        array('action', 'id'),
                        false,
                        array('action', 'added-failed')
                    )
                );
            }
        }
    }
}

require '_header.php';
?>

<h1>Добавление нового товара</h1>
<form action="" method="post" enctype="multipart/form-data">
    <table cellpadding="0" cellspacing="0" class="tbl_insert">
        <tr>
            <th><label for="category">Категория *</label></th>
            <td>
                <?php echo $objValid->validate('category'); ?>
                <select name="category" id="category" class="sel">
                    <option value="">Выберите&hellip;</option>

                    <?php if (!empty($categories)) { ?>

                        <?php foreach ($categories as $cat) { ?>
                            <option value="<?php echo $cat['id']; ?>"
                                <?php echo $objForm->stickySelect('category', $cat['id']); ?>>
                                <?php echo Helper::encodeHtml($cat['name']); ?>
                            </option>

                        <?php } ?>

                    <?php } ?>

                </select>
            </td>
        </tr>

        <tr>
            <th><label for="name">Название *</label></th>
            <td>
                <?php echo $objValid->validate('name'); ?>
                <input
                    type="text"
                    name="name"
                    id="name"
                    value="<?php echo $objForm->stickyText('name'); ?>"
                    class="fld"
                />
            </td>
        </tr>

        <tr>
            <th><label for="description">Описание *</label></th>
            <td>
                <?php echo $objValid->validate('description'); ?>
                <textarea
                    name="description"
                    id="description"
                    cols=""
                    rows=""
                    class="tar_fixed"
                ><?php echo $objForm->stickyText('description'); ?></textarea>
            </td>
        </tr>

        <tr>
            <th><label for="price">Цена *</label></th>
            <td>
                <?php echo $objValid->validate('price'); ?>
                <input
                    type="text"
                    name="price"
                    id="price"
                    value="<?php echo $objForm->stickyText('price', '0.00'); ?>"
                    class="fld_price"
                />
            </td>
        </tr>

        <tr>
            <th><label for="weight">Вес *</label></th>
            <td>
                <?php echo $objValid->validate('weight'); ?>
                <input
                    type="text"
                    name="weight"
                    id="weight"
                    value="<?php echo $objForm->stickyText('weight', '0.00'); ?>"
                    class="fld_price"
                />
            </td>
        </tr>

        <tr>
            <th><label for="qty">Кол-во *</label></th>
            <td>
                <?php echo $objValid->validate('qty'); ?>
                <input
                    type="text"
                    name="qty"
                    id="qty"
                    value="<?php echo $objForm->stickyText('qty', $product['qty']); ?>"
                    class="fld_price"
                />
            </td>
        </tr>

        <tr>
            <th><label for="identity">Идентификатор URL *</label></th>
            <td>
                <?php echo $objValid->validate('identity'); ?>
                <?php echo $objValid->validate('duplicate_identity'); ?>
                <input
                    type="text"
                    name="identity"
                    id="identity"
                    value="<?php echo $objForm->stickyText('identity'); ?>"
                    class="fld"
                />
            </td>
        </tr>

        <tr>
            <th><label for="meta_title">Мета заголовок *</label></th>
            <td>
                <?php echo $objValid->validate('meta_title'); ?>
                <input
                    type="text"
                    name="meta_title"
                    id="meta_title"
                    value="<?php echo $objForm->stickyText('meta_title'); ?>"
                    class="fld"
                />
            </td>
        </tr>

        <tr>
            <th><label for="meta_description">Мета описание *</label></th>
            <td>
                <?php echo $objValid->validate('meta_description'); ?>
                <textarea
                    name="meta_description"
                    id="meta_description"
                    cols=""
                    rows=""
                    class="tar_fixed"
                ><?php echo $objForm->stickyText('meta_description'); ?></textarea>
            </td>
        </tr>

        <tr>
            <th><label for="meta_keywords">Мета ключевые слова *</label></th>
            <td>
                <?php echo $objValid->validate('meta_keywords'); ?>
                <textarea
                    name="meta_keywords"
                    id="meta_keywords"
                    cols=""
                    rows=""
                    class="tar_fixed"
                ><?php echo $objForm->stickyText('meta_keywords'); ?></textarea>
            </td>
        </tr>

        <tr>
            <th><label for="image">Изображение</label></th>
            <td>
                <?php echo $objValid->validate('image'); ?>
                <input type="file" name="image" id="image" size="30" />
            </td>
        </tr>

        <tr>
            <th>&nbsp;</th>
            <td>
                <label for="btn" class="sbm sbm_blue fl_l">
                    <input type="submit" id="btn" class="btn" value="Добавить" />
                </label>
                <div class="sbm sbm_blue fl_r">
                    <a
                        class="btn"
                        href="/admin/products">В список товаров
                    </a>
                </div>
            </td>
        </tr>

    </table>
</form>

<?php require '_footer.php'; ?>



