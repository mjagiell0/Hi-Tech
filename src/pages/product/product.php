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
include_once "../../classes/entities/ProductDetail.php";
include_once "../../classes/services/ProductService.php";
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();

session_start();
$user = $_SESSION[ConstUtils::SESSION_USER] ?? '';
$product = ProductService::getProduct($productId);
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
<?php include_once "../../components/header.php" ?>

<main style="padding: 20px; text-align: center; margin: 0 auto">
    <?=$product->getProductName()?>;
</main>
<?php include_once "../../components/footer.php" ?>
<?php include_once "../../components/toast.php"; ?>
</body>
</html>
