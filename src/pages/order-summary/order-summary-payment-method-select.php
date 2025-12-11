<?php
include_once 'order-summary-payment-method-select-setup.php'
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
            <div class="step">
                Adres dostawy
            </div>
            <div class="step active">
                Sposób płatności
            </div>
            <div class="step">
                Podsumowanie
            </div>
        </div>
        <div class="delivery-address-section">
            <?php if (!empty($paymentCards)): ?>
                <h2>
                    Wybierz zapisaną kartę płatniczą
                </h2>
                <form
                        method="post"
                        id="cardForm"
                        action="../../classes/actions/SelectPaymentCardAction.php"
                        class="address-form"
                        novalidate
                >
                    <label for="<?= ConstUtils::FIELD_LABEL_CARD_ID ?>">
                        Zapisane karty:
                    </label>
                    <select
                            name="<?= ConstUtils::FIELD_LABEL_CARD_ID ?>"
                            id="<?= ConstUtils::FIELD_LABEL_CARD_ID ?>"
                            required
                    >
                        <option value="">
                            -- wybierz kartę --
                        </option>
                        <?php foreach ($paymentCards as $card): ?>
                            <option value="<?= $card->getId() ?>">
                                <?= $card->getCardholderName() . ' - ****' . $card->getCardNumberLast4() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="error-message" id="<?= ConstUtils::FIELD_LABEL_CARD_ID ?>-error"></div>
                    <button type="submit" class="confirm-address-button">
                        Dalej
                    </button>
                </form>
            <?php endif; ?>
            <form
                    id="createCardForm"
                    method="post"
                    action="../../classes/actions/AddPaymentCardAction.php"
                    class="address-form"
                    novalidate
            >
                <?php if (!empty($paymentCards)): ?>
                    <h3>
                        Lub dodaj nową kartę płatniczą:
                    </h3>
                <?php else: ?>
                    <h3>
                        Dodaj kartę płatniczą:
                    </h3>
                <?php endif; ?>
                <div class="card-inline-fields">
                    <div class="field-group">
                        <label for="<?= ConstUtils::FIELD_LABEL_CARDHOLDER_FIRSTNAME ?>">
                            Imię:
                        </label>
                        <input
                                type="text"
                                name="<?= ConstUtils::FIELD_LABEL_CARDHOLDER_FIRSTNAME ?>"
                                id="<?= ConstUtils::FIELD_LABEL_CARDHOLDER_FIRSTNAME ?>"
                                required
                        >
                        <div class="error-message" id="<?= ConstUtils::FIELD_LABEL_CARDHOLDER_FIRSTNAME ?>-error"></div>
                    </div>
                    <div class="field-group">
                        <label for="<?= ConstUtils::FIELD_LABEL_CARDHOLDER_LASTNAME ?>">
                            Nazwisko:
                        </label>
                        <input
                                type="text"
                                name="<?= ConstUtils::FIELD_LABEL_CARDHOLDER_LASTNAME ?>"
                                id="<?= ConstUtils::FIELD_LABEL_CARDHOLDER_LASTNAME ?>"
                                required
                        >
                        <div class="error-message" id="<?= ConstUtils::FIELD_LABEL_CARDHOLDER_LASTNAME ?>-error"></div>
                    </div>
                </div>

                <label for="<?= ConstUtils::FIELD_LABEL_CARD_NUMBER ?>">
                    Numer karty:
                </label>
                <input
                        type="text"
                        id="<?= ConstUtils::FIELD_LABEL_CARD_NUMBER ?>"
                        name="<?= ConstUtils::FIELD_LABEL_CARD_NUMBER ?>"
                        inputmode="numeric"
                        pattern="[0-9\s]{16}"
                        autocomplete="cc-number"
                        maxlength="16"
                />
                <div class="error-message" id="<?= ConstUtils::FIELD_LABEL_CARD_NUMBER ?>-error"></div>
                <div class="card-inline-fields">
                    <div class="field-group">
                        <label for="<?= ConstUtils::FIELD_LABEL_EXPIRATION_DATE ?>">
                            Data ważności:
                        </label>
                        <input
                                type="month"
                                name="<?= ConstUtils::FIELD_LABEL_EXPIRATION_DATE ?>"
                                id="<?= ConstUtils::FIELD_LABEL_EXPIRATION_DATE ?>"
                                min="<?= date('Y-m') ?>"
                                required
                        >
                        <div class="error-message" id="<?= ConstUtils::FIELD_LABEL_EXPIRATION_DATE ?>-error"></div>
                    </div>
                    <div class="field-group">
                        <label for="<?= ConstUtils::FIELD_LABEL_CVV ?>">
                            Kod CVV:
                        </label>
                        <input
                                type="text"
                                name="<?= ConstUtils::FIELD_LABEL_CVV ?>"
                                id="<?= ConstUtils::FIELD_LABEL_CVV ?>"
                                maxlength="3"
                                inputmode="numeric"
                                pattern="\d{3}"
                                required
                        >
                        <div class="error-message" id="<?= ConstUtils::FIELD_LABEL_CVV ?>-error"></div>
                    </div>
                </div>

                <div>
                    <input
                            type="checkbox"
                            name="save"
                            id="save"
                            value="true"
                    >
                    <label for="save">
                        Zapisz kartę
                    </label>
                </div>

                <button type="submit" class="confirm-address-button">
                    Dalej
                </button>
            </form>
            <button
                    class="order-return-button"
                    id="order-return-button"
                    onclick="window.history.back()"
            >
                Powrót
            </button>
        </div>
    </div>
</div>
</body>
</html>