<?php

namespace Helmich\MongoMock;

use MongoDB\InsertManyResult;

class MockInsertManyResult extends InsertManyResult
{
    private $insertedIds;

    /**
     * @param array $insertedIds
     */
    public function __construct(array $insertedIds)
    {
        $this->insertedIds = $insertedIds;
    }

    public function getInsertedCount(): int
    {
        return count($this->insertedIds);
    }

    public function getInsertedIds(): array
    {
        return $this->insertedIds;
    }

    public function isAcknowledged(): bool
    {
        return true;
    }

}