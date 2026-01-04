<?php

namespace Helmich\MongoMock;

use Iterator;
use MongoDB\BSON\Int64;
use MongoDB\Driver\CursorInterface;
use MongoDB\Driver\Manager;
use MongoDB\Driver\Server;

class MockCursor implements CursorInterface
{
    private $store = [];
    private $position = 0;
    private $cursorId;
    private $typeMap = [];
    private static $mockServer = null;

    public function __construct(array $documents = [])
    {
        $this->store = $documents;
        $this->cursorId = new Int64(spl_object_id($this));
    }

    public function getId(): Int64
    {
        return $this->cursorId;
    }

    public function getServer(): Server
    {
        if (self::$mockServer === null) {
            // Create a Manager with a dummy connection string to get a Server instance
            // This is a mock, so we don't need a real connection
            try {
                $manager = new Manager('mongodb://localhost:27017');
                $servers = $manager->getServers();
                if (!empty($servers)) {
                    self::$mockServer = reset($servers);
                } else {
                    // If we can't get a server from Manager, create a minimal mock
                    // This should not happen in practice, but handle it gracefully
                    throw new \RuntimeException('Unable to create mock server');
                }
            } catch (\Exception $e) {
                // Fallback: try to get server from a different approach
                // For a mock, we'll need to handle this case
                throw new \RuntimeException('Mock server creation failed: ' . $e->getMessage());
            }
        }
        return self::$mockServer;
    }

    public function isDead(): bool
    {
        return !isset($this->store[$this->position]);
    }

    public function setTypeMap(array $typemap): void
    {
        $this->typeMap = $typemap;
    }

    public function toArray(): array
    {
        return $this->store;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): object|array|null
    {
        return $this->store[$this->position] ?? null;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->store[$this->position]);
    }
}
