<?php

/**
 * Страница Catalog item
 * Отображает страницу конкретного товара
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     https://darket-shop.ru/catalog/catalog-item.html
 */

$id = $this->objUrl->get('item');

if (!empty($id)) {
    $objCatalog = new Catalog();
    $product = $objCatalog->getProductByIdentity($id);

    $objCatalog = new Catalog();
    $currency = Catalog::$currency;

    if (!empty($product)) {
        // Перезапись мета тегов
        $this->meta_title = $product['meta_title'];
        $this->meta_description = $product['meta_description'];
        $this->meta_keywords = $product['meta_keywords'];

        $category = $objCatalog->getCategory($product['category']);
        include '_header.php';
        ?>
        <h1>Каталог > <?php echo $category['name']; ?></h1>
        <div class="catalog_wrapper">
            <div class="catalog_wrapper_left">
            <?php
            // Есть ли изображение у товара
            $image = !empty($product['image']) ?
                $product['image'] : 'unavailable.png';

            $width = Helper::getImgSize(CATALOG_PATH . DS . $image, 0);
            $width = $width > 300 ? 300 : $width;
            ?>
            <img
                src="<?php echo $objCatalog->path . $image; ?>"
                alt=""
                width="<?php echo $width; ?>"
            />
            </div>
            <div class="catalog_wrapper_right" style="margin-left: 50px">
                <h1>
                    <a href="<?php echo $link; ?>">
                        <?php echo Helper::encodeHtml($product['name']); ?>
                    </a>
                </h1>
                <h4>
                    <a href="<?php echo $link; ?>">
                        <strong>
                            <?php
                            echo Catalog::$currency;
                            echo number_format($product['price']);
                            ?>
                        </strong>
                    </a>
                </h4>
                <p>
                    <?php
                    echo Helper::encodeHtml($product['description']);
                    ?>
                </p>
                <p>
                    <?php
                    if ($product['qty'] > 0) {
                        echo Cart::activeButton($product['id']);
                    } else {
                        echo Cart::inactiveButton();
                    }
                    ?>
                </p>
            </div>
        </div>




        <?php
        include '_footer.php';
    } else {
        include 'error.php';
    }

} else {
    include 'error.php';
}