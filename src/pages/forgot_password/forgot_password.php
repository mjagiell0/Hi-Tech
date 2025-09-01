<!DOCTYPE html>
<html lang="pl">

<?php
include_once '../../classes/utils/ConstUtils.php';
$status = $_GET[ConstUtils::STATUS] ?? '';
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Odzyskiwanie hasła</title>
    <link rel="stylesheet" href="../../styles/main.css">
    </link>
</head>

<body style="display: flex; height: 100vh; width: 100%; justify-content: center; align-items: center;">
    <?php if ($status !== ''): ?>
        <div class="password-reset-notification">
            <?php if ($status === ConstUtils::STATUS_SUCCESS): ?>
                Instrukcje dotyczące resetowania hasła zostały wysłane na podany adres e-mail.
            <?php elseif ($status === ConstUtils::STATUS_ERROR_NO_USER): ?>
                Nie znaleziono użytkownika z podanym adresem e-mail.
            <?php elseif ($status === ConstUtils::STATUS_ERROR): ?>
                Wystąpił błąd podczas próby odzyskania hasła. Proszę spróbować ponownie później.
            <?php endif; ?>
            <a href="../login/login.php" class="password-reset-back-link">
                < Powrót do logowania</a>
        </div>
    <?php endif; ?>
    <?php if ($status === ''): ?>
        <div class="password-forgot-container">
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
                <a href="../login/login.php" class="password-reset-back-link">
                    < Powrót do logowania</a>
            </div>
        </div>
    <?php endif; ?>

</body>

</html>
