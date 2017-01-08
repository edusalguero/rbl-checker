<?php


namespace EduSalguero\RblChecker\Test;


use EduSalguero\RblChecker\Config;
use EduSalguero\RblChecker\Queryfier\Dns;
use EduSalguero\RblChecker\Queryfier\QueryfierInterface;
use EduSalguero\RblChecker\Queryfier\Response;
use EduSalguero\RblChecker\RblChecker;
use org\bovigo\vfs\vfsStream;

class RblCheckerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Config
     */
    protected $config;

    public function setUp()
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
        $this->config = Config::load($configFile->url());

    }

    public function test_checkBlackList()
    {
        $mockQueryfier = $this->createMock(Dns::class);
        $queryResponse = Response::create('sbl-xbl.spamhaus.org',true, ['test']);
        $mockQueryfier->method('lookup')->willReturn($queryResponse);
        /** @var QueryfierInterface $mockQueryfier */

        $rblchecker = RblChecker::check($this->config,$mockQueryfier,'1.2.3.4');
        
        $this->assertTrue($rblchecker->ipInSpamLists());
    }

    public function test_checkNotBlackList()
    {
        $mockQueryfier = $this->createMock(Dns::class);
        $queryResponse = Response::create('sbl-xbl.spamhaus.org',false);
        $mockQueryfier->method('lookup')->willReturn($queryResponse);
        /** @var QueryfierInterface $mockQueryfier */

        $rblchecker = RblChecker::check($this->config,$mockQueryfier,'1.2.3.4');

        $this->assertFalse($rblchecker->ipInSpamLists());
    }
}
