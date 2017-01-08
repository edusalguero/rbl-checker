<?php
require_once __DIR__ . '/../vendor/autoload.php';

$configPath = __DIR__.'/../config/config.json';

$ip='107.180.4.167';
$checker = \EduSalguero\RblChecker\DnsRblCheckerFacade::create($ip,$configPath);
printf('Blacklisted: %s',$checker->blacklisted()? "true" : "false");