<?php

class LoginService
{
    public static function login($email, $password)
    {
        $dbHandler = DatabaseHandler::getDbHandler();

        $user = $dbHandler->query(new User(), CrudEnum::READ, $email);

        if ($user) {
            if (password_verify($password, $user->getPassword())) {
                $_SESSION[ConstUtils::SESSION_USER] = $user;
                return $user;
            } else {
                throw new PasswordMismatchException();
            }
        } else {
            throw new NoSuchUserException();
        }
    }

    public static function logout() {
        unset($_SESSION[ConstUtils::SESSION_USER]);
    }

    public static function register($firstName, $lastName, $email, $password)
    {
        $dbHandler = DatabaseHandler::getDbHandler();

        $user = $dbHandler->query(new User(), CrudEnum::READ, $email);
        if (!is_null($user)) {
            echo $user->getFirstName();
            throw new EmailInUseException();
        }
        $dbHandler->query(new User(), CrudEnum::CREATE, $firstName, $lastName, $email, password_hash($password, PASSWORD_DEFAULT));
        $user = $dbHandler->query(new User(), CrudEnum::READ, $email);
    }

    public static function recoverPassword($email): void
    {
        $dbHandler = DatabaseHandler::getDbHandler();

        $user = $dbHandler->query(new User(), CrudEnum::READ, $email);
        if ($user) {
            $recoveryToken = new RecoveryPassword();
            $recoveryToken->generateRecoveryToken();

            $dbHandler->query(
                $recoveryToken,
                CrudEnum::DELETE,
                $user->getId()
            );

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

    public static function checkRecoveryToken($token)
    {
        $dbHandler = DatabaseHandler::getDbHandler();
        $recoveryToken = $dbHandler->query(new RecoveryPassword(), CrudEnum::READ, $token);

        if ($recoveryToken) {
            if ($recoveryToken->isExpired()) {
                throw new ExpiredTokenException();
            }
            return $recoveryToken;
        } else {
            throw new NoTokenFoundException();
        }
    }

    public static function resetPassword($userId, $password)
    {
        $dbHandler = DatabaseHandler::getDbHandler();
        $user = $dbHandler->query(new User(), CrudEnum::READ, $userId);

        if ($user) {
            $dbHandler->query(
                $user,
                CrudEnum::UPDATE,
                $user->getFirstname(),
                $user->getLastname(),
                $user->getEmail(),
                password_hash($password, PASSWORD_DEFAULT),
                $userId
            );

            $dbHandler->query(
                new RecoveryPassword(),
                CrudEnum::DELETE,
                $userId
            );
        } else {
            throw new NoSuchUserException();
        }
    }

    public static function saveReward($userId, $rewardId)
    {
        $dbHandler = DatabaseHandler::getDbHandler();

        $createdAt = (new DateTime())->format(ConstUtils::DATETIME_FORMAT);
        $expiresAt = (new DateTime())->modify('+1 day')->format(ConstUtils::DATETIME_FORMAT);

        $dbHandler->query(new UserSpinReward(), CrudEnum::DELETE, $userId);
        $dbHandler->query(new UserSpinReward(), CrudEnum::CREATE, $userId, $rewardId, $createdAt, $expiresAt);
    }

    public static function doesUserHaveReward($userId)
    {
        $dbHandler = DatabaseHandler::getDbHandler();

        $reward = $dbHandler->query(new UserSpinReward(), CrudEnum::READ, $userId);

        if (!is_null($reward)) {
            return !$reward->isExpired();
        }
        return false;
    }

    public static function getUserReward($userId)
    {
        return DatabaseHandler::getDbHandler()->query(new UserSpinReward(), CrudEnum::READ, $userId);
    }
}
