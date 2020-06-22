<?php

/**
 * Страница сводки покупаемых товаров
 *
 * PHP version 7.3.11
 *
 * @category PHP
 * @package  Htdocs
 * @author   Алексей <alexeyheather@gmail.com>
 * @license  https://github.com/Infvmous/htdocs/blob/master/README.md BSD Licence
 * @link     http://darket-shop.ru/summary.html
 */

Login::restrictFront($this->objUrl);

$objUser = new User();
$user = $objUser->getUser(Session::getSession(Login::$login_front));

if (!empty($user)) {
    $objCart = new Cart($user);

    $objShipping = new Shipping($objCart);
    $shipping = $objShipping->getShippingOptions($user);

    // Чистка всех предыдущих сессий доставки
    $objCart->clearShipping();

    // Получить тип доставки по умолчанию для юзера по стране
    $shippingDefault = $objShipping->getDefault($user);

    if (!empty($shipping) && !empty($shippingDefault)) {
        $shippingSelected = $objShipping->getShipping($user, $shippingDefault['id']);

        if ($objCart->addShipping($shippingSelected)) {

            $token1 = mt_rand();
            $token2 = Login::stringToHash($token1);
            Session::setSession('token2', $token2);

            $out = array();
            $session = Session::getSession('cart');

            if (!empty($session)) {
                $objCatalog = new Catalog();
                foreach ($session as $key => $value) {
                    $out[$key] = $objCatalog->getProduct($key);
                }
            }

            include_once '_header.php'; ?>

            <h1>Оформление покупки</h1>

            <?php if (!empty($out)) { ?>

                <div id="cart_big">
                    <form action="" method="post" id="frm_cart">
                        <table cellpadding="0" cellspacing="0" class="tbl_repeat br_bd">
                            <tr>
                                <th>Товар</th>
                                <th class="ta_r">Кол-во</th>
                                <th class="ta_r col_15">Цена</th>
                            </tr>

                            <?php foreach ($out as $item) { ?>

                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td class="ta_r"><?php echo $session[$item['id']]['qty']; ?></td>
                                <td class="ta_r">
                                    <?php
                                        echo Catalog::$currency;
                                        echo number_format(
                                            $objCart->itemTotal(
                                                $item['price'],
                                                $session[$item['id']]['qty']
                                            ), 2
                                        );
                                    ?>
                                </td>
                            </tr>

                            <?php } ?>

                            <tr class="rowHighlight">
                                <td colspan="2" class="br_td">
                                    <i>Всего товаров:</i>
                                </td>
                                <td class="ta_r br_td">
                                    <i>
                                    <?php
                                    echo Catalog::$currency;
                                    echo number_format($objCart->sub_total, 2);
                                    ?>
                                    </i>
                                </td>
                            </tr>
                            <tr>
                                <th colspan="3">Доставка</th>
                            </tr>

                            <?php foreach ($shipping as $srow) { ?>

                            <tr>
                                <td colspan="2">
                                    <label for="shipping_<?php echo $srow['id']; ?>">
                                        <input
                                            type="radio"
                                            name="shipping"
                                            id="shipping_<?php echo $srow['id']; ?>"
                                            value="<?php echo $srow['id']; ?>"
                                            class="shippingRadio"
                                            <?php echo $srow['id'] == $shippingDefault['id'] ? ' checked="checked"' : null; ?>
                                        /><?php echo $srow['name']; ?>
                                    </label>
                                </td>
                                <td class="ta_r">
                                    <?php
                                    echo Catalog::$currency;
                                    echo number_format($srow['cost'], 2);
                                    ?>
                                </td>
                            </tr>

                            <?php } ?>

                            <tbody class="rowHighlight">
                                <tr>
                                    <td colspan="2" class="br_td">Всего:</td>
                                    <td class="ta_r br_td" id="cartSubTotal">
                                        <?php
                                            echo Catalog::$currency;
                                            echo number_format($objCart->final_sub_total, 2);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="br_td">
                                        НДС (<?php echo number_format($objCart->vat_rate, 2); ?>%)
                                    </td>
                                    <td class="ta_r br_td" id="cartVat">
                                        <?php
                                            echo Catalog::$currency;
                                            echo number_format($objCart->final_vat, 2);
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="br_td">
                                        <strong>Итого:</strong>
                                    </td>
                                    <td class="ta_r br_td">
                                        <strong id="cartTotal">
                                            <?php
                                                echo Catalog::$currency;
                                                echo number_format($objCart->final_total, 2);
                                            ?>
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="sbm sbm_blue fl_r paypal" id="<?php echo $token1; ?>">
                            <span class="btn">Оплатить</span>
                        </div>

                        <div class="sbm sbm_blue fl_l" id="<?php echo $token1; ?>">
                            <a href="<?php echo $this->objUrl->href('cart'); ?>" class="btn">Изменить заказ</a>
                        </div>

                    </form>
                    <div class="dev">&#160;</div>
                </div>

                <div class="dn">
                    <img src="/images/loadinfo.net.gif" alt="Proceeding to PayPal" />
                </div>

            <?php } else { ?>

                <p>Ваша корзина пуста.</p>

            <?php } ?>

            <?php
            include_once '_footer.php';
        } else {
            include_once 'error-shipping.php';
        }
    } else {
        include_once 'error-shipping.php';
    }
} else {
    Helper::redirect($this->objUrl->href('error'));
}
?>

