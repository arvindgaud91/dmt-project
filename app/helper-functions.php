<?php

function getRandomString($length = 6) {
    $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?";
    $validCharNumber = strlen($validCharacters);

    $result = "";

    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }

    return $result;
}

function incrementalHash($len = 6){
  //@TODO: Find a better, more unqiue number generator
  
  return sprintf('%06u', mt_rand(000000, 999999));
  //return substr(md5(microtime()),rand(0,26),$len);
}
