<!DOCTYPE html>

<?php
include_once "../../classes/utils/ConstUtils.php";
include_once "../../classes/enums/CrudEnum.php";
include_once "../../classes/handlers/DatabaseHandler.php";
include_once "../../classes/abstracts/Entity.php";
include_once "../../classes/entities/Section.php";
include_once "../../classes/entities/ProductDiscount.php";
include_once "../../classes/entities/Opinion.php";
include_once "../../classes/services/ProductService.php";

require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();

$products = ProductService::getProductsWithDiscounts();
$sections = ProductService::getSections();
$opinions = ProductService::getBestOpinions();

$user = $_SESSION[ConstUtils::SESSION_USER] ?? '';
?>
<html lang="pl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sklep z elektroniką</title>
    <link rel="stylesheet" href="../../styles/main.css"/>
    <script src="main.js" defer></script>
</head>
<body>
<?php include_once "../../components/header.php" ?>

<main style="padding: 20px; text-align: center;">
    <div>
        <h2 class="slider-title">Odkryj nasze promocje</h2>
        <div class="slider">
            <div class="slides">
                <?php foreach ($products as $product): ?>
                    <div class="slide" onclick="location.href='../section/section.php?id=<?= $product->getId() ?>'">
                        <img src="../../assets/images/<?= $product->getImageName() ?>" alt="<?= $product->getName() ?>">
                        <div class="slide-title">
                            <?= $product->getProducent().': '.$product->getName()?>
                            <p><?= $product->getPrice() ?></p>
                            <h3><?= $product->getPriceAfterDiscount() ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php
            if (count($products) > 1) {
                ?>
                <div class="slider-buttons">
                    <button class="slider-button" id="prev">&#10094;</button>
                    <button class="slider-button" id="next">&#10095;</button>
                </div>
                <?php
            }
            ?>

            <div class="dots">
                <?php foreach ($products as $index => $product): ?>
                    <span class="dot <?= $index === 0 ? 'active' : '' ?>"></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div style="padding-top: 200px">
        <h2 class="slider-title">Naszą opinię tworzą klienci</h2>
        <div class="slider opinion-slider">
            <div class="slider-track">
                <?php foreach ($opinions as $opinion): ?>
                    <div class="opinion-slide"
                         onclick="location.href='../product/product.php?id=<?= $opinion->getProductId() ?>'">
                        <div class="opinion-card">
                            <div class="stars">
                                <?php for ($i = 0; $i < $opinion->getStars(); $i++): ?>
                                    ★
                                <?php endfor; ?>
                            </div>
                            <p class="comment">"<?= $opinion->getComment() ?>"</p>
                            <p class="author">
                                – <?= $opinion->getOwnerFirstname() . ' ' . $opinion->getOwnerLastname() ?></p>
                            <p class="product-info"><?= $opinion->getProduct() . ' | ' . $opinion->getProducent() ?></p>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!--TODO: Pochwałka opiniami-->
    <!--TODO: Stopka-->

</main>
</body>
</html>
