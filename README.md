## Getting Started
`composer require pilou/eu-vat-id-validation`

---

## Options / Functions

#### __construct()
- param: `string` $vatId *[optional]*

---
#### setVatId()
- param: `string` $vatId
- throws: `\Exception`

Sets the current VAT-ID value and extracts the VAT-Number and the country code from it.

---
#### getVatId()
- return: `string`

Gets the last set VAT-ID value.

---
#### toArray()
- return: `array`

Gets the last set VAT-ID value and all associated details:
- VAT-ID
- VAT-Number
- Country code
- Is valid?
- Company name *(not always available)*
- Company address *(not always available)*

Example:
```php
Array (
    [vatId] => IT01775560442
    [vatNumber] => 01775560442
    [countryCode] => IT
    [isValid] => 1
    [companyName] => M.A.B. SOFTWARE SRL
    [companyAddress] => C DA CAMPIGLIONE 20 63900 FERMO FM
)
```

---
#### isValid()
- return: `boolean`
- throws: '\Pilou\EuVat\ServiceUnavailableException'

---

## Usage Examples

Check if passed VAT-ID is valid:
```php
$vatId = new \Pilou\EuVat\Validation('IT01775560442');  
print_r($vatId->isValid());

// Output
true
```
---

Check multiple VAT-ID's:
```php
$vatId = new \Pilou\EuVat\Validation;

$vatId->setVatId('IT01775560442');
print_r($vatId->isValid());             // Output: true

$vatId->setVatId('XX123456789');
print_r($vatId->isValid());             // Output: false
```
---

Display all VAT-ID details:
```php
$vatId = new \Pilou\EuVat\Validation('IT01775560442');
print_r($vatId->toArray());

// Output
Array (
    [vatId] => IT01775560442
    [vatNumber] => 01775560442
    [countryCode] => IT
    [isValid] => 1
    [companyName] => M.A.B. SOFTWARE SRL
    [companyAddress] => C DA CAMPIGLIONE 20 63900 FERMO FM
)
```
---

Using all public class functions:
```php
$vatId = new \Pilou\EuVat\Validation;
$vatId->setVatId('IT01775560442');
$vatId->validate();

print_r($vatId->isValid());             // Output: true
print_r($vatId->getVatId());            // Output: 'IT01775560442'

print_r($vatId->toArray());

// Output
Array (
    [vatId] => IT01775560442
    [vatNumber] => 01775560442
    [countryCode] => IT
    [isValid] => 1
    [companyName] => M.A.B. SOFTWARE SRL
    [companyAddress] => C DA CAMPIGLIONE 20 63900 FERMO FM
)
```
---

[VIES]:http://ec.europa.eu/taxation_customs/vies/vatRequest.html
[VIES API]:http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl
[PHPUnit]:https://github.com/sebastianbergmann/phpunit





















