<?php
namespace Acme\ISO8583;

/**
 *
 */
class ISO8583
{
  const FIXED_LENGTH = true;
  const VARIABLE_LENGTH = false;

  private $_DATA_ELEMENTS = [
    1   => ['b',   16,  self::FIXED_LENGTH],           //Bit Map Extended
    2   => ['an',  19,  self::VARIABLE_LENGTH],        //Primary account number (PAN
    3   => ['n',   6,   self::FIXED_LENGTH],           //Precessing code
    4   => ['n',   12,  self::FIXED_LENGTH],           //Amount transaction
    5   => ['n',   12,  self::FIXED_LENGTH],           //Amount reconciliation
    6   => ['n',   12,  self::FIXED_LENGTH],           //Amount cardholder billing
    7   => ['an',  10,  self::FIXED_LENGTH],           //Date and time transmission
    8   => ['n',   8,   self::FIXED_LENGTH],           //Amount cardholder billing fee
    9   => ['n',   8,   self::FIXED_LENGTH],           //Conversion rate reconciliation
    10  => ['n',   8,   self::FIXED_LENGTH],           //Conversion rate cardholder billing
    11  => ['n',   6,   self::FIXED_LENGTH],           //Systems trace audit number
    12  => ['n',   6,   self::FIXED_LENGTH],           //Date and time local transaction
    13  => ['n',   4,   self::FIXED_LENGTH],           //Date effective
    14  => ['n',   4,   self::FIXED_LENGTH],           //Date expiration
    15  => ['n',   4,   self::FIXED_LENGTH],           //Date settlement
    16  => ['n',   4,   self::FIXED_LENGTH],           //Date conversion
    17  => ['n',   4,   self::FIXED_LENGTH],           //Date capture
    18  => ['n',   4,   self::FIXED_LENGTH],           //Message error indicator
    19  => ['n',   3,   self::FIXED_LENGTH],           //Country code acquiring institution
    20  => ['n',   3,   self::FIXED_LENGTH],           //Country code primary account number (PAN
    21  => ['n',   3,   self::FIXED_LENGTH],           //Transaction life cycle identification data
    22  => ['n',   3,   self::FIXED_LENGTH],           //Point of service data code
    23  => ['n',   3,   self::FIXED_LENGTH],           //Card sequence number
    24  => ['n',   3,   self::FIXED_LENGTH],           //Function code
    25  => ['n',   2,   self::FIXED_LENGTH],           //Message reason code
    26  => ['n',   2,   self::FIXED_LENGTH],           //Merchant category code
    27  => ['n',   1,   self::FIXED_LENGTH],           //Point of service capability
    28  => ['n',   9,   self::FIXED_LENGTH],           //Date reconciliation
    29  => ['an',  9,   self::FIXED_LENGTH],           //Reconciliation indicator
    30  => ['n',   9,   self::FIXED_LENGTH],           //Amounts original
    31  => ['an',  9,   self::FIXED_LENGTH],           //Acquirer reference number
    32  => ['n',   11,  self::VARIABLE_LENGTH],        //Acquiring institution identification code
    33  => ['n',   11,  self::VARIABLE_LENGTH],        //Forwarding institution identification code
    34  => ['an',  28,  self::VARIABLE_LENGTH],        //Electronic commerce data
    35  => ['z',   37,  self::VARIABLE_LENGTH],        //Track 2 data
    36  => ['n',   104, self::VARIABLE_LENGTH],        //Track 3 data
    37  => ['an',  12,  self::FIXED_LENGTH],           //Retrieval reference number
    38  => ['an',  6,   self::FIXED_LENGTH],           //Approval code
    39  => ['an',  2,   self::FIXED_LENGTH],           //Action code
    40  => ['an',  3,   self::FIXED_LENGTH],           //Service code
    41  => ['ans', 8,   self::FIXED_LENGTH],           //Card acceptor terminal identification
    42  => ['ans', 15,  self::FIXED_LENGTH],           //Card acceptor identification code
    43  => ['ans', 40,  self::FIXED_LENGTH],           //Card acceptor name/location
    44  => ['an',  25,  self::VARIABLE_LENGTH],        //Additional response data
    45  => ['an',  76,  self::VARIABLE_LENGTH],        //Track 1 data
    46  => ['an',  999, self::VARIABLE_LENGTH],        //Amounts fees
    47  => ['an',  999, self::VARIABLE_LENGTH],        //Additional data national
    48  => ['ans', 999, self::VARIABLE_LENGTH],        //Additional data private
    49  => ['an',  3,   self::FIXED_LENGTH],           //Verification data
    50  => ['an',  3,   self::FIXED_LENGTH],           //Currency code, settlement
    51  => ['a',   3,   self::FIXED_LENGTH],           //Currency code, cardholder billing
    52  => ['an',  16,  self::FIXED_LENGTH],           //Personal identification number (PIN) data
    53  => ['an',  16,  self::FIXED_LENGTH],           //Security related control information
    54  => ['an',  120, self::VARIABLE_LENGTH],        //Amounts additional
    55  => ['ans', 999, self::VARIABLE_LENGTH],        //Integrated circuit card (ICC) system related data
    56  => ['ans', 999, self::VARIABLE_LENGTH],        //Original data elements
    57  => ['ans', 999, self::VARIABLE_LENGTH],        //Authorisation life cycle code
    58  => ['ans', 999, self::VARIABLE_LENGTH],        //Authorising agent institution identification code
    59  => ['ans', 999, self::VARIABLE_LENGTH],        //Transport data
    60  => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for national use
    61  => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for national use
    62  => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for private use
    63  => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for private use
    64  => ['b',   16,   self::FIXED_LENGTH],          //Message authentication code (MAC) field
    65  => ['b',   1,   self::FIXED_LENGTH],           //Bitmap tertiary
    66  => ['n',   1,   self::FIXED_LENGTH],           //Settlement code
    67  => ['n',   2,   self::FIXED_LENGTH],           //Extended payment data
    68  => ['n',   3,   self::FIXED_LENGTH],           //Receiving institution country code
    69  => ['n',   3,   self::FIXED_LENGTH],           //Settlement institution county code
    70  => ['n',   3,   self::FIXED_LENGTH],           //Network management Information code
    71  => ['n',   4,   self::FIXED_LENGTH],           //Message number
    72  => ['ans', 4,   self::VARIABLE_LENGTH],        //Data record
    73  => ['n',   6,   self::FIXED_LENGTH],           //Date action
    74  => ['n',   10,  self::FIXED_LENGTH],           //Credits, number
    75  => ['n',   10,  self::FIXED_LENGTH],           //Credits, reversal number
    76  => ['n',   10,  self::FIXED_LENGTH],           //Debits, number
    77  => ['n',   10,  self::FIXED_LENGTH],           //Debits, reversal number
    78  => ['n',   10,  self::FIXED_LENGTH],           //Transfer number
    79  => ['n',   10,  self::FIXED_LENGTH],           //Transfer, reversal number
    80  => ['n',   10,  self::FIXED_LENGTH],           //Inquiries number
    81  => ['n',   10,  self::FIXED_LENGTH],           //Authorizations, number
    82  => ['n',   12,  self::FIXED_LENGTH],           //Credits, processing fee amount
    83  => ['n',   12,  self::FIXED_LENGTH],           //Credits, transaction fee amount
    84  => ['n',   12,  self::FIXED_LENGTH],           //Debits, processing fee amount
    85  => ['n',   12,  self::FIXED_LENGTH],           //Debits, transaction fee amount
    86  => ['n',   16,  self::FIXED_LENGTH],           //Credits, amount
    87  => ['an',  16,  self::FIXED_LENGTH],           //Credits, reversal amount
    88  => ['n',   16,  self::FIXED_LENGTH],           //Debits, amount
    89  => ['n',   16,  self::FIXED_LENGTH],           //Debits, reversal amount
    90  => ['an',  42,  self::FIXED_LENGTH],           //Original data elements
    91  => ['an',  1,   self::FIXED_LENGTH],           //File update code
    92  => ['n',   2,   self::FIXED_LENGTH],           //File security code
    93  => ['n',   6,   self::FIXED_LENGTH],           //Response indicator
    94  => ['an',  7,   self::FIXED_LENGTH],           //Service indicator
    95  => ['an',  42,  self::FIXED_LENGTH],           //Replacement amounts
    96  => ['an',  16,  self::FIXED_LENGTH],           //Message security code
    97  => ['an',  17,  self::FIXED_LENGTH],           //Amount, net settlement
    98  => ['ans', 25,  self::FIXED_LENGTH],           //Payee
    99  => ['n',   11,  self::VARIABLE_LENGTH],        //Settlement institution identification code
    100 => ['n',   11,  self::VARIABLE_LENGTH],        //Receiving institution identification code
    101 => ['ans', 99,  self::VARIABLE_LENGTH],        //File name
    102 => ['ans', 28,  self::VARIABLE_LENGTH],        //Account identification 1
    103 => ['ans', 28,  self::VARIABLE_LENGTH],        //Account identification 2
    104 => ['an',  100, self::VARIABLE_LENGTH],        //Transaction description
    105 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for ISO use
    106 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for ISO use
    107 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for ISO use
    108 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for ISO use
    109 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for ISO use
    110 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for ISO use
    111 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for private use
    112 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for private use
    113 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for private use
    114 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for national use
    115 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for national use
    116 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for national use
    117 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for national use
    118 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for national use
    119 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for national use
    120 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for private use
    121 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for private use
    122 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for national use
    123 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for private use
    124 => ['ans', 999, self::VARIABLE_LENGTH],        //Info Text
    125 => ['ans', 999, self::VARIABLE_LENGTH],        //Network management information
    126 => ['ans', 999, self::VARIABLE_LENGTH],        //Issuer trace id
    127 => ['ans', 999, self::VARIABLE_LENGTH],        //Reserved for private use
    128 => ['b',   16,  self::FIXED_LENGTH]            //Message authentication code (MAC) field
  ];

