<!DOCTYPE html>

<?php

include_once "../../classes/utils/ConstUtils.php";
include_once "../../classes/enums/CrudEnum.php";
include_once "../../classes/handlers/DatabaseHandler.php";
include_once "../../classes/abstracts/Entity.php";
include_once "../../classes/entities/Section.php";
include_once "../../classes/entities/User.php";
include_once "../../classes/entities/CartProduct.php";
include_once "../../classes/entities/Category.php";
include_once "../../classes/entities/Product.php";
include_once "../../classes/services/ProductService.php";
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();

session_start();
$user = $_SESSION[ConstUtils::SESSION_USER] ?? '';
$sessionCart = $_SESSION[ConstUtils::SESSION_USER_CART] ?? [];
$cart = $user !== '' ? ProductService::getCartProducts() : $sessionCart;

$showCartMergeModal = false;

if ($user !== '' && !empty($sessionCart)) {
    $showCartMergeModal = true;
}
?>
<html lang="pl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sklep z elektroniką</title>
    <link rel="stylesheet" href="../../styles/main.css"/>
    <script src="../main/main.js" defer></script>
    <script src="cart.js" defer></script>
</head>
<body>
<?php
include_once "../../components/header.php";
$itemsPerPage = ConstUtils::RECORD_PER_PAGE;
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$totalItems = count($cart);
$totalPages = ceil($totalItems / $itemsPerPage);

// Wytnij tylko produkty dla bieżącej strony
$offset = ($currentPage - 1) * $itemsPerPage;
$cartPage = array_slice($cart, $offset, $itemsPerPage);

?>

<main class="cart-main">
    <h2 class="cart-title">Twój koszyk</h2>
    <?php if (!empty($cart)):
        $total = 0;
        ?>
        <div class="cart-layout">
            <div>
                <div class="cart-list">
                    <?php foreach ($cartPage as $item):
                        $price = $item->getPriceWithDiscountValue();
                        $total += $price * $item->getQuantity();
                        ?>
                        <div class="cart-item">
                            <img src="../../assets/images/<?= $item->getImageName() ?>" alt="Produkt"
                                 class="cart-item-image">
                            <div class="cart-item-details">
                                <a href="../product/product.php?id=<?= $item->getId() ?>">
                                    <h3><?= $item->getProductName() ?></h3></a>
                                <p class="item-producent">Producent: <?= $item->getProducent() ?></p>
                                <label>
                                    Ilość:
                                    <input type="number"
                                           class="cart-quantity-input"
                                           min="1"
                                           max="<?= $item->getStockQuantity() ?>"
                                           value="<?= $item->getQuantity() ?>"
                                           data-id="<?= $item->getId() ?>"
                                           data-price="<?= $item->getPrice() ?>"
                                           data-discount="<?= $item->getDiscount() ?>">

                                </label>

                                <?php if ($item->getDiscount() > 0): ?>
                                    <p class="price-old"><?= $item->getPrice() ?> zł</p>
                                    <p class="price-new" data-price-container>( -<?= $item->getDiscount() * 100 ?>%)
                                        → <?= $item->getPriceWithDiscountAndQuantity() ?> zł</p>
                                <?php else: ?>
                                    <p class="price"><?= $item->getPriceWithQuantity() ?> zł</p>
                                <?php endif; ?>
                            </div>
                            <button class="remove-from-cart-button" data-id="<?= $item->getId() ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                     width="24px"
                                     fill="#FFFFFF">
                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                </svg>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?= $i ?>"
                               class="pagination-button<?= $i === $currentPage ? ' active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>

            <aside class="cart-summary">
                <h4>Łączna wartość do zapłaty:</h4>
                <h3> <?= number_format($total, 2, ',', '') ?> zł</h3>
                <button class="checkout-button">Przejdź do płatności</button>
            </aside>
        </div>
    <?php else: ?>
        <p>Twój koszyk jest pusty.</p>
    <?php endif; ?>
</main>
<?php include_once "../../components/footer.php" ?>
<?php if ($showCartMergeModal):
    ?>
    <div id="cart-merge-modal" class="modal-overlay">
        <div class="modal-content">
            <h3>Przenieść produkty z poprzedniego koszyka?</h3>
            <p>Masz produkty dodane przed zalogowaniem. Czy chcesz je przenieść do koszyka przypisanego do konta?</p>
            <form method="post" action="../../classes/actions/MergeCartAction.php">
                <button type="submit" name="merge" value="yes">Tak, przenieś</button>
                <button type="submit" name="merge" value="no">Nie, usuń</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php include_once "../../components/toast.php"; ?>
</body>
</html>
