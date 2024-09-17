<?php

namespace Vendor;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require_once __DIR__ . '/vendor/autoload.php';

interface RemoteApiCaller {
    public function call(): string;
}

class HttpRemoteApiCaller implements RemoteApiCaller {
    public function call(): string
    {
        return 'http';
    }
}

class LogbookRemoteApiCaller implements RemoteApiCaller {
    public function call(): string
    {
        return 'logbook';
    }
}

const ENV = 'dev';

$xmlContainer = new ContainerBuilder();
$xmlLoader = new XmlFileLoader($xmlContainer, new FileLocator(__DIR__ . '/config'), ENV);
$xmlLoader->load('services.xml');

$xmlCaller = $xmlContainer->get(RemoteApiCaller::class);
echo "[XML] Expects 'logbook' got '{$xmlCaller->call()}'" . PHP_EOL;


$yamlContainer = new ContainerBuilder();
$yamlLoader = new YamlFileLoader($yamlContainer, new FileLocator(__DIR__ . '/config'), ENV);
$yamlLoader->load('services.yaml');

$yamlCaller = $yamlContainer->get(RemoteApiCaller::class);
echo "[YAML] Expects 'logbook' got '{$yamlCaller->call()}'" . PHP_EOL;

if ($yamlCaller->call() === $xmlCaller->call()) {
    echo "[ALL] Different file loaders have the same value :D" . PHP_EOL;
} else {
    echo "[ALL] Different file loaders don't have the same result D:" . PHP_EOL;
}
