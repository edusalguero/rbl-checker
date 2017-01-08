<?php


namespace EduSalguero\RblChecker\Test;


use EduSalguero\RblChecker\Check\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class ResponseTest
 * @package EduSalguero\RblChecker\Test
 */
class ResponseTest extends TestCase
{

    public function test_createPropelInstance()
    {
        $ip = '1.2.3.5';
        $dnsrblDomainName='test.dnsrbl.com';
        $blacklisted = false;
        $response = Response::create($ip, $dnsrblDomainName, $blacklisted);
        $this->assertInstanceOf(Response::class,$response);
    }
    public function test_contructNotBlackListedHasPropelValues()
    {
        $ip = '1.2.3.5';
        $dnsrblDomainName='test.dnsrbl.com';
        $blacklisted = false;
        $response = Response::create($ip, $dnsrblDomainName, $blacklisted);

        $this->assertFalse($response->isBlacklisted());
        $this->assertEquals($ip, $response->ip());
        $this->assertEquals($dnsrblDomainName,$response->dnsrblDomainName());
        $this->assertEmpty($response->extraInfo());
    }

    public function test_contructBlackListedHasPropelValues()
    {
        $ip = '1.2.3.5';
        $dnsrblDomainName='test.dnsrbl.com';
        $blacklisted = true;
        $extraInfo[]='Info about entry';
        $response = Response::create($ip, $dnsrblDomainName, $blacklisted,$extraInfo);

        $this->assertTrue($response->isBlacklisted());
        $this->assertEquals($ip, $response->ip());
        $this->assertEquals($dnsrblDomainName,$response->dnsrblDomainName());
        $this->assertEquals($extraInfo,$response->extraInfo());
    }
}
