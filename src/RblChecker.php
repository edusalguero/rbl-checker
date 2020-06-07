<?php


namespace EduSalguero\RblChecker;


use EduSalguero\RblChecker\Check\Collection;
use EduSalguero\RblChecker\Check\Response\Collection as ResponseCollection;
use EduSalguero\RblChecker\Exceptions\InvalidIpException;
use EduSalguero\RblChecker\Queryfier\QueryfierInterface;

class RblChecker
{
    protected $reversedIp;
    protected $ip;
    protected $checkers;
    protected $checkResponseCollection;
    public    $DNSBLDomainName = false;  
    /**
     * @var QueryfierInterface
     */
    private $queryfier;

    /**
     * RblChecker constructor.
     *
     * @param Config $config
     * @param QueryfierInterface $queryfier
     * @param string $ip
     *
     * @throws InvalidIpException
     */
    private function __construct(Config $config, QueryfierInterface $queryfier, $ip)
    {
        $this->config = $config;
        $this->checkResponseCollection = new ResponseCollection();
        $this->setIp($ip);
        $this->queryfier = $queryfier;
        $this->loadCheckers();
    }


    private function loadCheckers()
    {
        $this->checkers =
            Collection::createFromRblsArray($this->config->dnsrbls(), $this->ip, $this->queryfier);
    }

    /**
     * @param string $ip
     *
     * @throws InvalidIpException
     */
    private function setIp($ip)
    {
        if(filter_var($ip, FILTER_VALIDATE_IP) === false)
        {
            throw new InvalidIpException(sprintf("%s is not a valid IPv4", $ip));
        }
        $this->ip = $ip;
    }

    /**
     * @param Config $config
     * @param QueryfierInterface $queryfier
     * @param string $ip
     *
     * @return RblChecker
     */
    public static function check(Config $config, QueryfierInterface $queryfier, $ip)
    {
        return new static($config, $queryfier, $ip);
    }

    /**
     * @return ResponseCollection
     */
    public function checkResponseCollection()
    {
        return $this->checkResponseCollection;
    }

    /**
     * @return bool
     */
    public function ipInSpamLists()
    {
        $isBlacklisted = false;
        /** @var Check $checker */
        foreach($this->checkers as $checker)
        {
            $response = $checker->lookup();
            if($response->isBlacklisted())
            {
                $isBlacklisted = true;
                $this->DNSBLDomainName = $response->dnsrblDomainName();
            }
            $this->checkResponseCollection->add($response);
        }

        return $isBlacklisted;
    }

    /**
     *@return bool|string
     */
    public function getDNSBLDomainName()
    {
        return $this->DNSBLDomainName;
    }

}
