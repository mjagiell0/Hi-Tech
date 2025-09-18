<?php
include_once '../../classes/enums/CrudEnum.php';
include_once '../../classes/abstracts/Entity.php';
include_once __DIR__ . '/../../../vendor/autoload.php';
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/services/LoginService.php';
include_once '../../classes/exceptions/NoTokenFoundException.php';
include_once '../../classes/exceptions/ExpiredTokenException.php';
include_once '../../classes/handlers/DatabaseHandler.php';
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/entities/RecoveryPassword.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();

$token = $_GET[ConstUtils::GET_PARAMETER_TOKEN] ?? '';

$status = $_GET[ConstUtils::STATUS] ?? '';

$loginService = new LoginService();
try {
    $token = $loginService->checkRecoveryToken($token);
} catch (NoTokenFoundException $e) {
    if ($status === '')
        header("Location: ../login/login.php");
} catch (ExpiredTokenException $e) {
    if ($status === '') {
        $status = ConstUtils::STATUS_ERROR_TOKEN_EXPIRED;
    }
}

?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Odzyskiwanie hasła</title>
    <link rel="stylesheet" href="../../styles/main.css">
    </link>
    <script src="reset_password.js" defer></script>
</head>

<body style="display: flex; justify-content: center; height: 100vh; width: 100%;">


<div class="auth-wrapper">
    <img class="logo" src="../../assets/logo.png" alt="Logo">
    <?php if ($status !== ''): ?>
        <div class="password-reset-notification">
            <?php if ($status === ConstUtils::STATUS_SUCCESS): ?>
                Pomyślnie zresetowano hasło.
            <?php elseif ($status === ConstUtils::STATUS_ERROR_TOKEN_EXPIRED): ?>
                Token utracił swoją ważność. Poproś o ponowne zresetowanie hasła.
            <?php elseif ($status === ConstUtils::STATUS_ERROR_NO_USER): ?>
                Nie znaleziono użytkownika z podanym adresem e-mail.
            <?php elseif ($status === ConstUtils::STATUS_ERROR): ?>
                Wystąpił błąd podczas próby odzyskania hasła. Proszę spróbować ponownie później.
            <?php endif; ?>
            <br>
            <a href="../login/login.php" class="password-reset-back-link">
                < Powrót do logowania</a>
        </div>
    <?php endif; ?>
    <?php if ($status === ''): ?>
        <div class="password-reset-container">
            <h2 class="password-reset-title">Utwórz nowe hasło<br>
                <p class="password-reset-subtitle">Min. 6 znaków, w tym jedna cyfra.</p>
            </h2>
            <form class="password-reset-form" id="resetForm" action="../../classes/actions/ChangePasswordAction.php"
                  method="POST" novalidate>
                <label class="password-reset-label" for="password">Hasło:</label>
                <input class="password-reset-input" type="password" id="password" name="password" required>
                <div class="error-message" id="passwordError"></div>
                <label class="password-reset-label" for="confirm_password">Powtórz hasło:</label>
                <input class="password-reset-input" type="password" id="confirm_password" name="confirm_password"
                       required>
                <div class="error-message" id="confirmPasswordError"></div>
                <input type="hidden" name="user_id" value="<?php echo $token->getUserId(); ?>">
                <input type="hidden" name="token" value="<?php echo $token->getRecoveryToken() ?>">
                <button class="password-reset-button" type="submit">Zmień hasło</button>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>


</html>
