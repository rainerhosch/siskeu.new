<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class CreateTxId
{

    function set($value, $places)
    {
        $leading = '';
        if (is_numeric($value)) {
            for ($x = 1; $x <= $places; $x++) {
                $ceiling = pow(10, $x);
                if ($value < $ceiling) {
                    $zeros = $places - $x;
                    for ($y = 1; $y <= $zeros; $y++) {
                        $leading .= "0";
                    }
                    $x = $places + 1;
                }
            }
            $output = $leading . $value;
        } else {
            $output = $value;
        }
        return $output;
    }
}
