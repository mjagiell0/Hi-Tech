<?php
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/enums/CrudEnum.php';
include_once '../../classes/handlers/DatabaseHandler.php';
include_once '../../classes/abstracts/Entity.php';
include_once '../../classes/services/LoginService.php';
include_once '../../classes/services/ProductService.php';
include_once '../../classes/entities/User.php';
include_once '../../classes/entities/PaymentCard.php';
include_once '../../classes/entities/Address.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

session_start();

$status = $_GET[ConstUtils::STATUS];

$user = $_SESSION[ConstUtils::SESSION_USER] ?? null;
if (is_null($user)) {
    header('location: ../login/login.php');
}

$cart = ProductService::getCartProducts();
$cartSum = ProductService::sumCartProductsPrice($cart);
$cartSumFormated = number_format(
    $cartSum,
    2,
    ',',
    ''
);
$taxVatFromCartSum = number_format(
    $cartSum * ConstUtils::TAX_PERCENT,
    2,
    ',',
    ''
);
$address = $_SESSION[ConstUtils::ORDER_ADDRESS];
$paymentCard = $_SESSION[ConstUtils::ORDER_PAYMENT_CARD];
?>

<!DOCTYPE html>
<html lang="pl">
<?php include_once '../../components/order-header.php' ?>
<body>
<div class="container">
    <div class="content-container">
        <div class="logo-container">
            <img class="logo no-padding-top" src="../../assets/logo.png" alt="Logo">
        </div>
        <div class="order-progress">
            <div class="step">
                Adres dostawy
            </div>
            <div class="step">
                Sposób płatności
            </div>
            <div class="step active">
                Podsumowanie
            </div>
        </div>
        <div class="order-summary-container">
            <div class="order-summary__product-table">
                <table class="order-summary__products-table">
                    <tr>
                        <th>Produkt</th>
                        <th>Ilość</th>
                        <th>Kwota</th>
                    </tr>
                    <?php
                    foreach ($cart as $product) {
                        ?>
                        <tr>
                            <td><?=$product->getProductName()?></td>
                            <td><?=$product->getQuantity()?></td>
                            <td><?=$product->getPriceWithDiscountAndQuantity()?> zł</td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            <div class="order-summary__delivery-address">
                <p>Adres dostawy:</p>
                <div>
                    <strong>
                        <?= $address->toString() ?>
                    </strong>
                    <div class="order-summary__delivery-time">
                        czas dostawy 2-3 dni robocze
                    </div>
                </div>
            </div>
            <div class="order-summary__payment-card">
                Płatność za pomocą karty:
                <strong>
                    <?= $paymentCard->toString() ?>
                </strong>
            </div>
            <div class="order-summary__total-cost">
                <p> Łączna kwota zamówienia:</p>
                <div>
                    <strong>
                        <?= $cartSumFormated ?> zł
                    </strong>
                    <div class="order-summary__tax-cost">
                        w tym VAT:
                        <strong>
                            <?= $taxVatFromCartSum ?> zł
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        <form method="<?=ConstUtils::POST_METHOD?>" action="../../classes/actions/OrderComplete.php">
            <button type="submit" class="confirm-address-button">
                Potwierdź zamówienie
            </button>
        </form>
        <button
                class="order-return-button"
                id="order-return-button"
                onclick="window.history.back()"
        >
            Powrót
        </button>
    </div>
</div>
</body>
</html>