<?php

namespace App\Http\Controllers;

use App\Services\WriteXmlService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class FusionController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected $writeXmlService; 

    public function __construct(WriteXmlService $writeXmlService) 
    {
        $this->writeXmlService = $writeXmlService;
    }

    public function myFunction()
    {
        // read csv into array and format into the required order
        $productsarray = $this->writeXmlService->parseCsv();

        //read array into xml
        $this->writeXmlService->arrayToXml($productsarray, "products.xml");

        // return dd($productsarray);
        return view('welcome');
    }

 
}
