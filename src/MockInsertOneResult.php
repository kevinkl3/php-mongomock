<?php

namespace Helmich\MongoMock;

use MongoDB\InsertOneResult;

class MockInsertOneResult extends InsertOneResult
{
    private $insertedId;

    /** @noinspection PhpMissingParentConstructorInspection */
    public function __construct($insertedId)
    {
        $this->insertedId = $insertedId;
    }

    public function getInsertedCount(): int
    {
        return 1;
    }

    public function getInsertedId(): mixed
    {
        return $this->insertedId;
    }

    public function isAcknowledged(): bool
    {
        return true;
    }

}