<?php


namespace EduSalguero\RblChecker\Check;


use EduSalguero\RblChecker\Check;
use EduSalguero\RblChecker\Queryfier\QueryfierInterface;

/**
 * Class Collection
 * @package EduSalguero\RblChecker\Check
 */
class Collection implements \IteratorAggregate, \Countable
{
    /**
     * @var Check[]
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
     * @param Check $item
     *
     * @return $this
     */
    public function add(Check $item)
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

    /**
     * @param array $rbls
     * @param $ip
     *
     * @return Collection
     */
    public static function createFromRblsArray(array  $rbls = [], $ip, QueryfierInterface $queryfier)
    {
        $checkerCollection = new Collection();
        foreach($rbls as $rbl)
        {
            $checkerCollection->add(Check::setup($rbl, $ip,$queryfier));
        }

        return $checkerCollection;
    }

}