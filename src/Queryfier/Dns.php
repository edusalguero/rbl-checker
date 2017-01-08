<?php


namespace EduSalguero\RblChecker\Queryfier;


/**
 * Class Dns
 * @package EduSalguero\RblChecker
 */
class Dns implements QueryfierInterface
{

    const  TYPE_A   = DNS_A;
    const  TYPE_TXT = DNS_TXT;

    /**
     * Dns constructor.
     *
     */
    private function __construct()
    {

    }


    /**
     * @return Dns
     */
    public static function create()
    {
        $query = new  static();

        return $query;
    }

    /**
     * @param string $hostname
     *
     * @return Response
     */
    public function lookup($hostname)
    {

        $aRecord = dns_get_record($hostname, self::TYPE_A);
        $exist = false;
        $extraInfo = [];
        if(false !== $aRecord && !empty($aRecord))
        {
            $exist = true;
            $extraInfo = $this->getExtraInfo($hostname);
        }

        return new Response($hostname, $exist, $extraInfo);
    }

    /**
     * @param $hostname
     *
     * @return mixed
     */
    private function getExtraInfo($hostname)
    {
        $extraInfo = [];
        $txtRecord = dns_get_record($hostname, DNS_TXT);
        if(false !== $txtRecord && !empty($txtRecord) && isset($txtRecord[0]['entries']))
        {
            $extraInfo = $txtRecord[0]['entries'];

            return $extraInfo;
        }

        return $extraInfo;
    }


}