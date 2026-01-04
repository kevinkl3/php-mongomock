<?php

namespace Helmich\MongoMock;

use ArrayIterator;
use Iterator;
/**
 * Implementation of CollectionInfoIterator 
 * in case MongoDB\Model\CollectionInfoLegacyIterator is
 * not available
 * 
 * @package Helmich\MongoMock
 */
class MockCollectionInfoIterator implements Iterator {
    private $arrayIterator;

    public function __construct(ArrayIterator $arrayIterator) {
        $this->arrayIterator = $arrayIterator;
    }

    public function rewind() : void{
        $this->arrayIterator->rewind();
    }

    #[\ReturnTypeWillChange]
    public function current() {
        return $this->arrayIterator->current();
    }

    #[\ReturnTypeWillChange]
    public function key() {
        return $this->arrayIterator->key();
    }

    public function next() : void {
        $this->arrayIterator->next();
    }

    public function valid() : bool {
        return $this->arrayIterator->valid();
    }
}
