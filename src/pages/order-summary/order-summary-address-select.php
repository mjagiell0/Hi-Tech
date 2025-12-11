<?php
include_once 'order-summary-address-select-setup.php';
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
            <div class="step active">
                Adres dostawy
            </div>
            <div class="step">
                Sposób płatności
            </div>
            <div class="step">
                Podsumowanie
            </div>
        </div>
        <div class="delivery-address-section">
            <?php if (!empty($userAddresses)): ?>
                <h2>
                    Adres dostawy
                </h2>
                <form
                        method="post"
                        id="orderAddressSelectForm"
                        action="../../classes/actions/SelectDeliveryAddressAction.php"
                        class="address-form"
                        novalidate
                >
                    <label for="<?=ConstUtils::FIELD_LABEL_ADDRESS_ID?>">
                        Wybierz zapisany adres:
                    </label>
                    <select
                            name="<?=ConstUtils::FIELD_LABEL_ADDRESS_ID?>"
                            id="<?=ConstUtils::FIELD_LABEL_ADDRESS_ID?>"
                            required
                    >
                        <option value="">
                            -- wybierz adres --
                        </option>
                        <?php foreach ($userAddresses as $address): ?>
                            <option value="<?= $address->getId() ?>">
                                <?= $address->getStreet() . ' ' . $address->getHouseNumber() . ', ' ?>
                                <?= $address->getPostalCode() . ' ' . $address->getCity() ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="error-message" id="<?=ConstUtils::FIELD_LABEL_ADDRESS_ID?>-error"></div>
                    <button type="submit" class="confirm-address-button">
                        Dalej
                    </button>
                </form>
            <?php endif; ?>

            <form
                    method="post"
                    id="orderNewAddressForm"
                    action="../../classes/actions/AddDeliveryAddressAction.php"
                    class="address-form"
                    novalidate
            >
                <?php if (!empty($userAddresses)): ?>
                    <h3>
                        Lub podaj nowy adres dostawy:
                    </h3>
                <?php else: ?>
                    <h3>
                        Podaj nowy adres dostawy:
                    </h3>
                <?php endif; ?>
                <label for="<?=ConstUtils::FIELD_LABEL_STREET?>">
                    Ulica:
                </label>
                <input
                        type="text"
                        name="<?=ConstUtils::FIELD_LABEL_STREET?>"
                        id="<?=ConstUtils::FIELD_LABEL_STREET?>"
                        required
                >
                <div class="error-message" id="<?=ConstUtils::FIELD_LABEL_STREET?>-error"></div>

                <label for="<?=ConstUtils::FIELD_LABEL_HOUSE_NUMBER?>">
                    Numer domu/mieszkania:
                </label>
                <input
                        type="text"
                        name="<?=ConstUtils::FIELD_LABEL_HOUSE_NUMBER?>"
                        id="<?=ConstUtils::FIELD_LABEL_HOUSE_NUMBER?>"
                        required
                >
                <div class="error-message" id="<?=ConstUtils::FIELD_LABEL_HOUSE_NUMBER?>-error"></div>

                <label for="<?=ConstUtils::FIELD_LABEL_CITY?>">
                    Miasto:
                </label>
                <input
                        type="text"
                        name="<?=ConstUtils::FIELD_LABEL_CITY?>"
                        id="<?=ConstUtils::FIELD_LABEL_CITY?>"
                        required
                >
                <div class="error-message" id="<?=ConstUtils::FIELD_LABEL_CITY?>-error"></div>

                <label for="<?=ConstUtils::FIELD_LABEL_POSTAL_CODE?>">
                    Kod pocztowy:
                </label>
                <input
                        type="text"
                        name="<?=ConstUtils::FIELD_LABEL_POSTAL_CODE?>"
                        id="<?=ConstUtils::FIELD_LABEL_POSTAL_CODE?>"
                        required
                >
                <div class="error-message" id="<?=ConstUtils::FIELD_LABEL_POSTAL_CODE?>-error"></div>

                <div>
                    <input
                            type="checkbox"
                            name="save"
                            id="save"
                            value="false"
                    >
                    <label for="save">
                        Zapisz adres
                    </label>
                </div>

                <button type="submit" class="confirm-address-button">
                    Dalej
                </button>
            </form>
            <button class="order-return-button" id="order-return-button">
                Powrót
            </button>
        </div>
    </div>
</div>
</body>

</html>
