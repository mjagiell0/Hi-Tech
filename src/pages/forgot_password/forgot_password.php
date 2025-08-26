<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Odzyskiwanie hasła</title>
    <link rel="stylesheet" href="../../styles/main.css"></link>
    <script src="forgot_password.js" defer></script>
</head>

<body style="display: flex; height: 100vh; width: 100%; justify-content: center; align-items: center;">
    <div class="password-reset-container">
        <h2 class="password-reset-title">Odzyskaj hasło</h2>
        <form class="password-reset-form" id="resetForm" action="../../classes/actions/RecoverPasswordAction.php" method="POST" novalidate>
            <label class="password-reset-label" for="email">Podaj swój adres e-mail:</label>
            <input class="password-reset-input" type="email" id="email" name="email" required placeholder="np. jan.kowalski@example.com">
            <div class="password-reset-error" id="emailError"></div>
            <button class="password-reset-button" type="submit">Wyślij</button>
        </form>
        <div class="password-reset-info">
            Po wysłaniu otrzymasz wiadomość z instrukcjami dotyczącymi resetowania hasła.
        </div>
        <div class="password-reset-back">
            <a href="../login/login.php" class="password-reset-back-link"> < Powrót do logowania</a>
        </div>
    </div>
    
</body>

</html>
