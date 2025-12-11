<?php
include_once '../../classes/utils/ConstUtils.php';
session_start();
if (isset($_SESSION[ConstUtils::SESSION_USER])) {
    header('location: ../main/main.php');
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaloguj się</title>
    <link rel="stylesheet" href="../../styles/main.css">
    <script type="module" src="login.js" defer></script>

</head>

<body>
    <div class="container">
        <div class="content-container">
            <div class="logo-container">
                <a href="../../pages/main/main.php">
                    <img class="logo" src="../../assets/logo.png" alt="Logo">
                </a>
            </div>
            <div class="login-form-container">
                <div class="login-title">
                    <h1>Zaloguj się</h1>
                </div>
                <?php
                $status = $_GET['status'] ?? null;
                if ($status === 'error') {
                    echo '<div class="error-banner">Nieprawidłowe hasło. Spróbuj ponownie.</div>';
                } elseif ($status === 'error_no_user') {
                    echo '<div class="error-banner">Użytkownik o podanym adresie email nie istnieje.</div>';
                }
                ?>
                <form class="login-form" id="loginForm" action="../../classes/actions/LoginPostAction.php" method="post" novalidate>
                    <div class="input-container">
                        <input class="email-input" type="email" id="email" name="email" placeholder="Email">
                        <div class="error-message" id="email-error"></div>
                    </div>
                    <div class="input-container">
                        <input class="password-input" type="password" id="password" name="password" placeholder="Hasło">
                        <div class="error-message" id="password-error"></div>
                        <div style="width: 80%; padding-top: 10px;">
                            <input type="checkbox" onclick="showPassword()"> Pokaż hasło
                        </div>
                    </div>
                    <a href="../forgot_password/forgot_password.php">Zapomniałaś/eś hasła?</a>
                    <button class="login-button" type="submit">Zaloguj</button>
                </form>
                <div class="login-footer">
                    <p>Nie masz konta? <a href="../register/register.php">Zarejestruj się</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
