<?php

class Section extends Entity {
    private $id;
    private $name;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withName($name) {
        $this->name = $name;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (count($criteria) != 1 || !is_string($criteria[0])) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }
        return "INSERT INTO section (name) VALUES (?)";
    }

    protected function getReadQuery(...$criteria)
    {
        return "SELECT * FROM section";
    }

    protected function getUpdateQuery(...$criteria)
    {
        // TODO: Implement getUpdateQuery() method.
    }

    protected function getDeleteQuery(...$criteria)
    {
        // TODO: Implement getDeleteQuery() method.
    }

    public function fromRow($row)
    {
        return (new Section())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withName($row[ConstUtils::FIELD_LABEL_NAME]);
    }

    public function getTableName()
    {
        return "section";
    }

    public function prepareToDisplay()
    {
        $this->name = htmlspecialchars($this->name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}