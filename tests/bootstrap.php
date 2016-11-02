<?php

require __DIR__ . '/../vendor/autoload.php';

(new Zend\Loader\StandardAutoloader)
    ->registerNamespace('Mock', __DIR__ . '/Mock')
    ->registerNamespace('Pilou/EuVat', __DIR__ . '/../src/Pilou/EuVat')
    ->register();
