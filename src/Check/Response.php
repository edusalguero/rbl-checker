<?php


namespace EduSalguero\RblChecker\Check;


/**
 * Class Response
 * @package EduSalguero\RblChecker\Check
 */
class Response
{
    /**
     * @var string
     */
    private $ip;

    /**
     * @var array
     */
    private $extraInfo;
    /**
     * @var  string
     */
    private $dnsrblDomainName;
    /**
     * @var bool
     */
    private $blacklisted;

    /**
     * CheckResponse constructor.
     *
     * @param $ip
     * @param $dnsrblDomainName
     * @param bool $blacklisted
     * @param array $extraInfo
     *
     */
    private function __construct($ip, $dnsrblDomainName, $blacklisted = false, $extraInfo = [])
    {

        $this->ip = $ip;
        $this->extraInfo = $extraInfo;
        $this->dnsrblDomainName = $dnsrblDomainName;
        $this->blacklisted = $blacklisted;
    }

    /**
     * @param $ip
     * @param $dnsrblDomainName
     * @param bool $blacklisted
     * @param array $extraInfo
     *
     * @return static
     */
    public static function create($ip, $dnsrblDomainName, $blacklisted = false, $extraInfo = [])
    {
        return new static($ip, $dnsrblDomainName, $blacklisted, $extraInfo);
    }

    /**
     * @return string
     */
    public function ip()
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function dnsrblDomainName()
    {
        return $this->dnsrblDomainName;
    }

    /**
     * @return bool
     */
    public function isBlacklisted()
    {
        return $this->blacklisted;
    }

    /**
     * @return array
     */
    public function extraInfo()
    {
        return $this->extraInfo;
    }

}