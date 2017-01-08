<?php


namespace EduSalguero\RblChecker\Test;


use EduSalguero\RblChecker\Config;
use EduSalguero\RblChecker\DnsRblCheckerFacade;
use EduSalguero\RblChecker\Exceptions\InvalidIpException;
use org\bovigo\vfs\vfsStream;

class DnsRblCheckerFacadeTest extends \PHPUnit_Framework_TestCase
{
    public function test_wrongIpThrowsInvalidIpException()
    {
        $wrongIp='is not an ip';
        $this->expectException(InvalidIpException::class);
        DnsRblCheckerFacade::create($wrongIp,'config/path');
    }

    public function test_createReturnsProperInstance()
    {
        $root = vfsStream::setup();
        $content = '{
        "dnsrbls": [
            "sbl-xbl.spamhaus.org"
            ]
        }';

        $configFile = vfsStream::newFile('config.json')
                               ->withContent($content)
                               ->at($root);

        $ip = '1.2.3.4';
        $this->assertInstanceOf(DnsRblCheckerFacade::class,DnsRblCheckerFacade::create($ip,$configFile->url()));
    }
}
