<!DOCTYPE html>

<?php

include_once "../../classes/utils/ConstUtils.php";
$sectionId = $_GET[ConstUtils::FIELD_LABEL_ID] ?? '';

if ($sectionId != '') {
    if (intval($sectionId) === 0) {
        header('Location: ../main/main.php');
    }
} else {

}

include_once "../../classes/enums/CrudEnum.php";
include_once "../../classes/handlers/DatabaseHandler.php";
include_once "../../classes/abstracts/Entity.php";
include_once "../../classes/entities/Section.php";
include_once "../../classes/entities/Category.php";
include_once "../../classes/services/ProductService.php";
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();

session_start();

$user = $_SESSION[ConstUtils::SESSION_USER] ?? '';
$categories = ProductService::getCategories($sectionId);
?>
<html lang="pl">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Sklep z elektroniką</title>
    <link rel="stylesheet" href="../../styles/main.css"/>
    <script src="section.js" defer></script>
</head>
<body>
<?php include_once "../../components/header.php" ?>

<main style="padding: 20px; text-align: center; margin: 0 auto">
    <?php if (empty($categories)): ?>
        <p>Brak dostępnych kategorii w tej sekcji.</p>
    <?php else:
        $sectionName = $categories[0]->getSectionName();
        ?>
        <div class="actual-path">
            <a class="actual-path-link" href="section.php?id=<?=$sectionId?>"><?=$sectionName?></a>/
        </div>
        <h2 class="section-title"><?= $sectionName ?></h2>
        <div class="category-grid">
            <?php foreach ($categories as $category):?>
                <a href="../products/products.php?category_id=<?= $category->getId() ?>" class="category-card">
                    <img src="../../assets/images/<?= $category->getImagePath() ?>" alt="<?= $category->getName() ?>">
                    <h3><?= $category->getName() ?></h3>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
<?php include_once "../../components/footer.php" ?>
</body>
</html>