  public function create ($params)
  {
      $bitmap = $this->calculateBitmap($params['elements']);
      $data = $this->concatDataElements($params['elements']);
      return $params['mti'].$bitmap.$data;
  }

  public function parse ($iso)
  {
    $dataElements = $this->parseBitmap($iso);
    // return $this->parseDataElement($dataElements, 1, substr($iso, 20, strlen($iso)-20));
    return array_filter($this->parseDataElement($dataElements, 1, substr($iso, 20, strlen($iso)-20)));
  }

  private function calculateBitmap ($elements)
  {
    $primary = sprintf("%064d", 0);
    $secondary = sprintf("%064d", 0);
    $primaryBitmap = '';
    $secondaryBitmap = '';

    foreach ($elements as $key => $value)
    {
      if ($key < 65) $primary[$key-1] = 1;
      else if ($key > 64)
      {
        $primary[0] = 1;
        $secondary[$key-1-64] = 1;
      }
    }

    for ($i = 0; $i<64; $i+=4)
    {
      $primaryBitmap .= base_convert(substr($primary, $i, 4), 2, 16);
    }

    if ($primary[0] == 1)
    {
      for ($i = 0; $i < 64; $i += 4)
      {
        $secondaryBitmap .=  base_convert(substr($secondary, $i, 4), 2, 16);
      }
    }
    return strtoupper($primaryBitmap.$secondaryBitmap);
  }

