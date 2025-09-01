document.getElementById("resetForm").addEventListener("submit", function (e) {
        const emailInput = document.getElementById("email");
        const emailError = document.getElementById("emailError");

        // reset błędów
        emailError.textContent = "";
        emailInput.classList.remove("error-input");

        // prosty regex dla adresu e-mail
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailInput.value.trim()) {
            emailError.textContent = "Podaj adres e-mail.";
            emailInput.classList.add("error-input");
            e.preventDefault();
        } else if (!emailPattern.test(emailInput.value)) {
            emailError.textContent = "Podaj poprawny adres e-mail.";
            emailInput.classList.add("error-input");
            e.preventDefault();
        }
    });
