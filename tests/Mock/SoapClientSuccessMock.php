<?php
namespace Mock;

use Pilou\EuVat\SoapClient;
use StdClass;

class SoapClientSuccessMock extends SoapClient
{
    public function __construct()
    {
        parent::__construct();
    }

    public function checkVat()
    {
        $result = new StdClass;
        $result->countryCode = 'DE';
        $result->vatNumber = '273616207';
        $result->requestDate = '2013-01-01 00:00:00';
        $result->valid = true;
        $result->name = 'Ondango';
        $result->address = '1600 Amphitheatre Pkwy, Mountain View, CA';

        return (object) [
            'countryCode' => 'DE',
            'vatNumber' => '273616207',
            'requestDate' => '2013-01-01 00:00:00',
            'valid' => true,
            'name' => 'Ondango',
            'address' => '1600 Amphitheatre Pkwy, Mountain View, CA'
        ];
    }
}
