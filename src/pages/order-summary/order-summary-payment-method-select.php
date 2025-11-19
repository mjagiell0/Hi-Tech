<?php
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/abstracts/Entity.php';
include_once '../../classes/entities/User.php';
include_once '../../classes/handlers/DatabaseHandler.php';
include_once '../../classes/entities/PaymentCard.php';
include_once '../../classes/enums/CrudEnum.php';
include_once '../../classes/services/LoginService.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

session_start();

$user = $_SESSION[ConstUtils::SESSION_USER] ?? null;
if (is_null($user)) {
    header('location: ../login/login.php');
}

$paymentCards = LoginService::getUserPaymentCards($user->getId());
?>

<!DOCTYPE html>
<html lang="pl">
<?php include_once '../../components/order-header.php' ?>
<script type="module" src="../../modules/formValidation.js"></script>
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
        <div class="delivery-address-section">
            <?php if (!empty($paymentCards)): ?>
                <h2>Wybierz zapisaną kartę płatniczą</h2>
                <form method="post" id="cardForm" action="../../classes/actions/SelectPaymentCardAction.php" class="address-form" novalidate>
                    <label for="card">Zapisane karty:</label>
                    <select name="card_id" id="card" required>
                        <option value="">-- wybierz kartę --</option>
                        <?php foreach ($paymentCards as $card): ?>
                            <option value="<?= $card->getId() ?>">
                                <?= $card->getCardholderName() . ' - ****' . $card->getCardNumberLast4() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="error-message" id="card-error"></div>
                    <button type="submit" class="confirm-address-button">Dalej</button>
                </form>
            <?php endif; ?>

            <form id="createCardForm" method="post" action="../../classes/actions/AddPaymentCardAction.php"
                  class="address-form" novalidate>
                <?php if (!empty($paymentCards)): ?>
                    <h3>Lub dodaj nową kartę płatniczą:</h3>
                <?php else: ?>
                    <h3>Dodaj kartę płatniczą:</h3>
                <?php endif; ?>

                <div class="card-inline-fields">
                    <div class="field-group">
                        <label for="cardholder_firstname">Imię:</label>
                        <input type="text" name="<?= ConstUtils::FIELD_LABEL_CARDHOLDER_FIRSTNAME ?>"
                               id="cardholder_firstname" required>
                        <div class="error-message" id="cardholder_firstname-error"></div>
                    </div>
                    <div class="field-group">
                        <label for="cardholder_lastname">Nazwisko:</label>
                        <input type="text" name="<?= ConstUtils::FIELD_LABEL_CARDHOLDER_LASTNAME ?>"
                               id="cardholder_lastname" required>
                        <div class="error-message" id="cardholder_lastname-error"></div>
                    </div>
                </div>

                <label for="card_number">Numer karty:</label>
                <input
                        type="text"
                        id="card_number"
                        inputmode="numeric"
                        pattern="[0-9\s]{16}"
                        autocomplete="cc-number"
                        maxlength="16"
                />
                <div class="error-message" id="card_number-error"></div>


                <div class="card-inline-fields">
                    <div class="field-group">
                        <label for="<?= ConstUtils::FIELD_LABEL_EXPIRATION_DATE ?>">Data ważności:</label>
                        <input
                                type="month"
                                name="<?= ConstUtils::FIELD_LABEL_EXPIRATION_DATE ?>"
                                id="expiration-date"
                                min="<?= date('Y-m') ?>"
                                required
                        >
                        <div class="error-message" id="expiration-date-error"></div>
                    </div>
                    <div class="field-group">
                        <label for="<?= ConstUtils::FIELD_LABEL_CVV ?>">Kod CVV:</label>
                        <input
                                type="text"
                                name="<?= ConstUtils::FIELD_LABEL_CVV ?>"
                                id="card_cvv"
                                maxlength="3"
                                inputmode="numeric"
                                pattern="\d{3}"
                                required
                        >
                        <div class="error-message" id="card_cvv-error"></div>
                    </div>
                </div>

                <div>
                    <input type="checkbox" name="save" id="save" value="true">
                    <label for="save">Zapisz kartę</label>
                </div>

                <button type="submit" class="confirm-address-button">Dalej</button>
            </form>
            <button class="order-return-button" id="order-return-button" onclick="window.history.back()">Powrót</button>
        </div>
    </div>
</div>
</body>
</html>