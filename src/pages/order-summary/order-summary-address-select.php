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
            <div class="step active">Adres dostawy</div>
            <div class="step">Sposób płatności</div>
            <div class="step">Podsumowanie</div>
        </div>
        <div class="delivery-address-section">
            <?php if (!empty($userAddresses)): ?>
                <h2>Adres dostawy</h2>
                <form method="post" id="orderAddressSelectForm" action="../../classes/actions/SelectDeliveryAddressAction.php" class="address-form" novalidate>
                    <label for="address-select">Wybierz zapisany adres:</label>
                    <select name="address_id" id="address-select" required>
                        <option value="">-- wybierz adres --</option>
                        <?php foreach ($userAddresses as $address): ?>
                            <option value="<?= $address->getId() ?>">
                                <?= $address->getStreet() . ' ' . $address->getHouseNumber() . ', ' ?>
                                <?= $address->getPostalCode() . ' ' . $address->getCity() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="error-message" id="address-error"></div>
                    <button type="submit" class="confirm-address-button">Dalej</button>
                </form>
            <?php endif; ?>

            <form method="post" id="orderNewAddressForm" action="../../classes/actions/AddDeliveryAddressAction.php" class="address-form" novalidate>
                <?php if (!empty($userAddresses)): ?>
                    <h3>Lub podaj nowy adres dostawy:</h3>
                <?php else: ?>
                    <h3>Podaj nowy adres dostawy:</h3>
                <?php endif; ?>
                <label for="street">Ulica:</label>
                <input type="text" name="street" id="street" required>
                <div class="error-message" id="street-error"></div>

                <label for="house_number">Numer domu/mieszkania:</label>
                <input type="text" name="house_number" id="house_number" required>
                <div class="error-message" id="house_number-error"></div>

                <label for="city">Miasto:</label>
                <input type="text" name="city" id="city" required>
                <div class="error-message" id="city-error"></div>

                <label for="postal">Kod pocztowy:</label>
                <input type="text" name="postal_code" id="postal" required>
                <div class="error-message" id="postal-error"></div>

                <div>
                    <input type="checkbox" name="save" id="save" value="false">
                    <label for="save">Zapisz adres</label>
                </div>

                <button type="submit" class="confirm-address-button">Dalej</button>
            </form>
            <button class="order-return-button" id="order-return-button">Powrót</button>
        </div>
    </div>
</div>
</body>

</html>
