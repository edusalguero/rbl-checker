<?php


namespace EduSalguero\RblChecker;


use EduSalguero\RblChecker\Exceptions\ConfigFileNotExistException;
use EduSalguero\RblChecker\Exceptions\ConfigRBLListInvalidException;

/**
 * Class Config
 * @package EduSalguero\RblChecker
 */
class Config
{
    /**
     * @var string
     */
    protected $filename;
    /**
     * @var array
     */
    protected $dnsRBLList;

    /**
     * Config constructor.
     *
     * @param $filename
     */
    private function __construct($filename)
    {
        $this->loadConfig($filename);
    }

    /**
     * @param $filename
     *
     * @return Config
     */
    public static function load($filename)
    {
        return new static($filename);
    }

    /**
     * @return array
     */
    public function dnsrbls()
    {
        return $this->dnsRBLList;
    }

    /**
     * @param $filename
     *
     * @throws ConfigFileNotExistException
     * @throws ConfigRBLListInvalidException
     */
    private function loadConfig($filename)
    {
        if(!file_exists($filename))
        {
            throw new ConfigFileNotExistException(sprintf('Config file "%s" not exist!',
                                                          $filename));
        }
        $configDataString = file_get_contents($filename);
        $configData = json_decode($configDataString, true);
        if(!is_null($configData) && isset($configData['dnsrbls'])
           && is_array($configData['dnsrbls'])
        )
        {
            $this->dnsRBLList = $configData['dnsrbls'];
        }
        else
        {
            throw  new ConfigRBLListInvalidException();
        }
    }


}