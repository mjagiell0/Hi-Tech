<!DOCTYPE html>

<?php

include_once "../../classes/utils/ConstUtils.php";
$categoryId = $_GET[ConstUtils::FIELD_LABEL_ID] ?? '';

if (intval($categoryId) === 0) {
    header('Location: ../main/main.php');
}

include_once "../../classes/enums/CrudEnum.php";
include_once "../../classes/handlers/DatabaseHandler.php";
include_once "../../classes/abstracts/Entity.php";
include_once "../../classes/entities/Section.php";
include_once "../../classes/entities/Category.php";
include_once "../../classes/entities/Product.php";
include_once "../../classes/services/ProductService.php";
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();

session_start();

$user = $_SESSION[ConstUtils::SESSION_USER] ?? '';
$page = isset($_GET['page']) && intval($_GET['page']) > 0 ? intval($_GET['page']) : 1;
$products = ProductService::getCategoryProducts($categoryId, $page);
?>
<html lang="pl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sklep z elektroniką</title>
    <link rel="stylesheet" href="../../styles/main.css"/>
    <script src="../main/main.js" defer></script>
    <script src="category.js" defer></script>
</head>
<body>
<?php include_once "../../components/header.php" ?>

<main style="padding: 20px; text-align: center; margin: 0 auto">
    <?php if (empty($products)): ?>
        <p>Brak dostępnych produktów w tej kategorii.</p>
    <?php else:
        $sectionName = $products[0]->getSectionName();
        $sectionId = $products[0]->getSectionId();
        $categoryName = $products[0]->getCategoryName();
        ?>
        <div class="actual-path">
            <a class="actual-path-link" href="../section/section.php?id=<?= $sectionId ?>"><?= $sectionName ?></a>
            /
            <a class="actual-path-link" href="../category/category.php?id=<?= $categoryId ?>"><?= $categoryName ?></a>
            /
        </div>
        <h2 class="section-title"><?= $categoryName ?></h2>
        <div class="product-container">
            <?php foreach ($products as $product): ?>
                <div class="product-card<?= $product->getDiscount() > 0 ? '--discount' : '' ?>"
                     data-href="../product/product.php?id=<?= $product->getId() ?>">
                    <div class="product-image">
                        <img src="../../assets/images/<?= $product->getImageName() ?>" alt="...">
                    </div>

                    <div class="product-info">
                        <h3><?= $product->getProductName() ?></h3>
                        <div class="product-rating">
                            (<?= $product->getOpinionCount() ?>)
                            <?php
                            $rating = round($product->getAvgOpinion());
                            echo '<p class="product-stars">' . str_repeat('★', $rating) . '</p>' . str_repeat('★', 5 - $rating);
                            ?>
                        </div>


                        <p class="product-producer">Producent: <?= $product->getProducent() ?></p>
                    </div>

                    <div class="product-price-box">
                        <p class="product-price<?= $product->getDiscount() > 0 ? '--line-through' : '' ?>">
                            <?= $product->getPrice() ?>
                        </p>
                        <?php if ($product->getDiscount() > 0.0): ?>
                            <p class="product-price--discount">(-<?= $product->getDiscount() * 100 ?>
                                %) <?= $product->getPriceWithDiscount() ?></p>
                        <?php endif; ?>
                        <div class="cart-controls">
                            <input type="number" class="quantity-input" min="1" max="<?= $product->getQuantity() ?>"
                                   value="1">
                            <button class="add-to-cart-button">
                                <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960"
                                     width="30px" fill="#FFFFFF">
                                    <path d="M456.67-608.67v-122H334v-66.66h122.67v-122h66.66v122h122v66.66h-122v122h-66.66ZM286.53-80q-30.86 0-52.7-21.97Q212-123.95 212-154.81q0-30.86 21.98-52.69 21.97-21.83 52.83-21.83t52.69 21.97q21.83 21.98 21.83 52.84 0 30.85-21.97 52.69Q317.38-80 286.53-80Zm402.66 0q-30.86 0-52.69-21.97-21.83-21.98-21.83-52.84 0-30.86 21.97-52.69 21.98-21.83 52.84-21.83 30.85 0 52.69 21.97Q764-185.38 764-154.52q0 30.85-21.97 52.69Q720.05-80 689.19-80ZM54.67-813.33V-880h121l170 362.67H630.8l158.87-280h75L698-489.33q-11 19.33-28.87 30.66-17.88 11.34-39.13 11.34H328.67l-52 96H764v66.66H282.67q-40.11 0-61.06-33-20.94-33-2.28-67L280-496 133.33-813.33H54.67Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <form class="add-to-cart-form" style="display: none;">
                        <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                        <input type="hidden" name="quantity" value="1">
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        $totalProducts = count($products);
        $totalPages = ceil($totalProducts / ConstUtils::RECORD_PER_PAGE);
        ?>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?id=<?= $categoryId ?>&page=<?= $page - 1 ?>" class="pagination-button">« Poprzednia</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?id=<?= $categoryId ?>&page=<?= $i ?>"
                   class="pagination-button <?= $i === $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?id=<?= $categoryId ?>&page=<?= $page + 1 ?>" class="pagination-button">Następna »</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>
<?php include_once "../../components/footer.php" ?>
</body>
</html>
