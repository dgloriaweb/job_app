<?php

namespace app\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\ArrayToXml\ArrayToXml;

class WriteXmlService
{

    public function parseCsv()
    {

        $row = 1;
        $productsarray = [];
        if (($handle = fopen("products.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $productsarray[] = $data;
                $row++;
                for ($c = 0; $c < $num; $c++) {
                    //format data to match the required xml format

                }
            }
            fclose($handle);
        }
        return $productsarray;
    }
    public function arrayToXml($productsarray)
    {
        $result = arrayToXml::convert($productsarray);
        Storage::put('public/products.xml', $result);
    }
}
