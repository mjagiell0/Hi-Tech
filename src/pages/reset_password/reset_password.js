
document.getElementById("resetForm").addEventListener("submit", function(event) {
    let valid = true;
    // Pobranie elementów
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirm_password");
    const passwordError = document.getElementById("passwordError");
    const confirmPasswordError = document.getElementById("confirmPasswordError");

    const passwordRegex = /^(?=.*\d).{6,}$/;
    // Reset błędów
    password.classList.remove("error-input");
    confirmPassword.classList.remove("error-input");
    passwordError.textContent = "";
    confirmPasswordError.textContent = "";

    // Walidacja hasła
    if (!password.value.trim()) {
        passwordError.textContent = "Podaj hasło";
        password.classList.add("error-input");
        valid = false;
    } else if (!passwordRegex.test(password.value)) {
        passwordError.textContent = "Hasło musi mieć co najmniej 6 znaków w tym jedną cyfrę.";
        password.classList.add("error-input");
        valid = false;
    } else if (password.value !== confirmPassword.value) {
        confirmPasswordError.textContent = "Hasła nie są identyczne.";
        confirmPassword.classList.add("error-input");
    }
    if (!valid) {
        event.preventDefault();
    }
});
