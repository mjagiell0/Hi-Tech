<?php

class LoginService
{
    public static function login($email, $password)
    {
        $dbHandler = new DatabaseHandler(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            $_ENV['DB_NAME']
        );

        $user = $dbHandler->query(new User(), CrudEnum::READ, $email);

        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $_SESSION[ConstUtils::USER_ID_LABEL] = $user->getId();
                $_SESSION[ConstUtils::USER_EMAIL_LABEL] = $user->getEmail();
                return $user;
            } else {
                throw new PasswordMismatchException();
            }
        } else {
            throw new NoSuchUserException();
        }
    }

    public static function recoverPassword($email) {
        $dbHandler = new DatabaseHandler(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            $_ENV['DB_NAME']
        );

        $user = $dbHandler->query(new User(), CrudEnum::READ, $email);
        if ($user) {
            $recoveryToken = new RecoveryPassword($email, $user->getId());
            $recoveryToken->generateRecoveryToken();
        } else {
            throw new NoSuchUserException();
        }
    }
}
