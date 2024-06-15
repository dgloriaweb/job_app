<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class FusionController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function __construct()
    {
    }

    public function myFunction()
    {
        // return view('welcome');
        // read csv into array
        $productsarray = $this->parseCsv();
        
        return dd($productsarray);

    }

    public function parseCsv()
    {

        $row = 1;
        $productsarray = [];
        if (($handle = fopen("products.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                $productsarray[] = $data;
                // echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c = 0; $c < $num; $c++) {
                    // echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }
        return $productsarray;
    }

}
