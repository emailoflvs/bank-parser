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

        /* XML_daily котировои на указанное количество дней*/
        $daily = $currency->xml_daily($currency->url_daily, 1);

        /* XML_dynamic динамики котировок выбранной валюты*/
//        $dymanic = $currency->xml_dynamic(
//            "02/09/2019",
//            "12/09/2019",
//            "R01235"
//        );

        return view('welcome');
    }


    /*
     * REST API метод, который возвращает курс(ы)в json валюты для переданного valueID за
     * указанный период date (from&to) используя данные из таблицы currency.
     * Параметры передаем методом GET.
     *
     * @param $valuteID
     * @param $parsingFrom
     * @param $parsingTo
     *
     * @return array
     * */
    public function getCurrency(Request $request)
    {
        /* Здесь нужно поставить проверку передаваемых значений*/
        $valuteID = $request->valuteID;
        $parsingFrom = $request->parsingFrom;
        $parsingTo = $request->parsingTo;

        $rates = Currency::where('valuteID', '=', $valuteID)
            ->whereBetween('date', [$parsingFrom, $parsingTo])
            ->get();

        return response()->json(['rates' => $rates]);
    }

    /*
     * Выводит таблицу со списком валют и данными по этим валютам за указанную в поле/селекторе дату.
     *
     * @param $parsingDate
     *
     * */
    public function showTable(Request $request)
    {
        $parsingDate = explode("-", $request->parsingDate);
        $parsingDate = $parsingDate[2] . "/" . $parsingDate[1] . "/" . $parsingDate[0];

        // Url для получения котировки
        $url = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=" . $parsingDate;

        $xml = file_get_contents($url);
        $xml_data = simplexml_load_string($xml); // XML объект

        // Возвращает объект из XML
        $data = json_decode(json_encode($xml_data));

        $table = [];
        foreach ($data->Valute as $value) {
            $table[] = [
                "valuteID" => $value->{'@attributes'}->ID,
                "name" => $value->Name,
                "value" => $value->Value,
            ];
        }
        return view('welcome', [
            'table' => $table,
            'date' => $parsingDate
        ]);
    }


}
