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
include_once "../../classes/entities/ProductFortune.php";
include_once "../../classes/entities/Opinion.php";
include_once "../../classes/entities/User.php";
include_once "../../classes/entities/Specification.php";
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
$specification = ProductService::getProductSpecification($productId);
$userDiscount = ProductService::getUserDiscount($user);

if (!is_null($userDiscount)) {
    if ($product->getDiscount() < $userDiscount->getDiscount()
        && $product->getId() === $userDiscount->getProductId()) {
        $product->withDiscount($userDiscount->getDiscount());
    }
}
?>
<html lang="pl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sklep z elektroniką</title>
    <link rel="stylesheet" href="../../styles/main.css"/>
    <script src="../main/main.js" defer></script>
    <script type="module" src="product.js" defer></script>
</head>
<body>

<?php
include_once "../../components/header.php" ?>

<main style="padding: 20px; text-align: center; margin: 0 auto">
    <div class="actual-path">
        <a class="actual-path-link"
           href="../section/section.php?id=<?= $product->getSectionId() ?>"><?= $product->getSectionName()
            ?></a>
        /
        <a class="actual-path-link"
           href="../category/category.php?id=<?= $product->getCategoryId() ?>"><?= $product->getCategoryName() ?></a>
        /
        <a class="actual-path-link"
           href="product.php?id=<?= $productId ?>"><?= $product->getProductName() ?></a>
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
                <span class="opinion-summary-stars"><?= str_repeat('★', $product->getAvgOpinion()) . str_repeat('☆', 5 - $product->getAvgOpinion()) ?></span>
                <span class="rating-count">(<?= $product->getOpinionCount() ?> opinii)</span>
            </div>

            <div class="product-price-box">
                <?php if ($product->getDiscount() > 0): ?>
                    <p class="product-price--line-through"><?= $product->getPrice() ?> zł</p>
                    <?php if (!is_null($userDiscount)) :
                        if ($product->getId() === $userDiscount->getProductId()):?>
                        <div class="daily-luck-label">Daily luck!</div>
                        <?php endif;
                    endif;
                    ?>
                    <p class="product-price--discount bigger-font"><?= $product->getPriceWithDiscount() ?> zł <span
                                class="discount-label">(-<?= $product->getDiscount() * 100 ?>%)</span></p>
                <?php else: ?>
                    <p class="product-price"><?= $product->getPrice() ?> zł</p>
                <?php endif; ?>
            </div>
            <div class="product-description">
                <h3>Opis</h3>
                <?= $product->getDescription() ?>
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
                    <input type="hidden" name="<?= ConstUtils::FIELD_LABEL_PRODUCT_ID ?>"
                           value="<?= $product->getId() ?>">
                    <input type="hidden" name="<?= ConstUtils::FIELD_LABEL_QUANTITY ?>" value="1">
                    <input type="hidden" name="<?= ConstUtils::FIELD_LABEL_NAME ?>"
                           value="<?= $product->getProductName() ?>">
                    <input type="hidden" name="<?= ConstUtils::FIELD_LABEL_STOCK_QUANTITY ?>"
                           value="<?= $product->getStockQuantity() ?>">
                    <input type="hidden" name="<?= ConstUtils::FIELD_LABEL_PRICE ?>"
                           value="<?= $product->getPriceValue() ?>">
                    <input type="hidden" name="<?= ConstUtils::FIELD_LABEL_IMAGE_NAME ?>"
                           value="<?= $product->getImageName() ?>">
                    <input type="hidden" name="<?= ConstUtils::FIELD_LABEL_PRODUCENT ?>"
                           value="<?= $product->getProducent() ?>">
                    <input type="hidden" name="<?= ConstUtils::FIELD_LABEL_DISCOUNT ?>"
                           value="<?= $product->getDiscount() ?>">
                    <div>
                        <label for="quantity">Ilość:</label>
                        <input type="number" name="quantity" id="quantity" class="quantity-input" min="1"
                               max="<?= $product->getStockQuantity() ?>" value="1">
                    </div>
                    <button id='add-to-cart-button' class="add-to-cart-button product-button">
                        <svg xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960" width="30px"
                             fill="#FFFFFF">
                            <path d="M456.67-608.67v-122H334v-66.66h122.67v-122h66.66v122h122v66.66h-122v122h-66.66ZM286.53-80q-30.86 0-52.7-21.97Q212-123.95 212-154.81q0-30.86 21.98-52.69 21.97-21.83 52.83-21.83t52.69 21.97q21.83 21.98 21.83 52.84 0 30.85-21.97 52.69Q317.38-80 286.53-80Zm402.66 0q-30.86 0-52.69-21.97-21.83-21.98-21.83-52.84 0-30.86 21.97-52.69 21.98-21.83 52.84-21.83 30.85 0 52.69 21.97Q764-185.38 764-154.52q0 30.85-21.97 52.69Q720.05-80 689.19-80ZM54.67-813.33V-880h121l170 362.67H630.8l158.87-280h75L698-489.33q-11 19.33-28.87 30.66-17.88 11.34-39.13 11.34H328.67l-52 96H764v66.66H282.67q-40.11 0-61.06-33-20.94-33-2.28-67L280-496 133.33-813.33H54.67Z"/>
                        </svg>
                        <div>Dodaj do koszyka</div>
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </section>
    <?php if (!empty($specification)): ?>
        <section class="product-specifications">
            <h2>Specyfikacja techniczna</h2>
            <table class="spec-table">
                <tbody>
                <?php foreach ($specification as $spec): ?>
                    <tr>
                        <th><?= htmlspecialchars($spec->getKey()) ?></th>
                        <td><?= htmlspecialchars($spec->getValue()) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>

    <section class="product-opinions">
        <h2>Opinie o produkcie</h2>

        <div class="opinion-form-wrapper">
            <?php
            $userOpinion = '';
            if ($user !== '') :

                foreach ($productOpinions as $index => $opinion) {
                    if ($opinion->getOwnerId() == $user->getId()) {
                        $userOpinion = $opinion;
                        unset($productOpinions[$index]);
                        break;
                    }
                }
                if ($userOpinion === ''):?>
                    <h3>Dodaj swoją opinię</h3>
                    <form method="post" action="../../classes/actions/AddOpinionAction.php" class="opinion-form">
                        <input type="hidden" name="product_id" value="<?= $productId ?>">
                        <label for="stars">Ocena:</label>
                        <div class="star-rating" id="starRating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="opinion-star" data-value="<?= $i ?>">★</span>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="stars" id="starsInput" value="" required>

                        <label for="comment">Treść opinii:</label>
                        <textarea name="comment" id="comment" rows="4" maxlength="<?= ConstUtils::COMMENT_MAX_LENGTH ?>"
                                  minlength="10" required></textarea>

                        <button type="submit" class="submit-opinion-button">Dodaj opinię</button>
                    </form>
                <?php else: ?>
                    <div class="opinions-list">
                        <h4>Twoja opinia</h4>
                        <div class="opinion-card">
                            <div class="opinion-header">
                                <span class="opinion-date"><?= $userOpinion->getFormatedCreatedAt() ?></span>
                                <span class="opinion-author"><?= $userOpinion->getAuthor() ?></span>
                                <span class="opinion-summary-stars"><?= str_repeat('★', $userOpinion->getStars()) . str_repeat('☆', 5 - $userOpinion->getStars()) ?></span>
                            </div>
                            <p class="opinion-text"><?= $userOpinion->getComment() ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="login-prompt">
                    <p>
                        <a href="../login/login.php" class="login-link">Zaloguj się</a> lub
                        <a href="../register/register.php" class="login-link">załóż konto</a>, aby móc wystawić opinię
                        produktowi.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($productOpinions)): ?>
            <div class="opinions-list">
                <h4>Opinie innych użytkowników</h4>
                <?php foreach ($productOpinions as $opinion): ?>
                    <div class="opinion-card">
                        <div class="opinion-header">
                            <span class="opinion-date"><?= $opinion->getFormatedCreatedAt() ?></span>
                            <span class="opinion-author"><?= $opinion->getAuthor() ?></span>
                            <span class="opinion-summary-stars"><?= str_repeat('★', $opinion->getStars()) . str_repeat('☆', 5 - $opinion->getStars()) ?></span>
                        </div>
                        <p class="opinion-text"><?= $opinion->getComment() ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <?php if ($userOpinion !== ''): ?>
                <p class="no-opinions">Brak opinii innych użytkowników</p>
            <?php else: ?>
                <p class="no-opinions">Brak opinii dla tego produktu.</p>
            <?php endif; ?>
        <?php endif; ?>


    </section>
</main>
<?php include_once "../../components/footer.php" ?>
<?php include_once "../../components/toast.php"; ?>
</body>
</html>
