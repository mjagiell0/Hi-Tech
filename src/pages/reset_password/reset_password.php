<?php
    $token = $_GET['token'];
    if (empty($token)) {
        echo "Brak tokenu resetowania hasła.";
        exit;
    }

    
?>
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
        <h2 class="password-reset-title">Utwórz nowe hasło</h2>
        <form class="password-reset-form" id="resetForm" action="../../classes/actions/ChangePasswordAction.php" method="POST" novalidate>
            <label class="password-reset-label" for="password">Podaj hasło:</label>
            <input class="password-reset-input" type="password" id="password" name="password" required placeholder="np. jan.kowalski@example.com">
            <label class="password-reset-label" for="confirm_password">Powtórz hasło:</label>
            <input class="password-reset-input" type="password" id="confirm_password" name="confirm_password" required placeholder="np. jan.kowalski@example.com">
            <div class="password-reset-error" id="emailError"></div>
            <button class="password-reset-button" type="submit">Zmień hasło</button>
        </form>
    </div>
    
</body>

</html>
