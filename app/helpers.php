<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/*Lakmal
*13/02/2022
*/

if(! function_exists('fld')) {
    function fld($ldate)
    {
        if ($ldate) {
            $date = explode(' ', $ldate);
            $jsd = (new Controller())->dateFormats['js_sdate'];
            $inv_date = $date[0];
            $time = $date[1];
            if ($jsd == 'dd-mm-yyyy' || $jsd == 'dd/mm/yyyy' || $jsd == 'dd.mm.yyyy') {
                $date = substr($inv_date, -4) . '-' . substr($inv_date, 3, 2) . '-' . substr($inv_date, 0, 2) . ' ' . $time;
            } elseif ($jsd == 'mm-dd-yyyy' || $jsd == 'mm/dd/yyyy' || $jsd == 'mm.dd.yyyy') {
                $date = substr($inv_date, -4) . '-' . substr($inv_date, 0, 2) . '-' . substr($inv_date, 3, 2) . ' ' . $time;
            } else {
                $date = $inv_date;
            }
            return $date;
        }
        return '0000-00-00 00:00:00';
    }
}
if(! function_exists('getDateFormat')) {
    function getDateFormat($id)
    {

        $q = DB::table('sma_date_format')->where('id', $id)->get();

        if ($q->count() > 0) {
            return $q[0];
        }
        return false;
    }
}

if(! function_exists('is_natural_no_zero')){
    function is_natural_no_zero($str)
    {
        return ($str != 0 && ctype_digit((string) $str));
    }
}

if(! function_exists('convertToBase')) {
    function convertToBase($unit, $value)
    {
        switch ($unit->operator) {
            case '*':
                return $value / $unit->operation_value;
                break;
            case '/':
                return $value * $unit->operation_value;
                break;
            case '+':
                return $value - $unit->operation_value;
                break;
            case '-':
                return $value + $unit->operation_value;
                break;
            default:
                return $value;
        }
    }
}
