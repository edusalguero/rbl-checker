<?php


namespace EduSalguero\RblChecker\Test;


use EduSalguero\RblChecker\Queryfier\Dns;
use PHPUnit\Framework\TestCase;

class DnsTest extends TestCase
{

    public function test_lookupExist()
    {
        $a = Dns::create()->lookup('edusalguero.com');
        $this->assertTrue($a->exist());
    }

    public function test_lookupNotExist()
    {
        $a = Dns::create()->lookup('not-exist.edusalguero.com');
        $this->assertFalse($a->exist());
    }

    public function test_lookupCompareWithDnsInBuildFunction()
    {
        $hostname = 'edusalguero.com';
        $a = Dns::create()->lookup($hostname);
        $recordA = dns_get_record($hostname,DNS_A);
        $this->assertEquals(!empty($recordA),$a->exist());

        $recordTxt = dns_get_record($hostname,DNS_TXT);
        $entries = isset($recordTxt[0]['entries'])?$recordTxt[0]['entries']:[];
        $this->assertEquals($entries,$a->extraInfo());
    }
}
