document.getElementById("order-return-button").addEventListener('click',() => {
    window.location.href = '../cart/cart.php';
});

document.getElementById("orderAddressSelectForm").addEventListener("submit", function(e) {
    let valid = true;

    const addressInput = document.getElementById("address-select");
    const addressError = document.getElementById("address-error")

    addressInput.classList.remove('error-input');
    addressError.textContent = '';

    if (addressInput.value === '') {
        valid = false;
        addressInput.classList.add('error-input');
        addressError.textContent = 'Wybierz jeden z zapisanych adresów';
    }

    if (!valid) {
        e.preventDefault();
    }
});

document.getElementById("orderNewAddressForm").addEventListener("submit", function(e) {
    let valid = true;

    const streetInput = document.getElementById('street');
    const streetError = document.getElementById('street-error');

    const houseNumberInput = document.getElementById('house_number');
    const houseNumberError = document.getElementById('house_number-error');

    const cityInput = document.getElementById('city');
    const cityError = document.getElementById('city-error');

    const postalCodeInput = document.getElementById('postal');
    const postalCodeError = document.getElementById('postal-error');

    // Reset błędów
    [streetInput, houseNumberInput, cityInput, postalCodeInput].forEach(input => input.classList.remove('error-input'));
    [streetError, houseNumberError, cityError, postalCodeError].forEach(error => error.textContent = '');

    // Walidacja ulicy
    if (streetInput.value.trim() === '') {
        streetInput.classList.add('error-input');
        streetError.textContent = 'Podaj nazwę ulicy';
        valid = false;
    }

    // Walidacja numeru domu
    if (houseNumberInput.value.trim() === '') {
        houseNumberInput.classList.add('error-input');
        houseNumberError.textContent = 'Podaj numer domu';
        valid = false;
    }

    // Walidacja miasta
    if (cityInput.value.trim() === '') {
        cityInput.classList.add('error-input');
        cityError.textContent = 'Podaj nazwę miasta';
        valid = false;
    }

    // Walidacja kodu pocztowego (np. 00-000)
    const postalRegex = /^\d{2}-\d{3}$/;
    if (!postalRegex.test(postalCodeInput.value.trim())) {
        postalCodeInput.classList.add('error-input');
        postalCodeError.textContent = 'Podaj kod pocztowy w formacie 00-000';
        valid = false;
    }

    if (!valid) {
        e.preventDefault();
    }
});