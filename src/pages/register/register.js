document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("registerForm");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        clearErrors();

        let isValid = true;

        const firstName = document.getElementById("first_name").value.trim();
        const lastName = document.getElementById("last_name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm_password").value;

        // Walidacja imienia
        if (firstName === "") {
            showError("firstNameError", "Podaj imię");
            isValid = false;
        } else if (!/^[A-Za-zĄĆĘŁŃÓŚŹŻąćęłńóśźż]+$/.test(firstName)) {
            showError("firstNameError", "Imię może zawierać tylko litery");
            isValid = false;
        }

        // Walidacja nazwiska
        if (lastName === "") {
            showError("lastNameError", "Podaj nazwisko");
            isValid = false;
        } else if (!/^[A-Za-zĄĆĘŁŃÓŚŹŻąćęłńóśźż]+$/.test(lastName)) {
            showError("lastNameError", "Nazwisko może zawierać tylko litery");
            isValid = false;
        }

        // Walidacja emaila
        if (!validateEmail(email)) {
            showError("emailError", "Podaj poprawny adres email");
            isValid = false;
        }

        // Walidacja hasła
        if (password.length < 6) {
            showError("passwordError", "Hasło musi mieć co najmniej 6 znaków");
            isValid = false;
        }

        // Sprawdzenie zgodności haseł
        if (password !== confirmPassword) {
            showError("confirmPasswordError", "Hasła nie są zgodne");
            isValid = false;
        }

        // 🔍 Sprawdzenie, czy email już istnieje
        if (isValid) {
            try {
                const response = await fetch("../../api/check_email.php?email=" + encodeURIComponent(email));
                const data = await response.json();

                if (data.exists) {
                    showError("emailError", "Konto z tym adresem już istnieje");
                    isValid = false;
                }
            } catch (error) {
                console.error("Błąd podczas sprawdzania emaila:", error);
                showError("emailError", "Nie udało się sprawdzić adresu email");
                isValid = false;
            }
        }

        if (isValid) {
            form.submit();
        }
    });

    function showError(id, message) {
        const errorElement = document.getElementById(id);
        errorElement.textContent = message;
    }

    function clearErrors() {
        const errors = document.querySelectorAll(".error-message");
        errors.forEach(el => el.textContent = "");
    }

    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }


});

function showPassword() {
    const passwordField = document.getElementById("password");
    const confirmPasswordField = document.getElementById("confirm_password");

    const isVisible = passwordField.type === "text";

    passwordField.type = isVisible ? "password" : "text";
    confirmPasswordField.type = isVisible ? "password" : "text";
}