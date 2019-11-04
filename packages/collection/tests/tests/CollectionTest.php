<?php

use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldCreateCollection()
    {
        $items = [
            'item1' => 'value1',
            'item2' => 'value2',
            'item3' => 'value3',
            'item4' => 'value4',
        ];

        $collection = new Collection\Collection($items);
        $this->assertEquals($items, $collection->__toArray());
    }

    public function testShouldManageItemsInCollection()
    {
        $items = [
            1 => 1,
            '2' => 2,
            'test' => 'test123'
        ];

        $collection = new Collection\Collection($items);
        $this->assertEquals($items, $collection->__toArray());

        $collection->add('key', 'value');
        $this->assertEquals($items + ['key' => ['value']], $collection->__toArray());

        $collection->remove('key');
        $this->assertEquals($items, $collection->__toArray());

        $this->assertEquals('test123', $collection->get('test'));

        $collection->set('test', 'value');
        $this->assertEquals('value', $collection->get('test'));
    }
}