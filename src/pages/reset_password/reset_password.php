<?php
include_once '../../classes/enums/CrudEnum.php';
include_once '../../classes/abstracts/Entity.php';
require_once __DIR__ . '/../../../vendor/autoload.php';
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/services/LoginService.php';
include_once '../../classes/exceptions/NoTokenFoundException.php';
include_once '../../classes/exceptions/ExpiredTokenException.php';
include_once '../../classes/DatabaseHandler.php';
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/RecoveryPassword.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../'); // Ścieżka do katalogu z .env
$dotenv->load();

$token = $_GET[ConstUtils::GET_PARAMETER_TOKEN] ?? '';
if (empty($token)) {
    echo "Brak tokenu resetowania hasła.";
    exit;
}

$status = $_GET[ConstUtils::FORGOT_PASSWORD_STATUS] ?? '';

$loginService = new LoginService();
try {
    $token = $loginService->checkRecoveryToken($token);
    // TODO: Do wystylizowania
} catch (NoTokenFoundException $e) {
    echo "Nieprawidłowy token resetowania hasła.";
    echo $e->getMessage();
    exit;
} catch (ExpiredTokenException $e) {
    echo "Token resetowania hasła wygasł.";
    exit;
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
            <?php if ($status === ConstUtils::FORGOT_PASSWORD_STATUS_SUCCESS): ?>
                Pomyślnie zresetowano hasło.
            <?php elseif ($status === ConstUtils::FORGOT_PASSWORD_STATUS_ERROR_NO_USER): ?>
                Nie znaleziono użytkownika z podanym adresem e-mail.
            <?php elseif ($status === ConstUtils::FORGOT_PASSWORD_STATUS_ERROR): ?>
                Wystąpił błąd podczas próby odzyskania hasła. Proszę spróbować ponownie później.
            <?php endif; ?>
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
