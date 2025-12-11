<!DOCTYPE html>

<?php

include_once "../../classes/utils/ConstUtils.php";
include_once "../../classes/enums/CrudEnum.php";
include_once "../../classes/enums/RareRateEnum.php";
include_once "../../classes/handlers/DatabaseHandler.php";
include_once "../../classes/abstracts/Entity.php";
include_once "../../classes/entities/Section.php";
include_once "../../classes/entities/User.php";
include_once "../../classes/entities/UserSpinReward.php";
include_once "../../classes/entities/ProductDiscount.php";
include_once "../../classes/entities/Opinion.php";
include_once "../../classes/entities/ProductRewardable.php";
include_once "../../classes/services/ProductService.php";
include_once "../../classes/services/LoginService.php";

require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();

session_start();

$discountedProducts = ProductService::getProductsWithDiscounts();
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
        </div>
        <h2 class="slider-title">
            Odkryj nasze promocje
        </h2>
        <div class="slider">
            <div class="slides">
                <?php foreach ($discountedProducts as $product): ?>
                    <div class="slide" onclick="location.href='../product/product.php?id=<?= $product->getId() ?>'">
                        <img src="../../assets/images/<?= $product->getImageName() ?>" alt="<?= $product->getName() ?>">
                        <div class="slide-title">
                            <?= $product->getProducent() . ': ' . $product->getName() ?>
                            <p><?= $product->getPrice() ?></p>
                            <h3><?= $product->getPriceAfterDiscount() ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php
            if (count($discountedProducts) > 1) {
                ?>
                <div class="slider-buttons">
                    <button class="slider-button" id="prev">&#10094;</button>
                    <button class="slider-button" id="next">&#10095;</button>
                </div>
                <?php
            }
            ?>

            <div class="dots">
                <?php foreach ($discountedProducts as $index => $product): ?>
                    <span class="dot <?= $index === 0 ? 'active' : '' ?>"></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div style="padding-top: 200px">
        <h2 class="slider-title">
            Naszą opinię tworzą klienci
        </h2>
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
    <div class="case-opening">
        <h2>
            Daily luck!
        </h2>
        <p style="padding-bottom: 20px; color: #8b8b8b">
            Każdy dzisiaj wygrać może...
        </p>
        <label class="tooltip-label">
            Zasady
            <span class="tooltip-text">
            • Możesz losować raz dziennie<br>
            • Nagroda jest przypisywana do konta<br>
            • Rzadkość wpływa na wartość produktu<br>
            • Nie można wymieniać nagród
            </span>
        </label>

        <?php if ($user != ''):
            $user = $_SESSION[ConstUtils::SESSION_USER];
            if (!LoginService::doesUserHaveReward($user->getId())):
                $rewardableProducts = ProductService::getRewardableProducts();
                shuffle($rewardableProducts);
                ?>
                <div class="carousel" id="carousel-container">
                    <div class="win-line">
                    </div>
                    <div class="carousel-track">
                        <?php foreach ($rewardableProducts as $product):
                            $rarityClass = match ($product->getRareRate()) {
                                RareRateEnum::COMMON => 'rarity-common',
                                RareRateEnum::RARE => 'rarity-rare',
                                RareRateEnum::SPECIAL => 'rarity-special',
                            };
                            ?>
                            <div class="carousel-item <?= $rarityClass ?>" data-product-id="<?= $product->getId() ?>">
                                <div class="rarity-glow">
                                    <img src="../../assets/images/<?= $product->getImageName() ?>"
                                         alt="<?= $product->getName() ?>">
                                    <p>
                                        <?= $product->getName() ?>
                                    </p>
                                    <div class="item-price-percent-container">
                                        <p class="item-price__before-discount">
                                            <?= $product->getPrice() ?>
                                        </p>
                                        <p class="item-price__percent">
                                            <?= '-' . $product->getPercent() ?>
                                        </p>
                                    </div>
                                    <p class="item-price__after-discount">
                                        <?= $product->getPriceAfterDiscount() ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button id="start-case">
                    Start
                </button>
                <div id="result" class="result-box">
                </div>
                <div id="winner-card" style="display: none;">
                </div>
            <?php else: ?>
                <h2 style="font-size: 2.0rem">
                    Twoja dzisiejsza wygrana:
                </h2>
                <?php
                $userReward = LoginService::getUserReward($user->getId());
                $reward = $userReward->getProductReward();
                $rarityClass = match ($reward->getRareRate()) {
                    RareRateEnum::COMMON => 'rarity-common',
                    RareRateEnum::RARE => 'rarity-rare',
                    RareRateEnum::SPECIAL => 'rarity-special',
                };
                ?>
                <div id="winner-card" class="visible">
                    <div class="winner-card-content <?= $rarityClass ?>">
                        <div class="rarity-glow">
                            <img src="../../assets/images/<?= $reward->getImageName() ?>"
                                 alt="<?= $reward->getName() ?>">
                            <p><?= $reward->getName() ?></p>
                            <div class="item-price-percent-container">
                                <p class="item-price__before-discount">
                                    <?= $reward->getPrice() ?>
                                </p>
                                <p class="item-price__percent">
                                    <?= '-' . $reward->getPercent() ?>
                                </p>
                            </div>
                            <p class="item-price__after-discount">
                                <?= $reward->getPriceAfterDiscount() ?>
                            </p>
                        </div>
                    </div>
                </div>
                <h2 style="font-size: 1.3rem; padding-top: 20px; font-weight: normal">
                    Kolejne losowanie: <?=$userReward->nextSpinDate()?>
                </h2>
            <?php endif;?>
        <?php else: ?>
            <p class="access-info">
                Uczestnictwo w Daily luck jest udzielane tylko zalogowanym użytkownikom
            </p>
            <div style="display: flex; flex-direction: row; align-items: center; justify-content: center; text-align: center">
                <a href="../login/login.php" class="login-link">
                    Zaloguj się
                </a>
                <p style="padding: 0 10px">
                    lub
                </p>
                <a href="../register/register.php" class="login-link">
                    utwórz konto
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php include_once "../../components/footer.php" ?>
<iframe name="hidden-frame" style="display: none;"></iframe>

</body>
</html>
