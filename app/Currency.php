<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /*
     * Связанная с моделью таблица.
     *
     * @var string
     **/
    public $table = 'currency';

    public $url_daily = "http://www.cbr.ru/scripts/XML_daily.asp";
    public $url_dynamic = "http://www.cbr.ru/scripts/XML_dynamic.asp";

    /*
     * Добавление в базу котировок на заданный день
     *
     * @param $url
     * @param $days
     **/
    public function xml_daily($url, $days)
    {
        $day = 60 * 60 * 24;
        for ($from = (time() - ($day * $days)); $from <= time(); $from += $day) {
            $date = date("d/n/Y", $from);
            $url = $url . "?date_req=" . $date;

            // конвертирует url в объект
            $data = $this->xml_coonvert($url);

            foreach ($data->Valute as $value) {
                $insert = [
                    "valuteID" => $value->{'@attributes'}->ID,
                    "numCode" => $value->NumCode,
                    "сharCode" => $value->CharCode,
                    "name" => $value->Name,
                    "value" => $value->Value,
                    "date" => $from
                ];
                $result = Currency::insert($insert);
            }
        }
        return $result;
    }


    /*
     * Добавление в базу котировок за указанный период времени и указанную валюту
     *
     * @param $url
     * @param $days
     * */
    public function xml_dynamic($parsingFrom, $parsingTo, $valuteID)
    {
        $url = $this->url_dynamic . "?date_req1=" . $parsingFrom . "&date_req2=" . $parsingTo . "&VAL_NM_RQ=" . $valuteID;

        // конвертирует url в объект
        $data = $this->xml_coonvert($url);

        $insert = [];
        foreach ($data as $record) {

            $unixtime = mktime(0, 0, 0, $date[1], $date[0], $date[2]);

            $insert[] = [
//                "valuteID" => $record->Id,
                "numCode" => "",
                "сharCode" => "",
                "name" => "",
//                "value" => $record->Value,
                "date" => $unixtime
            ];

        }

        if (!empty ($insert))
            return Currency::insert($insert);

        return false;
    }


    /*
     * Конвертер XML, полученный  через url в объект
     *
     * @param $url
     *
     * @param $object
     **/
    public function xml_coonvert($url)
    {
        $xml = file_get_contents($url);
        $xml_data = simplexml_load_string($xml); // XML объект

        $json = json_encode($xml_data);
        $data = json_decode($json);

        return $data;
    }

}
