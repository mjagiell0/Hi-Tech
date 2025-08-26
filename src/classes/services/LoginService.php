<?php

class LoginService
{
    private static function getDbHandler()
    {
        return new DatabaseHandler(
            $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            $_ENV['DB_NAME']
        );
    }

    public static function login($email, $password)
    {
        $dbHandler = self::getDbHandler();

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

    public static function recoverPassword($email)
    {
        $dbHandler = self::getDbHandler();

        $user = $dbHandler->query(new User(), CrudEnum::READ, $email);
        if ($user) {
            $recoveryToken = new RecoveryPassword($email, $user->getId());
            $recoveryToken->generateRecoveryToken();

            $dbHandler->query(
                $recoveryToken,
                CrudEnum::CREATE,
                $user->getId(),
                $recoveryToken->getRecoveryToken(),
                $recoveryToken->getExpirationDate(),
                $recoveryToken->getCreatedAt()
            );

            $message = "To reset your password, please click the following link: \r\n" .
                "http://localhost/Hi-Tech/src/pages/reset_password/reset_password.php?token=" . $recoveryToken->getRecoveryToken();
            $message = wordwrap($message, 70, "\r\n");
            $headers = "From: no-reply@hi-tech.com\r\n" .
                "Reply-To: no-reply@hi-tech.com\r\n" .
                "X-Mailer: PHP/" . phpversion();
            mail(
                $email,
                "Password Recovery",
                $message,
                $headers
            );
        } else {
            throw new NoSuchUserException();
        }
    }
}
