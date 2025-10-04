<!DOCTYPE html>

<?php

include_once "../../classes/utils/ConstUtils.php";
$productId = $_GET[ConstUtils::FIELD_LABEL_ID] ?? '';

if (intval($productId) === 0) {
    header('Location: ../main/main.php');
}

include_once "../../classes/enums/CrudEnum.php";
include_once "../../classes/handlers/DatabaseHandler.php";
include_once "../../classes/abstracts/Entity.php";
include_once "../../classes/entities/Section.php";
include_once "../../classes/entities/Category.php";
include_once "../../classes/entities/Product.php";
include_once "../../classes/entities/Opinion.php";
include_once "../../classes/entities/ProductImage.php";
include_once "../../classes/entities/ProductDetail.php";
include_once "../../classes/services/ProductService.php";
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();

session_start();
$user = $_SESSION[ConstUtils::SESSION_USER] ?? '';

$product = ProductService::getProduct($productId);
$productImages = ProductService::getProductImages($productId);
$productOpinions = ProductService::getProductOpinions($productId);

?>
<html lang="pl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sklep z elektroniką</title>
    <link rel="stylesheet" href="../../styles/main.css"/>
    <script src="../main/main.js" defer></script>
    <script src="product.js" defer></script>
</head>
<body>

<?php ;
include_once "../../components/header.php" ?>

<main style="padding: 20px; text-align: center; margin: 0 auto">
    <div class="actual-path">
        <a class="actual-path-link"
           href="../section/section.php?id=<?= $product->getSectionId() ?>"><?= $product->getSectionName()
            ?></a>
        /
        <a class="actual-path-link" href="../category/category.php"><?= $product->getCategoryName() ?></a>
        /
        <a class="actual-path-link"
           href="../category/category.php?id=<?= $productId ?>"><?= $product->getProductName() ?></a>
    </div>
    <section class="product-offer">
        <div class="product-gallery">
            <div class="main-image">
                <img id="mainProductImage" src="../../assets/images/<?= $productImages[0]->getImagePath() ?>"
                     alt="Zdjęcie produktu">
            </div>
            <div class="thumbnail-row">
                <?php foreach ($productImages as $index => $image): ?>
                    <img class="thumbnail<?= $index === 0 ? ' active' : '' ?>"
                         src="../../assets/images/<?= $image->getImagePath() ?>"
                         alt="Miniatura"
                         data-image="../../assets/images/<?= $image->getImagePath() ?>">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="product-details">
            <h1><?= $product->getProductName() ?></h1>
            <p class="product-producer"></p>

            <div class="product-rating">
                <span class="opinion-stars"><?= str_repeat('★', $product->getAvgOpinion()) . str_repeat('☆', 5 - $product->getAvgOpinion()) ?></span>
                <span class="rating-count">(<?= $product->getOpinionCount() ?> opinii)</span>
            </div>

            <div class="product-price-box">
                <?php if ($product->getDiscount() > 0): ?>
                    <p class="product-price--line-through"><?= $product->getPrice() ?> zł</p>
                    <p class="product-price--discount"><?= $product->getPriceWithDiscount() ?> zł <span
                                class="discount-label">(-<?= $product->getDiscount() * 100 ?>%)</span></p>
                <?php else: ?>
                    <p class="product-price"><?= $product->getPrice() ?> zł</p>
                <?php endif; ?>
            </div>
            <div class="product-description">
                <h3>Opis</h3>
                <?= $product->getDescription()?>
            </div>
            <?php if ($product->getStockQuantity() < ConstUtils::MIN_QUANTITY_WARNING): ?>
                <div class="product-stock">
                    <?= !$product->isArchived() ?
                        "<span class='low-quantity-warning'>Zostało już tylko {$product->getStockQuantity()} sztuk!</span>" :
                        "<span class='out-of-stock'>Brak w magazynie</span>" ?>
                </div>
            <?php endif; ?>

            <?php if ($product->getStockQuantity() > 0): ?>
                <form class="add-to-cart-form" method="post" action="../../classes/actions/ProductToCartAction.php">
                    <input type="hidden" name="product_id" value="<?= $productId ?>">
                    <input type="hidden" name="price" value="<?= $product->getPriceValue() ?>">
                    <input type="hidden" name="discount" value="<?= $product->getDiscount() ?>">
                    <input type="hidden" name="stock_quantity" value="<?= $product->getStockQuantity() ?>">
                    <div>
                        <label for="quantity">Ilość:</label>
                        <input type="number" name="quantity" id="quantity" class="quantity-input" min="1"
                               max="<?= $product->getStockQuantity() ?>" value="1">
                    </div>
                    <button type="submit" class="add-to-cart-button product-button">
                        <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px" fill="#FFFFFF"><path d="M456.67-608.67v-122H334v-66.66h122.67v-122h66.66v122h122v66.66h-122v122h-66.66ZM286.53-80q-30.86 0-52.7-21.97Q212-123.95 212-154.81q0-30.86 21.98-52.69 21.97-21.83 52.83-21.83t52.69 21.97q21.83 21.98 21.83 52.84 0 30.85-21.97 52.69Q317.38-80 286.53-80Zm402.66 0q-30.86 0-52.69-21.97-21.83-21.98-21.83-52.84 0-30.86 21.97-52.69 21.98-21.83 52.84-21.83 30.85 0 52.69 21.97Q764-185.38 764-154.52q0 30.85-21.97 52.69Q720.05-80 689.19-80ZM54.67-813.33V-880h121l170 362.67H630.8l158.87-280h75L698-489.33q-11 19.33-28.87 30.66-17.88 11.34-39.13 11.34H328.67l-52 96H764v66.66H282.67q-40.11 0-61.06-33-20.94-33-2.28-67L280-496 133.33-813.33H54.67Z"/></svg>
                        <div>Dodaj do koszyka</div>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </section>

</main>
<?php include_once "../../components/footer.php" ?>
<?php include_once "../../components/toast.php"; ?>
</body>
</html>
