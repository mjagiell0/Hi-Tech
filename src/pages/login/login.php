<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaloguj się</title>
    <link rel="stylesheet" href="../../styles/main.css">
    <script src="login.js" defer></script>
</head>

<body style="display: flex; height: 100vh; width: 100%; justify-content: center;">
    <div class="container">
        <div class="logo-container">
            <img class="logo" src="../../assets/logo.png" alt="Logo">
        </div>

        <div class="login-form-container">
            <div class="login-title">
                <h1>Zaloguj się</h1>
            </div>
            <form class="login-form" id="loginForm" action="../../classes/actions/LoginPostAction.php" method="post" novalidate>
                <div class="input-container">
                    <input class="email-input" type="email" id="email" name="email" placeholder="Email">
                    <div class="error-message" id="emailError"></div>
                </div>
                <div class="input-container">
                    <input class="password-input" type="password" id="password" name="password" placeholder="Hasło">
                    <div class="error-message" id="passwordError"></div>
                    <div style="width: 80%; padding-top: 10px;">
                        <input type="checkbox" onclick="showPassword()"> Pokaż hasło
                    </div>
                </div>
                <a href="../forgot_password/forgot_password.php">Zapomniałaś/eś hasła?</a>
                <button class="login-button" type="submit">Zaloguj</button>
            </form>
            <div class="login-footer">
                <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
            </div>
        </div>
    </div>
</body>

</html>
