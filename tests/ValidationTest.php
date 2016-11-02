<?php
namespace Pilou\EuVat;

use PHPUnit\Framework\TestCase;
use Mock\SoapClientSuccessMock;
use Mock\SoapClientServiceUnavailableMock;

class ValidationTest extends TestCase
{
    /**
     * @var Validation
     */
    protected $validator;

    protected function setUp()
    {
        $this->validator = new Validation;
    }
    
    public function testValidResponse()
    {
        $this->validator
            ->setSoapClient(new SoapClientSuccessMock)
            ->setVatId('DE273616207');

        $this->assertTrue($this->validator->isValid());
    }

    public function testInvalidResponse()
    {
        $this->validator->setVatId('foo');
        $this->assertFalse($this->validator->isValid());
    }

    /**
     * @expectedException \Pilou\EuVat\ServiceUnavailableException
     */
    public function testServiceUnavailable()
    {
        $this->validator->setSoapClient(new SoapClientServiceUnavailableMock)
            ->setVatId('exceptionWillBeThrown');

        $this->assertTrue($this->validator->isValid());
    }
}
