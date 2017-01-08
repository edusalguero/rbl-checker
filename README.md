# RBL Checker

RblChekcer is a simple PHP library to check IP against SPAM list.
 
The Facade use the DNS Rbl queryfier to check ips. Currently the library provide a list with 29 DNS Rbl hosts.

More info about DNSRBL in [Wikipedia] (https://en.wikipedia.org/wiki/DNSBL)

## Usage
```php
<?php
$configPath = __DIR__.'/config/config.json';

$ip='107.180.4.167';
$checker = \EduSalguero\RblChecker\DnsRblCheckerFacade::create($ip,$configPath);
printf('Blacklisted: %s',$checker->blacklisted()? "true" : "false");

