<?php
class User implements Querable{
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $password;

    public function __construct() {
        $this->id = null;
        $this->firstname = ConstUtils::BLANK_STRING;
        $this->lastname = ConstUtils::BLANK_STRING;
        $this->email = ConstUtils::BLANK_STRING;
        $this->password = ConstUtils::BLANK_STRING;
    }

    public function withId($id) {
        $this->id = $id;
        return $this;
    }

    public function withFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    public function withLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    public function withEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function withPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getQuery(...$criteria) {
        if (!filter_var($criteria[0], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format.");
        }
        return "SELECT id, firstname, lastname, email, password FROM user WHERE email = ?";
    }

    public function fromResult($result) {
        if ($row = $result->fetch_assoc()) {
        return $this
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withFirstname($row[ConstUtils::FIELD_LABEL_FIRSTNAME])
            ->withLastname($row[ConstUtils::FIELD_LABEL_LASTNAME])
            ->withEmail($row[ConstUtils::FIELD_LABEL_EMAIL])
            ->withPassword($row[ConstUtils::FIELD_LABEL_PASSWORD]);
        }
        return null;
    }

    public function getTableName() {
        return 'user';
    }

    public function __toString() {
        return "User [id={$this->id}, firstname={$this->firstname}, lastname={$this->lastname}, email={$this->email}]";
    }
}
