<?php
namespace EduSalguero\RblChecker\Queryfier;


/**
 * Interface QueryfierInterface
 * @package EduSalguero\RblChecker\Queryfier
 */
interface QueryfierInterface
{
    /**
     * @param string $hostname
     *
     * @return Response
     */
    public function lookup($hostname);
}