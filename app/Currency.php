<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Currency extends Model
{
    /*
     * Связанная с моделью таблица.
     *
     * @var string
     **/
    protected $table = 'currency';

    public $url_daily = "http://www.cbr.ru/scripts/XML_daily.asp?";
    public $url_dynamic = "http://www.cbr.ru/scripts/XML_dynamic.asp?";

    /*
     * Добавление в базу котировок на заданный день
     *
     * @param $days
     **/
    public function xml_daily($days)
    {
        $day = 60 * 60 * 24;
        for ($from = (time() - ($day * $days)); $from <= time(); $from += $day) {
            $date = date("d/n/Y", $from);
            $url = $this->url_daily . "date_req=" . $date;

            // конвертирует url в объект
            $data = $this->xml_convert($url);

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
     * @param $parsingFrom
     * @param $parsingTo
     * @param $valuteID
     * */
    public function xml_dynamic($parsingFrom, $parsingTo, $valuteID)
    {
        $parsingFrom = explode("-", $parsingFrom);
        $parsingFrom = $parsingFrom[2] . "/" . $parsingFrom[1] . "/" . $parsingFrom[0];

        $parsingTo = explode("-", $parsingTo);
        $parsingTo = $parsingTo[2] . "/" . $parsingTo[1] . "/" . $parsingTo[0];

        $url = $this->url_dynamic . "date_req1=" . $parsingFrom . "&date_req2=" . $parsingTo . "&VAL_NM_RQ=" . $valuteID;

        // конвертирует url в объект
        $data = $this->xml_convert($url);

        if (isset($data->Record)) {
            $insert = [];
            foreach ($data->Record as $record) {

                if (isset($record->{'@attributes'})) {
                    $date = explode(".", $record->{'@attributes'}->Date);
                    $unixtime = mktime(0, 0, 0, $date[1], $date[0], $date[2]);

                    $insert[] = [
                        "valuteID" => $record->{'@attributes'}->Id,
                        "numCode" => "",
                        "сharCode" => "",
                        "name" => "",
                        "value" => $record->Value,
                        "date" => $unixtime
                    ];
                }
            }

            if (!empty ($insert))
                return Currency::insert($insert);
        }
        return false;
    }


    /*
     * Возвращает таблицу со списком валют и данными по этим валютам за указанную вдату.
     *
     * @param $parsingDate
     * @result array
     * */
    public function getParsingTable($parsingDate)
    {
        $parsingDate = explode("-", $parsingDate);
        $parsingDate = $parsingDate[2] . "/" . $parsingDate[1] . "/" . $parsingDate[0];

        // Url для получения котировки
        $url = $this->url_daily . "date_req=" . $parsingDate;

        // конвертирует url в объект
        $data = $this->xml_convert($url);

        $table = [];
        foreach ($data->Valute as $value) {
            $table[] = [
                "valuteID" => $value->{'@attributes'}->ID,
                "name" => $value->Name,
                "value" => $value->Value,
            ];
        }
        return $table;
    }


    /*
     * Конвертер XML, полученный  через url в объект
     *
     * @param string $url
     *
     * @result $object
     **/
    public function xml_convert($url)
    {
        $xml = file_get_contents($url);
        $xml_data = simplexml_load_string($xml); // XML объект

        $json = json_encode($xml_data);
        $data = json_decode($json);

        return $data;
    }

}
