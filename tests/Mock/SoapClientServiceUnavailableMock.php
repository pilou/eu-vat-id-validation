<?php
namespace Mock;

use Pilou\EuVat\SoapClient;
use SoapFault;

class SoapClientServiceUnavailableMock extends SoapClient
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkVat()
    {
        throw new SoapFault(
            'SERVICE_UNAVAILABLE',
            'The SOAP service is unavailable, try again later'
        );
    }
}
