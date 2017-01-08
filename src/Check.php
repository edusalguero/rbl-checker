<?php


namespace EduSalguero\RblChecker;


use EduSalguero\RblChecker\Check\Response;
use EduSalguero\RblChecker\Exceptions\InvalidIpException;
use EduSalguero\RblChecker\Queryfier\QueryfierInterface;

/**
 * Class Check
 * @package EduSalguero\RblChecker
 */
class Check
{
    /**
     * @var string
     */
    protected $reversedIp;
    /**
     * @var string
     */
    protected $hostname;
    /**
     * @var QueryfierInterface
     */
    protected $queryfier;
    /**
     * @var string
     */
    private $rblrecord;
    /**
     * @var string
     */
    private $ip;

    /**
     * Check constructor.
     *
     * @param string $rblrecord
     * @param string $ip
     * @param QueryfierInterface $queryfier
     *
     * @throws InvalidIpException
     */
    public function __construct($rblrecord, $ip, QueryfierInterface $queryfier)
    {   $this->queryfier = $queryfier;
        $this->setRblRecord($rblrecord);
        $this->setupIp($ip);
    }

    /**
     * @param $rblrecord
     * @param $ip
     * @param QueryfierInterface $queryfier
     *
     * @return Check
     */
    public static function setup($rblrecord, $ip, QueryfierInterface $queryfier)
    {
        return new static($rblrecord, $ip,$queryfier);
    }

    /**
     * @param $ip
     *
     * @throws InvalidIpException
     */
    private function setupIp($ip)
    {
        if(filter_var($ip, FILTER_VALIDATE_IP) === false)
        {
            throw new InvalidIpException(sprintf("%s is not a valid IPv4",$ip));
        }
        $this->ip = $ip;
        $this->reversedIp = $this->reverseIp($ip);
        $this->hostname = $this->createHostname();
    }

    /**
     * @param $ip
     *
     * @return string
     */
    private function reverseIp($ip)
    {
        list($part1, $part2, $part3, $part4) = explode('.', $ip);

        return sprintf('%s.%s.%s.%s', $part4, $part3, $part2, $part1);
    }

    /**
     * @return string
     */
    private function createHostname()
    {
        $hostname = sprintf("%s.%s", $this->reversedIp, $this->rblrecord);

        return $hostname;
    }

    /**
     * @return Response
     */
    public function lookup()
    {
        $response = $this->queryfier->lookup($this->hostname);

        return Response::create($this->ip, $this->rblrecord, $response->exist(), $response->extraInfo());
    }
    

    /**
     * @param $rblrecord
     */
    private function setRblRecord($rblrecord)
    {
        $this->rblrecord = $rblrecord;
    }
}