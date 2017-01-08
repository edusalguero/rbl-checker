<?php

namespace EduSalguero\RblChecker\Test;


use EduSalguero\RblChecker\Check;
use EduSalguero\RblChecker\Exceptions\InvalidIpException;
use EduSalguero\RblChecker\Queryfier\Dns;
use EduSalguero\RblChecker\Queryfier\QueryfierInterface;
use EduSalguero\RblChecker\Queryfier\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class CheckTest
 * @package EduSalguero\RblChecker\Test
 */
class CheckTest extends TestCase
{
    protected $queryfier;

    protected function setUp()
    {
       $this->queryfier =  Dns::create();
    }

    /**
     * @return array
     */
    public function setupProvider()
    {
        return [['1.2.3.4','test.dnsrbl.com','4.3.2.1','4.3.2.1.test.dnsrbl.com']];
    }

    /**
     * @dataProvider setupProvider
     */
    public function test_setupReturnsProperInstance($ip, $dnsrblDomainName)
    {
        $this->assertInstanceOf(Check::class,Check::setup($dnsrblDomainName,$ip,$this->queryfier));
    }

    /**
     * @dataProvider setupProvider
     * @param $ip
     * @param $dnsrblDomainName
     * @param $reversedIp
     * @param $hostname
     */
    public function test_reverseIp($ip, $dnsrblDomainName, $reversedIp, $hostname)
    {
        $checker  = Check::setup($dnsrblDomainName,$ip,$this->queryfier);
        $this->assertEquals($reversedIp,$this->invokeMethod($checker,'reverseIp',[$ip]));
    }

    /**
     * @dataProvider setupProvider
     * @param $ip
     * @param $dnsrblDomainName
     * @param $reversedIp
     * @param $hostname
     */
    public function test_createHostname($ip, $dnsrblDomainName, $reversedIp, $hostname)
    {
        $checker  = Check::setup($dnsrblDomainName,$ip,$this->queryfier);
        $this->assertEquals($hostname,$this->invokeMethod($checker,'createHostname'));
    }

    /**
     * @dataProvider setupProvider
     * @param $ip
     * @param $dnsrblDomainName
     * @param $reversedIp
     * @param $hostname
     */
    public function test_setupWrongIpThrowsInvalidIpException($ip, $dnsrblDomainName, $reversedIp, $hostname)
    {
        $wrongIp='is not an ip';
        $this->expectException(InvalidIpException::class);
        Check::setup($dnsrblDomainName,$wrongIp,$this->queryfier);
    }


    /**
     * @dataProvider setupProvider
     * @param $ip
     * @param $dnsrblDomainName
     * @param $reversedIp
     * @param $hostname
     */
    public function test_lookupNotBlacklisted($ip, $dnsrblDomainName, $reversedIp, $hostname)
    {

        $dnsQueryfierMock = $this->createMock(Dns::class);
        $queryResponse = Response::create($hostname,false);
        $dnsQueryfierMock->method('lookup')->willReturn($queryResponse);
        /** @var QueryfierInterface $dnsQueryfierMock */
        $checker  = Check::setup($dnsrblDomainName,$ip,$dnsQueryfierMock);
        $checkerResponse = $checker->lookup();
        $this->assertEquals(false, $checkerResponse->isBlacklisted());
        $this->assertEquals([], $checkerResponse->extraInfo());
        $this->assertEquals($dnsrblDomainName, $checkerResponse->dnsrblDomainName());

    }

    /**
     * @dataProvider setupProvider
     * @param $ip
     * @param $dnsrblDomainName
     * @param $reversedIp
     * @param $hostname
     */
    public function test_lookupBlacklisted($ip, $dnsrblDomainName, $reversedIp, $hostname)
    {

        $dnsQueryfierMock = $this->createMock(Dns::class);
        $queryResponse = Response::create($hostname,true, ['test']);
        $dnsQueryfierMock->method('lookup')->willReturn($queryResponse);
        /** @var QueryfierInterface $dnsQueryfierMock */
        $checker  = Check::setup($dnsrblDomainName,$ip,$dnsQueryfierMock);
        $checkerResponse = $checker->lookup();
        $this->assertEquals(true, $checkerResponse->isBlacklisted());
        $this->assertEquals(['test'], $checkerResponse->extraInfo());
        $this->assertEquals($dnsrblDomainName, $checkerResponse->dnsrblDomainName());

    }
    /**
     * @param $object
     * @param $methodName
     * @param array $parameters
     *
     * @return mixed
     */
    private function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
