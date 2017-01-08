<?php


namespace EduSalguero\RblChecker\Check\Response;


use EduSalguero\RblChecker\Check\Response;

/**
 * Class Collection
 * @package EduSalguero\RblChecker\Check\Response
 */
class Collection implements \IteratorAggregate, \Countable
{
    /**
     * @var Response[]
     */
    protected $items = [];

    /**
     * @var int
     */
    protected $count = 0;


    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @param Response $item
     *
     * @return $this
     */
    public function add(Response $item)
    {
        $this->items[$this->count++] = $item;

        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }


}