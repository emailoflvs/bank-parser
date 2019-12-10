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
        return view('welcome', [
        ]);

    }

    /*
     * Импортирует котировки, согласно указанной дате и валюте
     *
     * Тестовая валюта - "R01235"; (доллар USA)
     * */
    public function xml_dynamic(Request $request)
    {
        if (isset($request->parsingFrom) && isset($request->parsingTo) && isset($request->valuteId) {

            $parsingFrom = $request->parsingFrom;
            $parsingTo = $request->parsingTo;
            $valuteId = $request->valuteId;

            $currency = new Currency();

            /* XML_dynamic динамики котировок выбранной валюты*/
            $data = $currency->xml_dynamic(
                $parsingFrom,
                $parsingTo,
                $valuteId
            );

            if ($data)
                return view('welcome', [
                    'parsingFrom' => $parsingFrom,
                    'parsingTo' => $parsingTo,
                    'valuteId' => $valuteId,
                    'parsingError' => ''
                ]);

            return view('welcome', [
                'parsingError' => 'Ошибка при импорте котировок'
            ]);
        }

        return view('welcome', [
            'parsingError' => 'Ошибка при импорте котировок'
        ]);

    }

    /*
     * Импортирует котировки, согласно указанному количеству прошедших дней
     * */
    public function xml_daily(Request $request)
    {
        $currency = new Currency();

        if (isset($request->days)) {
            $days = $request->days;

            /* XML_daily котировои на указанное количество дней*/
            $currency->xml_daily($request->days);

            return view('welcome', [
                'days' => $days,
            ]);
        }

        return view('welcome', [
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
    public function show_table(Request $request)
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