  private function concatDataElements ($elements)
  {
    ksort($elements);
    $data = '';
    foreach ($elements as $element)
    {
      $data .= $element;
    }
    return $data;
  }

  private function parseBitmap ($iso)
  {
    $data = [];
    $binaryPrimaryBitmap = implode("", array_map([$this, 'bitmapHexToBin'], str_split(substr($iso, 4 , 16), 1)));
    $binarySecondaryBitmap = $binaryPrimaryBitmap[0] == 1 ? implode("", array_map([$this, 'bitmapHexToBin'], str_split(substr($iso, 20 , 16), 1))) : '';
    $binaryBitmap = $binaryPrimaryBitmap.$binarySecondaryBitmap;
    // dd($binaryBitmap);
    return array_combine(range(1, 128), array_map(function ($bit) {
      return $bit == 1 ? true : false;
    }, str_split($binaryBitmap, 1)));
  }

  private function parseDataElement ($dataElements, $key, $iso)
  {
    $oldKey = $key;
    if ($this->_DATA_ELEMENTS[$key][0] == 'b')
    {
      $dataElements[$key] = implode("", array_map([$this, 'bitmapHexToBin'], str_split(substr($iso, 0, $this->_DATA_ELEMENTS[$key][1]), 1)));
      while (! $dataElements[++$key]) {}
      return $this->parseDataElement($dataElements, $key, substr($iso, $this->_DATA_ELEMENTS[$oldKey][1], strlen($iso)-$this->_DATA_ELEMENTS[$oldKey][1]));
    }

    if ($this->_DATA_ELEMENTS[$key][0] == 'n')
    {
      // dd($iso);
      $dataElements[$key] = $this->_DATA_ELEMENTS[$key][2] ? substr($iso, 0, $this->_DATA_ELEMENTS[$key][1]) : substr($iso, strlen($this->_DATA_ELEMENTS[$key][1]), (integer) substr($iso, 0, strlen($this->_DATA_ELEMENTS[$key][1])));
      while (! $dataElements[++$key]) {}
      return $this->parseDataElement($dataElements, $key, substr($iso, $this->_DATA_ELEMENTS[$oldKey][1], strlen($iso)-$this->_DATA_ELEMENTS[$oldKey][1]));
    }
    // if ($key == 49) dd($iso);
    $dataElements[$key] = $this->_DATA_ELEMENTS[$key][2] ? substr($iso, 0, $this->_DATA_ELEMENTS[$key][1]) : substr($iso, strlen($this->_DATA_ELEMENTS[$key][1]), (integer) substr($iso, 0, strlen($this->_DATA_ELEMENTS[$key][1])));
    while (! $dataElements[++$key]) {
      if ($key == 128)
        return $dataElements;
    }
    $elementLength = $this->_DATA_ELEMENTS[$oldKey][2] ? $this->_DATA_ELEMENTS[$oldKey][1] : (integer) substr($iso, 0, strlen($this->_DATA_ELEMENTS[$oldKey][1])) + strlen($this->_DATA_ELEMENTS[$oldKey][1]);
    return $key == 128 ? $dataElements : $this->parseDataElement($dataElements, $key, substr($iso, $elementLength, strlen($iso)-$elementLength));
  }

  private function bitmapHexToBin ($bit)
  {
    return sprintf("%04u", base_convert($bit, 16, 2));
  }












}
