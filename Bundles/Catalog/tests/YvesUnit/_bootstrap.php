<?php

\SprykerFeature\Shared\Library\Autoloader::unregister();
$bootstrap = SprykerFeature\Shared\Library\SystemUnderTest\SystemUnderTestBootstrap::getInstance();

$application = 'Yves';
$bootstrap->bootstrap($application);
