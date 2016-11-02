<?php

namespace Pilou\EuVat;

/**
 *  European VAT-ID validation (VIES VAT-ID)
 *  URL: http://ec.europa.eu/taxation_customs/vies
 * 
 *  If company name and address are available, these also get extracted
 *  and are accessible after valdiation via EuVatValidation::getVatIdExtended()
 * 
 * 
 *  Example 1:
 *  ---------------------------------
 *  $vatId = new EuVatValidation('DE273616207');
 *  print_r($vatId->isValid());
 * 
 * 
 *  Example 2 (Recommended to validate many VAT-ID's with one class instance):
 *  ---------------------------------
 *  $vatId = new EuVatValidation;
 * 
 *  $vatId->setVatId('DE273616207');
 *  print_r($vatId->isValid());
 * 
 *  $vatId->setVatId('AU374651267');
 *  print_r($vatId->isValid());
 * 
 * 
 *  Example 3 (long):
 *  ---------------------------------
 *  $vatId = new EuVatValidation;
 *  $vatId->setVatId('DE273616207');
 *  $vatId->validate();
 *  print_r($vatId->getVatIdExtended());
 */
use SoapFault;

class Validation
{
    /**
     * @var SoapClient 
     */
    private $soapClient;

    /**
     * @var type 
     */
    private $vatId;


    /**
     * Constructor
     * If $vatId isn't empty, the validation gets triggered right away
     * 
     * @param string $vatId
     * @throws SoapFault
     */
    public function __construct($vatId = null)
    {
        if ($vatId) {
            $this->setVatId($vatId);
        }
    }
    
    public function getSoapClient()
    {
        if ($this->soapClient === null) {
            $this->setSoapClient(new SoapClient);
        }
        return $this->soapClient;
    }

    public function setSoapClient(SoapClient $soapClient)
    {
        $this->soapClient = $soapClient;
        return $this;
    }

    /**
     * Set VAT-ID and extract VAT-Number and country code
     * 
     * @param string $vatId
     * @return self
     * @throws Exception
     */
    public function setVatId($vatId)
    {
        $cleanedVatId = $this->getCleanedVatId($vatId);

        if (empty($cleanedVatId)) {
            throw new \Exception('VAT-ID cannot be empty');
        }

        $this->resetVatId();
        $this->vatId['vatId']       = $cleanedVatId;
        $this->vatId['vatNumber']   = $this->getVatNumber();
        $this->vatId['countryCode'] = $this->getCountryCode();
        
        return $this;
    }

    /**
     * Get current VAT-ID value
     * 
     * @return string
     */
    public function getVatId()
    {
        return $this->vatId['vatId'];
    }

    /**
     * Extract the VAT number from the current VAT-ID
     * 
     * @return string
     */
    public function getVatNumber()
    {
        return isset($this->vatId['vatId']) ? substr($this->vatId['vatId'], 2) : null;
    }

    /**
     * Extract the country code from the current VAT-ID
     * 
     * @return string
     */
    public function getCountryCode()
    {
        return isset($this->vatId['vatId']) ? substr($this->vatId['vatId'], 0, 2) : null;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return isset($this->vatId['companyName']) ? $this->vatId['companyName'] : null;
    }

    /**
     * @return string
     */
    public function getCompanyAddress()
    {
        return isset($this->vatId['companyAddress']) ? $this->vatId['companyAddress'] : null;
    }

    /**
     * Get current VAT-ID value and details
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->vatId;
    }
    

    /**
     * Get the current value of $this->vatId['isValid'].
     * If $this->vatId['isValid'] is still NULL, the current VAT-ID gets validated before checking $this->vatId['isValid']
     * 
     * @return boolean
     */
    public function isValid()
    {
        if ($this->vatId['isValid'] === null) {
            $this->validate();
        }
        return $this->vatId['isValid'];
    }

    /**
     * Clean a string removing spaces, comas, etc.
     * 
     * @param string $vatId
     * @return string
     */
    public function getCleanedVatId($vatId)
    {
        return strtoupper(str_replace(array(' ', '-', '.', ','), '', trim($vatId)));
    }

    /**
     * Validates the current VAT-ID value.
     * If available, company name and address are also set
     */
    private function validate()
    {
        try {
            $response = $this->sendValidationRequest();

            $this->vatId['isValid'] = $response->valid;
            $this->vatId['companyName'] = $response->name !== '---' ? $response->name : null;
            $this->vatId['companyAddress'] = $response->address !== '---' ? $response->address : null;
        } catch (SoapFault $ex) {
            if ($ex->getMessage() === 'INVALID_INPUT') {
                $this->vatId['isValid'] = false;
                $this->vatId['companyName'] = null;
                $this->vatId['companyAddress'] = null;
                return;
            }
            throw new ServiceUnavailableException($ex->getMessage(), $ex->getCode(), $ex);
        }
    }

    /**
     * Reset $vatId with its default values
     */
    private function resetVatId()
    {
        $this->vatId = array(
            'vatId'           => null,
            'vatNumber'       => null,
            'countryCode'     => null,
            'isValid'         => null,
            'companyName'     => null,
            'companyAddress'  => null
        );
    }

    /**
     * Sead a SOAP request to validate the current VAT-ID
     * (WSDL description: http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl)
     * 
     * @return object
     * @throws SoapFault
     */
    private function sendValidationRequest()
    {
        $response = $this->getSoapClient()->checkVat(array(
            'vatNumber'   => $this->vatId['vatNumber'],
            'countryCode' => $this->vatId['countryCode']
        ));
        return $response;
    }
}
