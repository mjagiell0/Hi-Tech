<?php

class Category extends Entity{

    private $id;
    private $name;
    private $section_id;

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getSectionId(){
        return $this->section_id;
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

    public function withSectionId($section_id) {
        $this->section_id = $section_id;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        if (!is_string($criteria[0]) || !is_int($criteria[1]) || count($criteria) != 2) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }
        return "INSERT INTO Category (name, section_id) VALUES (?, ?)";
    }

    protected function getReadQuery(...$criteria)
    {
        if (!is_int($criteria[0]) || count($criteria) != 1) {
            if (!is_string($criteria[0])) {
                throw new InvalidArgumentException("Insufficient criteria for READ operation.");
            }
            return "SELECT * FROM Category WHERE name = ?";
        }
        return "SELECT * FROM Category WHERE section_id = ?";
    }

    protected function getUpdateQuery(...$criteria)
    {
        // TODO: Implement getUpdateQuery() method.
    }

    protected function getDeleteQuery(...$criteria)
    {
        // TODO: Implement getDeleteQuery() method.
    }

    public function fromResult($result): ?array
    {
        $objects = [];

        while ($row = $result->fetch_assoc()) {
            $objects[] = (new Category())
                ->withId($row[ConstUtils::FIELD_LABEL_ID])
                ->withName($row[ConstUtils::FIELD_LABEL_NAME])
                ->withSectionId($row[ConstUtils::FIELD_LABEL_SECTION_ID]);
        }

        return empty($objects) ? null : $objects;
    }

    public function getTableName()
    {
        return "category";
    }

    public function prepareToDisplay()
    {
        $this->name = htmlspecialchars($this->getName());
    }
}