<?php

/**
 * Catalog
 * Страница каталога
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/catalog.html
 */

$cat = $this->objUrl->get('category');

if (empty($cat)) {
    include 'error.php';
} else {
    $objCatalog = new Catalog();
    $category = $objCatalog->getCategoryByIdentity($cat);

    if (empty($category)) {
        include 'error.php';
    } else {
        // Перезапись мета тегов для категорий
        $this->meta_title = $category['meta_title'];
        $this->meta_title = $category['meta_description'];
        $this->meta_title = $category['meta_keywords'];

        // Если identity категории == all categories,
        // отобразить все товары вне зависимости от категории
        if ($cat == 'all') {
            $rows = $objCatalog->getAllProducts();
        } else {
            $rows = $objCatalog->getProducts($category['id']);
        }

        // Инициализация пагинации
        $objPaging = new Paging($this->objUrl, $rows, 5); // Отобразить 5 товаров на странице
        $rows = $objPaging->getRecords();

        include_once '_header.php';
        ?>

        <h1><?php echo $category['name']; ?></h1>

        <?php
        if (!empty($rows)) {
            foreach ($rows as $row) {
                ?>
                <div class="catalog_wrapper">
                    <div class="catalog_wrapper_left">
                        <?php
                            $image = !empty($row['image']) ? $row['image'] :
                                'unavailable.png';

                            $width = Helper::getImgSize(CATALOG_PATH . DS . $image, 0);
                            $width = $width > 230 ? 230 : $width;

                            $link = $this->objUrl->href(
                                'catalog-item', array(
                                    'category',
                                    $category['identity'],
                                    'item',
                                    $row['identity']
                                )
                            );
                        ?>
                        <a href="<?php $link; ?>">
                            <img
                                src="<?php echo $objCatalog->path . $image; ?>"
                                alt="<?php echo Helper::encodeHtml($row['name'], 1); ?>"
                                width="<?php echo $width; ?>"
                            />
                        </a>
                    </div>

                    <div class="catalog_wrapper_right">
                        <h2>
                            <a href="<?php echo $link; ?>">
                                <?php echo Helper::encodeHtml($row['name'], 1); ?>
                            </a>
                        </h2>
                        <h1>
                            <a href="<?php echo $link; ?>">
                                <?php echo Catalog::$currency;
                                echo number_format($row['price'], 2); ?>
                            </a>
                        </h1>
                        <p>
                            <?php
                            echo
                                Helper::encodeHtml(
                                    Helper::shortenString($row['description'])
                                );
                            ?>
                        </p>
                        <p>
                            <?php
                            if ($row['qty'] > 0) {
                                echo Cart::activeButton($row['id']);
                            } else {
                                echo Cart::inactiveButton();
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <?php
            }
            echo $objPaging->getPaging();
        } else {
            ?>
            <p>В этой категории нет товаров.</p>
            <?php
        }
        include_once '_footer.php';
    }
}
