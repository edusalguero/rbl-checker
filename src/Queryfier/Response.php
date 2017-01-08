<?php


namespace EduSalguero\RblChecker\Queryfier;


class Response
{
    /**
     * @var string
     */
    private $hostname;
    /**
     * @var bool
     */
    private $exist;
    /**
     * @var array
     */
    private $extraInfo;

    public function __construct($hostname, $exist = false, array $extraInfo = [])
    {
        $this->hostname = $hostname;
        $this->exist = $exist;
        $this->extraInfo = $extraInfo;
    }

    /**
     * @param $hostname
     * @param bool $listed
     * @param array $extraInfo
     *
     * @return Response
     */
    public static function create($hostname, $listed = false, array $extraInfo = [])
    {
        return new static($hostname, $listed, $extraInfo);
    }

    /**
     * @return string
     */
    public function hostname()
    {
        return $this->hostname;
    }

    /**
     * @return boolean
     */
    public function exist()
    {
        return $this->exist;
    }

    /**
     * @return array
     */
    public function extraInfo()
    {
        return $this->extraInfo;
    }
}