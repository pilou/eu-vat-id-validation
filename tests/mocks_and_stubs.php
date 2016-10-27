<?php
require_once dirname(__FILE__) . '/../src/Pilou/EuVatValidation.php';

use Pilou\EuVatValidation;

class EuVatValidationWrapper extends EuVatValidation {

  public function __construct($vatId = null) {

    if (!empty($vatId)) {
      parent::__construct($vatId);
    }
  }
}


class ViesSoapClient {
  
  public function checkVat() {

    return 'Nothing to return';
  }
}