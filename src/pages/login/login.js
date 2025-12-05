import {validateInput} from "../../modules/formValidation.js";

function showPassword() {
    var passwordInput = document.getElementById("password");
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
}

document.getElementById("loginForm").addEventListener("submit", function (e) {
    let valid = true;

    valid &= validateInput(
        "email",
        input => input.value.trim() !== '',
        "Podaj adres e-mail"
    );
    valid &= validateInput(
        "email",
        input => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value),
        "Podaj poprawny adres e-mail."
    );
    valid &=    validateInput(
        "password",
        input => input.value.trim(),
        "Podaj hasło"
    );

    if (!valid) {
        e.preventDefault();
    }
});
