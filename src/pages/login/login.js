function showPassword() {
        var passwordInput = document.getElementById("password");
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    }

document.getElementById("loginForm").addEventListener("submit", function(e) {
    let valid = true;
    // Pobranie elementów
    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");
    // Reset błędów
    email.classList.remove("error-input");
    password.classList.remove("error-input");
    emailError.textContent = "";
    passwordError.textContent = "";
    // Walidacja email
    if (!email.value.trim()) {
        emailError.textContent = "Podaj adres e-mail";
        email.classList.add("error-input");
        valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        emailError.textContent = "Podaj poprawny adres e-mail.";
        email.classList.add("error-input");
        valid = false;
    }
    // Walidacja hasła
    if (!password.value.trim()) {
        passwordError.textContent = "Podaj hasło";
        password.classList.add("error-input");
        valid = false;
    }
    if (!valid) {
        e.preventDefault();
    }
});
