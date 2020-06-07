<?php


namespace EduSalguero\RblChecker;

/**
 * Class RblCheckerFacade
 * @package EduSalguero\RblChecker
 */
use EduSalguero\RblChecker\Exceptions\InvalidIpException;
use EduSalguero\RblChecker\Queryfier\Dns;

/**
 * Class RblCheckerFacade
 * @package EduSalguero\RblChecker
 */
class DnsRblCheckerFacade
{

    /**
     * @var string
     */
    protected $ipToCheck;

    /**
     * RblCheckerFacade constructor.
     *
     * @param string $ipToCheck
     * @param string $configPath
     */
    private function __construct($ipToCheck, $configPath)
    {

        $this->setIp($ipToCheck);
        $config = Config::load($configPath);
        $queryfier= Dns::create();
        $this->checker = RblChecker::check($config,$queryfier, $ipToCheck);
    }

    /**
     * @param string $ipToCheck
     * @param string $configPath
     *
     * @return static
     */
    public static function create($ipToCheck, $configPath)
    {
        return new static($ipToCheck, $configPath);
    }

    /**
     * @return bool
     */
    public function blacklisted()
    {
        return $this->checker->ipInSpamLists();
    }

    /**
     *@return bool|string
     */
    public function getDNSBLDomainName()
    {
        return $this->checker->getDNSBLDomainName();
    }



    /**
     * @return Check\Response\Collection
     */
    public function checks()
    {
        return $this->checker->checkResponseCollection();
    }

    /**
     * @param string $ipToCheck
     *
     * @throws InvalidIpException
     */
    private function setIp($ipToCheck)
    {
        if(filter_var($ipToCheck, FILTER_VALIDATE_IP) === false)
        {
            throw new InvalidIpException(sprintf("%s is not a valid IPv4",$ipToCheck));
        }
        $this->ipToCheck = $ipToCheck;
    }

}
