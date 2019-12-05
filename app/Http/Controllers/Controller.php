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

        $days = 1;
        /* XML_daily котировои на указанное количество дней*/
        $daily = $currency->xml_daily($currency->url_daily, $days);


        $parsingFrom = "11/09/2019";
        $parsingTo = "12/09/2019";
        $valuteId = "R01235";

        /* XML_dynamic динамики котировок выбранной валюты*/
        $dymanic = $currency->xml_dynamic(
            $parsingFrom,
            $parsingTo,
            $valuteId
        );

        /*
        * Блок для выбора дня для котировки
        * */
        if (!empty($request->parsingDate)) {

            $parsingDate = $request->parsingDate;

            $currency = new Currency();
            $table = $currency->getParsingTable($parsingDate);

            return view('welcome', [
                'table' => $table,
                'date' => $parsingDate,
                'days' => $days,
                'parsingFrom' => $parsingFrom,
                'parsingTo' => $parsingTo,
                'valuteId' => $valuteId
            ]);
        }

        return view('welcome', [
            'days' => $days,
            'parsingFrom' => $parsingFrom,
            'parsingTo' => $parsingTo,
            'valuteId' => $valuteId
        ]);
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
        $currency = new Currency();

        return response()->json(['rates' => $currency->getCurrency($request)]);
    }

    /*
     * Выводит таблицу со списком валют и данными по этим валютам за указанную в поле/селекторе дату.
     *
     * @param $parsingDate
     *
     * */
    public function showTable(Request $request)
    {

        if (!empty($request->parsingDate)) {

            $parsingDate = $request->parsingDate;

            $currency = new Currency();
            $table = $currency->getParsingTable($parsingDate);

            return view('welcome', [
                'table' => $table,
                'date' => $parsingDate
            ]);
        }

        return view('welcome', ['table' => [], 'date' => '']);
    }


}
