<?php
/**
 * Edit
 * Страница редактирования категории
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  DARKET
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop/panel/categories/action/edit.html
 */
$id = $this->objUrl->get('id');

if (!empty($id)) {

    $objCatalog = new Catalog();

    $category = $objCatalog->getCategory($id);

    if (!empty($category)) {
        $objForm = new Form();
        $objValid = new Validation($objForm);
        if ($objForm->isPost('name')) {
            $objValid->expected = array(
                'name',
                'identity',
                'meta_title',
                'meta_description',
                'meta_keywords'
            );
            $objValid->required = array(
                'name',
                'identity',
                'meta_title',
                'meta_description',
                'meta_keywords'
            );

            $name = $objForm->getPost('name');
            $identity = Helper::cleanString($objForm->getPost('identity'));
            if ($objCatalog->duplicateCategory($name, $id)) {
                $objValid->addToErrors('name_duplicate');
            }
            if ($objCatalog->isDuplicateCategory($identity, $id)) {
                $objValid->addToErrors('duplicate_identity');
            }

            if ($objValid->isValid()) {
                $objValid->post['identity'] = $identity;
                if ($objCatalog->updateCategory($objValid->post, $id)) {
                    // Если изменение прошло успешно
                    Helper::redirect(
                        $this->objUrl->getCurrent(
                            array('action', 'id')
                        ) . '/action/edited'
                    );
                } else {
                    // Если произошла ошибка изменения категории
                    Helper::redirect(
                        $this->objUrl->getCurrent(
                            array('action', 'id')
                        ) . '/action/edited-failed'
                    );
                }
            }
        }
        include '_header.php'; ?>

        <h1>Редактирование категории &laquo;<?php echo $category['name']; ?>&raquo;</h1>
        <form action="" method="post">
            <table cellpadding="0" cellspacing="0" class="tbl_insert">
                <tr>
                <th><label for="name">Название *</label></th>
                    <td>
                        <?php
                            echo $objValid->validate('name');
                            echo $objValid->validate('name_duplicate');
                        ?>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            placeholder="Наименование категории"
                            value="<?php echo $objForm->stickyText('name', $category['name']); ?>"
                            class="fld"
                        />
                    </td>
                </tr>

                <tr>
                    <th><label for="identity">Идентификатор URL *</label></th>
                    <td>
                        <?php
                            echo $objValid->validate('identity');
                            echo $objValid->validate('duplicate_identity');
                        ?>
                        <input
                            type="text"
                            name="identity"
                            id="identity"
                            value="<?php echo $objForm->stickyText('identity', $category['identity']); ?>"
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
                            value="<?php echo $objForm->stickyText('meta_title', $category['meta_title']); ?>"
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
                        ><?php echo $objForm->stickyText('meta_description', $category['meta_description']); ?></textarea>
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
                        ><?php echo $objForm->stickyText('meta_keywords', $category['meta_keywords']); ?></textarea>
                    </td>
                </tr>

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

        <?php
        include '_footer.php';
    }
} else {
    Helper::redirect($this->objUrl->href('error'));
}




