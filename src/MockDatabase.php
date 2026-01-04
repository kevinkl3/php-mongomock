<?php

namespace Helmich\MongoMock;

use ArrayIterator;
use MongoDB\Collection;
use MongoDB\Database;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Model\CollectionInfoLegacyIterator;

/**
 * A mocked MongoDB database
 *
 * This class mimicks the behaviour of a MongoDB database (and also extends
 * the actual `MongoDB\Database` class and can be used as a drop-in
 * replacement). All operations are performed in-memory and are not persisted.
 *
 * NOTE: This class is not complete! Many methods are missing and I will only
 * implement them as soon as I need them. Feel free to open an issue or (better)
 * a pull request if you need something.
 *
 * @package Helmich\MongoMock
 */
class MockDatabase extends Database
{
    /** @var string */
    private $name;

    /** @var Collection * */
    private $collections = [];

    /** @var array * */
    private $options = [];

    /**
     * @param string $name
     */
    public function __construct(string $name = 'database', array $options = [])
    {
        $this->name = $name;
        $this->options = $options;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * List collections
     *
     * @param  array $options
     * @return CollectionInfoLegacyIterator|\Iterator
     */
    public function listCollections(array $options = []): \Iterator
    {
        $collections = [];
        foreach ($this->collections as $name => $collection) {
            $collections[] = [
                'name' => $this->name . '.' . $name,
                'options' => $collection['options']
            ];
        }
        if(!class_exists("MongoDB\\Model\\CollectionInfoLegacyIterator")){
            return new MockCollectionInfoIterator(new ArrayIterator($collections));
        }
        return new CollectionInfoLegacyIterator(new ArrayIterator($collections));
    }


    /**
     * Return the database name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Returns the database name.
     *
     * @return string
     */
    public function getDatabaseName(): string
    {
        return $this->name;
    }

    /**
     * Return collection
     *
     * @param  string $name
     * @return Collection
     */
    public function __get(string $name): Collection
    {
        return $this->selectCollection($name);
    }

    /**
     * Create collection
     *
     * @param string $name
     * @param array  $options
     * @return array
     */
    public function createCollection(string $collectionName, array $options = []): void
    {
        if (isset($this->collections[$collectionName])) {
            throw new RuntimeException('collection already exists');
        }

        $this->collections[$collectionName] = [
            'collection' => new MockCollection($collectionName, $this),
            'options' => $options
        ];
    }

    /**
     * Return collection
     *
     * @param  string $name
     * @param  array  $options
     * @return Collection
     */
    public function selectCollection(string $collectionName, array $options = []): Collection
    {
        if (!isset($this->collections[$collectionName])) {
            $this->createCollection($collectionName);
        }
        return $this->collections[$collectionName]['collection'];
    }
}
