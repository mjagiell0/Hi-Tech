<?php
include_once "../../classes/utils/ConstUtils.php";

$status = $_GET[ConstUtils::STATUS] ?? '';

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarejestruj się</title>
    <link rel="stylesheet" href="../../styles/main.css">
    <script src="register.js" defer></script>
</head>

<body style="display: flex; height: 100vh; width: 100%; justify-content: center;">
<div class="container">
    <div class="content-container">
        <div class="logo-container">
            <img class="logo without-padding" src="../../assets/logo.png" alt="Logo">
        </div>
        <?php if ($status !== ''): ?>
            <div class="password-reset-notification">
                <?php if ($status === ConstUtils::STATUS_SUCCESS): ?>
                    Rejestracja przebiegła pomyślnie. Zaloguj się na podane dane.<br>
                    <a href="../login/login.php" class="password-reset-back-link">
                        < Powrót do logowania</a>
                <?php elseif ($status === ConstUtils::STATUS_ERROR_EMAIL_IN_USE): ?>
                    Użytkownik o podanym adresie email już istnieje.<br>
                    <a href="./register.php" class="password-reset-back-link">
                        < Spróbuj ponownie z innymi danymi.</a>
                <?php elseif ($status === ConstUtils::STATUS_ERROR): ?>
                    Wystąpił błąd podczas próby rejestracji.<br>
                    <a href="./register.php" class="password-reset-back-link">
                        < Spróbuj ponownie.</a>
                <?php endif; ?>

            </div>
        <?php endif; ?>
        <?php if ($status === ''): ?>

            <div class="login-form-container">
                <div class="login-title">
                    <h1>Rejestracja</h1>
                </div>


                <form class="login-form" id="registerForm" action="../../classes/actions/RegisterPostAction.php" method="post"
                      novalidate>
                    <div class="input-container">
                        <input class="email-input" type="text" id="first_name"
                               name=<?= ConstUtils::FIELD_LABEL_FIRSTNAME ?> placeholder="Imię">
                        <div class="error-message" id="firstNameError"></div>
                    </div>
                    <div class="input-container">
                        <input class="email-input" type="text" id="last_name"
                               name=<?= ConstUtils::FIELD_LABEL_LASTNAME ?> placeholder="Nazwisko">
                        <div class="error-message" id="lastNameError"></div>
                    </div>
                    <div class="input-container">
                        <input class="email-input" type="email" id="email"
                               name=<?= ConstUtils::FIELD_LABEL_EMAIL ?> placeholder="Email">
                        <div class="error-message" id="emailError"></div>
                    </div>
                    <div class="input-container">
                        <input class="password-input" type="password" id="password"
                               name=<?= ConstUtils::FIELD_LABEL_PASSWORD ?> placeholder="Hasło">
                        <div class="password-tip">Min. 6 znaków, w tym jedna cyfra.</div>
                        <div class="error-message" id="passwordError"></div>
                    </div>
                    <div class="input-container">
                        <input class="password-input" type="password" id="confirm_password" name="confirm_password"
                               placeholder="Powtórz hasło">
                        <div class="error-message" id="confirmPasswordError"></div>
                        <div style="width: 80%; padding-top: 10px;">
                            <input type="checkbox" onclick="showPassword()"> Pokaż hasło
                        </div>
                    </div>
                    <button class="login-button" type="submit">Zarejestruj się</button>
                </form>
                <div class="login-footer">
                    <p>Masz już konto? <a href="../login/login.php">Zaloguj się</a></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>

</html>