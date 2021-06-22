<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Terbilang
{

    function satuan($inp)
    {
        if ($inp == 1) {
            return "Satu ";
        } else if ($inp == 2) {
            return "Dua ";
        } else if ($inp == 3) {
            return "Tiga ";
        } else if ($inp == 4) {
            return "Empat ";
        } else if ($inp == 5) {
            return "Lima ";
        } else if ($inp == 6) {
            return "Enam ";
        } else if ($inp == 7) {
            return "Tujuh ";
        } else if ($inp == 8) {
            return "Delapan ";
        } else if ($inp == 9) {
            return "Sembilan ";
        } else {
            return "";
        }
    }

    function belasan($inp)
    {
        $proses = $inp; //substr($inp, -1);
        if ($proses == '11') {
            return "Sebelas ";
        } else {
            $proses = substr($proses, 1, 1);
            return satuan($proses) . "Belas ";
        }
    }

    function puluhan($inp)
    {
        $proses = $inp; //substr($inp, 0, -1);
        if ($proses == 1) {
            return "Sepuluh ";
        } else if ($proses == 0) {
            return '';
        } else {
            return satuan($proses) . "Puluh ";
        }
    }

    function ratusan($inp)
    {
        $proses = $inp; //substr($inp, 0, -2);
        if ($proses == 1) {
            return "Seratus ";
        } else if ($proses == 0) {
            return '';
        } else {
            return satuan($proses) . "Ratus ";
        }
    }

    function ribuan($inp)
    {
        $proses = $inp; //substr($inp, 0, -3);
        if ($proses == 1) {
            return "Seribu ";
        } else if ($proses == 0) {
            return '';
        } else {
            return satuan($proses) . "Ribu ";
        }
    }

    function jutaan($inp)
    {
        $proses = $inp; //substr($inp, 0, -6);
        if ($proses == 0) {
            return '';
        } else {
            return satuan($proses) . "Juta ";
        }
    }

    function milyaran($inp)
    {
        $proses = $inp; //substr($inp, 0, -9);
        if ($proses == 0) {
            return '';
        } else {
            return satuan($proses) . "Milyar ";
        }
    }

    function bilang($rp)
    {
        $kata = "";
        $rp = trim($rp);
        if (strlen($rp) >= 10) {
            $angka = substr($rp, strlen($rp) - 10, -9);
            $kata = $kata . milyaran($angka);
        }
        $tambahan = "";
        if (strlen($rp) >= 9) {
            $angka = substr($rp, strlen($rp) - 9, -8);
            $kata = $kata . ratusan($angka);
            if ($angka > 0) {
                $tambahan = "Juta ";
            }
        }
        if (strlen($rp) >= 8) {
            $angka = substr($rp, strlen($rp) - 8, -7);
            $angka1 = substr($rp, strlen($rp) - 7, -6);
            if (($angka == 1) && ($angka1 > 0)) {
                $angka = substr($rp, strlen($rp) - 8, -6);
                //echo " belasan".($angka)." ";
                $kata = $kata . belasan($angka) . "Juta ";
            } else {
                $angka = substr($rp, strlen($rp) - 8, -7);
                //echo " puluhan".($angka)." ";
                $kata = $kata . puluhan($angka);
                if ($angka > 0) {
                    $tambahan = "Juta ";
                }

                $angka = substr($rp, strlen($rp) - 7, -6);
                //echo " ribuan".($angka)." ";
                $kata = $kata . ribuan($angka);
                if ($angka == 0) {
                    $kata = $kata . $tambahan;
                }
            }
        }
        if (strlen($rp) == 7) {
            $angka = substr($rp, strlen($rp) - 7, -6);
            $kata = $kata . jutaan($angka);
            if ($angka == 0) {
                $kata = $kata . $tambahan;
            }
        }
        $tambahan = "";
        if (strlen($rp) >= 6) {
            $angka = substr($rp, strlen($rp) - 6, -5);
            $kata = $kata . ratusan($angka);
            if ($angka > 0) {
                $tambahan = "Ribu ";
            }
        }
        if (strlen($rp) >= 5) {
            $angka = substr($rp, strlen($rp) - 5, -4);
            $angka1 = substr($rp, strlen($rp) - 4, -3);
            if (($angka == 1) && ($angka1 > 0)) {
                $angka = substr($rp, strlen($rp) - 5, -3);
                //echo " belasan".($angka)." ";
                $kata = $kata . belasan($angka) . "Ribu ";
            } else {
                $angka = substr($rp, strlen($rp) - 5, -4);
                //echo " puluhan".($angka)." ";
                $kata = $kata . puluhan($angka);
                if ($angka > 0) {
                    $tambahan = "Ribu ";
                }

                $angka = substr($rp, strlen($rp) - 4, -3);
                //echo " ribuan".($angka)." ";
                $kata = $kata . ribuan($angka);
                if ($angka == 0) {
                    $kata = $kata . $tambahan;
                }
            }
        }
        if (strlen($rp) == 4) {
            $angka = substr($rp, strlen($rp) - 4, -3);
            //echo " ribuan".($angka)." ";
            $kata = $kata . ribuan($angka);
            if ($angka == 0) {
                $kata = $kata . $tambahan;
            }
        }
        if (strlen($rp) >= 3) {
            $angka = substr($rp, strlen($rp) - 3, -2);
            //echo " ratusan".($angka)." ";
            $kata = $kata . ratusan($angka);
        }
        if (strlen($rp) >= 2) {
            $angka = substr($rp, strlen($rp) - 2, -1);
            $angka1 = substr($rp, strlen($rp) - 1);
            if (($angka == 1) && ($angka1 > 0)) {
                $angka = substr($rp, strlen($rp) - 2);
                //echo " belasan".($angka)." ";
                $kata = $kata . belasan($angka);
            } else {
                //echo " puluhan".($angka)." ";
                $kata = $kata . puluhan($angka);

                $angka = substr($rp, strlen($rp) - 1);
                //echo " satuan".($angka)." ";
                $kata = $kata . satuan($angka);
            }
        }
        if (strlen($rp) == 1) {
            $angka = substr($rp, strlen($rp) - 1);
            //echo " satuan".($angka)." ";
            $kata = "$kata" . satuan($angka);
        }
        return $kata;
    }
}
