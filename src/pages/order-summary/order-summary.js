import {validateInput, multiValidateInput} from '../../modules/formValidation.js';

document.getElementById("order-return-button")?.addEventListener('click', () => {
    window.location.href = '../cart/cart.php';
});

const cardholderFirstnameInput = document.getElementById("cardholder_firstname");
const cardholderLastnameInput = document.getElementById("cardholder_lastname");
const cardNumberInput = document.getElementById("card_number");
const cvvCodeInput = document.getElementById("cvv");

cardholderFirstnameInput?.addEventListener("beforeinput", (e) => {
    if (e.data && /^\d+$/.test(e.data)) {
        e.preventDefault();
    }
});

cardholderLastnameInput?.addEventListener("beforeinput", (e) => {
    if (e.data && /^\d+$/.test(e.data)) {
        e.preventDefault();
    }
});

cardNumberInput?.addEventListener("beforeinput", (e) => {
    if (e.data && !/^\d+$/.test(e.data)) {
        e.preventDefault();
    }
});

cvvCodeInput?.addEventListener("beforeinput", (e) => {
    if (e.data && !/^\d+$/.test(e.data)) {
        e.preventDefault();
    }
});


document.getElementById("orderAddressSelectForm")?.addEventListener("submit", function (e) {

    let valid = validateInput(
        "address_id",
        input => input.value !== '',
        "Wybierz jeden z zapisanych adresów."
    );

    if (!valid) {
        e.preventDefault();
    }
});

document.getElementById("orderNewAddressForm")?.addEventListener("submit", function (e) {
    let valid = true;

    valid &= validateInput(
        "street",
        input => input.value.trim() !== "",
        "Podaj nazwę ulicy."
    );
    valid &= validateInput(
        "house_number",
        input => input.value.trim() !== "",
        "Podaj numer domu."
    );
    valid &= validateInput(
        "city",
        input => input.value.trim() !== "",
        "Podaj nazwę miasta."
    );
    valid &= validateInput(
        "postal_code",
        input => /^\d{2}-\d{3}$/.test(input.value.trim()),
        "Podaj kod pocztowy w formacie 00-000."
    );

    if (!valid) {
        e.preventDefault();
    }
});


document.getElementById("cardForm")?.addEventListener("submit", function (e) {
    let valid = validateInput(
        "card_id",
        input => input.value.trim() !== '',
        "Wybierz jedna z zapisanych kart."
    );

    if (!valid) {
        e.preventDefault();
    }
});

document.getElementById("createCardForm")?.addEventListener("submit", function (e) {
    let valid = true;

    valid &= multiValidateInput(
        "cardholder_firstname",
        {
            isValidate: input => input.value.trim() !== '',
            errorMessage: "Wprowadź imię"
        },
        {
            isValidate: input => !/\d/.test(input.value),
            errorMessage: "Wprowadź prawidłowe imię"
        }
    );

    valid &= multiValidateInput(
        "cardholder_lastname",
        {
            isValidate: input => input.value.trim() !== '',
            errorMessage: "Wprowadź nazwisko"
        },
        {
            isValidate: input => !/\d/.test(input.value),
            errorMessage: "Wprowadź prawidłowe nazwisko"
        }
    );

    valid &= validateInput(
        "card_number",
        input => input.value.length === 16,
        "Podaj pełny numer karty"
    );

    valid &= validateInput(
        "expiration_date",
        input => input.value !== '',
        "Podaj datę ważności"
    );

    valid &= validateInput(
        "cvv",
        input => input.value.length === 3,
        "Podaj pełny kod CVV"
    )

    if (!valid) {
        e.preventDefault();
    }
});