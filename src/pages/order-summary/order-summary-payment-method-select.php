<?php
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/handlers/DatabaseHandler.php';
include_once '../../classes/enums/CrudEnum.php';
include_once '../../classes/abstracts/Entity.php';
include_once '../../classes/services/LoginService.php';
include_once '../../classes/entities/User.php';
include_once '../../classes/entities/Address.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();

session_start();

$user = $_SESSION[ConstUtils::SESSION_USER] ?? null;
if (is_null($user)) {
    header('location: ../login/login.php');
}

$userAddresses = LoginService::getUserAddresses($user->getId());
?>

<!DOCTYPE html>
<html lang="pl">

<?php include_once '../../components/order-header.php'?>
<body>
<div class="container">
    <div class="content-container">
        <div class="logo-container">
            <img class="logo no-padding-top" src="../../assets/logo.png" alt="Logo">
        </div>
        <div class="order-progress">
            <div class="step">Adres dostawy</div>
            <div class="step active">Sposób płatności</div>
            <div class="step">Podsumowanie</div>
        </div>
    </div>
</div>
</body>

</html>
