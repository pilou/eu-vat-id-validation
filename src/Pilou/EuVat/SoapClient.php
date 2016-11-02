<?php

namespace Pilou\EuVat;

class SoapClient extends \SoapClient
{
    const WSDL_URL = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    public function __construct()
    {
        parent::SoapClient(self::WSDL_URL);
    }
}
