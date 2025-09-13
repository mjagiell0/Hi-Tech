<?php

class User extends Entity
{
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $password;

    public function __construct()
    {
        $this->id = null;
        $this->firstname = ConstUtils::BLANK_STRING;
        $this->lastname = ConstUtils::BLANK_STRING;
        $this->email = ConstUtils::BLANK_STRING;
        $this->password = ConstUtils::BLANK_STRING;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function withLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function withEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function withPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    protected function getReadQuery(...$criteria)
    {
        if (!filter_var($criteria[0], FILTER_VALIDATE_EMAIL) || count($criteria) != 1) {
            if (!filter_var($criteria[0], FILTER_VALIDATE_INT) || count($criteria) != 1) {
                throw new InvalidArgumentException("Insufficient criteria for READ operation.");
            }
            return "SELECT id, firstname, lastname, email, password FROM user WHERE id = ?";
        }
        return "SELECT id, firstname, lastname, email, password FROM user WHERE email = ?";
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 4 ||
            !is_string($criteria[0]) ||
            !is_string($criteria[1]) ||
            !filter_var($criteria[2], FILTER_VALIDATE_EMAIL) ||
            !is_string($criteria[3])) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }
        return "INSERT INTO user (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
    }

    protected function getUpdateQuery(...$criteria)
    {
        if (count($criteria) != 5 ||
            !is_string($criteria[0]) ||
            !is_string($criteria[1]) ||
            !filter_var($criteria[2], FILTER_VALIDATE_EMAIL) ||
            !is_string($criteria[3]) ||
            !filter_var($criteria[4], FILTER_VALIDATE_INT)) {
            throw new InvalidArgumentException("Insufficient criteria for UPDATE operation.");
        }
        return "UPDATE user SET firstname = ?, lastname = ?, email = ?, password = ? WHERE id = ?";
    }

    protected function getDeleteQuery(...$criteria)
    {
        return null;
    }

    public function fromResult($result)
    {
        if ($row = $result->fetch_assoc()) {
            return $this
                ->withId($row[ConstUtils::FIELD_LABEL_ID])
                ->withFirstname($row[ConstUtils::FIELD_LABEL_FIRSTNAME])
                ->withLastname($row[ConstUtils::FIELD_LABEL_LASTNAME])
                ->withEmail($row[ConstUtils::FIELD_LABEL_EMAIL])
                ->withPassword($row[ConstUtils::FIELD_LABEL_PASSWORD]);
        }
        throw new NoSuchUserException();
    }

    public function getTableName()
    {
        return 'user';
    }

    public function __toString()
    {
        return "User [id={$this->id}, firstname={$this->firstname}, lastname={$this->lastname}, email={$this->email}]";
    }

    public function prepareToDisplay()
    {
        $this->firstname = htmlspecialchars($this->firstname, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->lastname = htmlspecialchars($this->lastname, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->email = htmlspecialchars($this->email, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
