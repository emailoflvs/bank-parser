<?php

namespace App\Http\Controllers;

use App\Currency;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request)
    {

        $currency = new Currency();

//            $daily = $currency->xml_daily("http://www.cbr.ru/scripts/XML_daily.asp", 1);

        $dymanic = $currency->xml_dynamic("http://www.cbr.ru/scripts/XML_dynamic.asp",
            "02/09/2019", "12/09/2019", "R01235");


        return view('welcome');
    }


    /*
     * Get json for API
     *
     * @param $valuteID
     * @param $parsingFrom
     * @param $parsingTo
     *
     * @return array
     * */
    public function getCurrency(Request $request)
    {

        $valuteID = $request->valuteID;
        $parsingFrom = $request->parsingFrom;
        $parsingTo = $request->parsingTo;

        $rates = Currency::where('valuteID', '=', $valuteID)
            ->whereBetween('date', [$parsingFrom, $parsingTo])
            ->get();

        return response()->json(['rates' => $rates]);
    }


}
