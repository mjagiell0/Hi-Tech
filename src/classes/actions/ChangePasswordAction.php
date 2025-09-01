<?php

include_once '../abstracts/Entity.php';
include_once '../../classes/exceptions/NoSuchUserException.php';
include_once '../enums/CrudEnum.php';
include_once '../../classes/entities/RecoveryPassword.php';
include_once '../../classes/exceptions/PasswordMismatchException.php';
include_once '../../classes/utils/ConstUtils.php';
include_once '../../classes/entities/User.php';
include_once '../../classes/handlers/DatabaseHandler.php';
include_once '../../classes/Services/LoginService.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();
session_start();

$password = $_POST[ConstUtils::FIELD_LABEL_PASSWORD];
$userId = $_POST[ConstUtils::FIELD_LABEL_USER_ID];
$token = $_POST[ConstUtils::FIELD_LABEL_RECOVERY_TOKEN];

$loginService = new LoginService();

try {
    $loginService->resetPassword($userId, $password);
    header("Location: ../../pages/reset_password/reset_password.php?token=".$token."&status=".ConstUtils::STATUS_SUCCESS);
} catch (Exception $e) {
    header("Location: ../../pages/reset_password/reset_password.php?token=".$token."&status=".ConstUtils::STATUS_ERROR);
}
