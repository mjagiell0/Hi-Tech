<?php




if ($_SERVER[ConstUtils::REQUEST_METHOD] === ConstUtils::POST_METHOD) {
    include_once '../abstracts/Entity.php';
    include_once '../../classes/exceptions/NoSuchUserException.php';
    include_once '../enums/CrudEnum.php';
    include_once '../../classes/exceptions/PasswordMismatchException.php';
    include_once '../../classes/entities/User.php';
    include_once '../../classes/handlers/DatabaseHandler.php';
    include_once '../../classes/Services/LoginService.php';
    require_once __DIR__ . '/../../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
    $dotenv->load();
    session_start();


    $email = $_POST[ConstUtils::FIELD_LABEL_EMAIL];
    $password = $_POST[ConstUtils::FIELD_LABEL_PASSWORD];
    $status = '';

    try {
        LoginService::login($email, $password);
    } catch (NoSuchUserException $e) {
        $status = ConstUtils::STATUS_ERROR_NO_USER;
        header('Location: ../../pages/login/login.php?status='.ConstUtils::STATUS_ERROR_NO_USER);
    } catch (PasswordMismatchException $e) {
        $status = ConstUtils::STATUS_ERROR;
        header('Location: ../../pages/login/login.php?status='.ConstUtils::STATUS_ERROR);
    }

    if ($_SESSION[ConstUtils::SESSION_USER]) {
        $url = $_SESSION[ConstUtils::PREV_PAGE];
        header("Location: $url". ($status !== '' ? '?status='.$status : ''));
    }
}
