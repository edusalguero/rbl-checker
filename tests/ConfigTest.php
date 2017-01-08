<?php


namespace EduSalguero\RblChecker\Test;


use EduSalguero\RblChecker\Config;
use EduSalguero\RblChecker\Exceptions\ConfigFileNotExistException;
use EduSalguero\RblChecker\Exceptions\ConfigRBLListInvalidException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigTest
 * @package EduSalguero\RblChecker\Test
 */
class ConfigTest extends TestCase
{
    public function test_invalidConfigFile_thrownsConfigFileNotExistException()
    {
        $this->expectException(ConfigFileNotExistException::class);
        Config::load('__WRONG_PATH');
    }

    public function test_validConfigFileWithoutDnsrblsSection_thrownsConfigRBLListInvalidException()
    {
        $root = vfsStream::setup();
        $content = '{}';
        $configFile = vfsStream::newFile('config.json')
                               ->withContent($content)
                               ->at($root);

        $this->expectException(ConfigRBLListInvalidException::class);
        Config::load($configFile->url());
    }

    public function test_validConfigFileLoadPropelData()
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

        $config = Config::load($configFile->url());
        $this->assertEquals(['sbl-xbl.spamhaus.org'],$config->dnsrbls());
    }
}
