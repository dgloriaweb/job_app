<?php

namespace app\Services;

use Illuminate\Support\Facades\Storage;
use PHPUnit\Event\Code\Test;
use Spatie\ArrayToXml\ArrayToXml;

class WriteXmlService
{

    public function parseCsv()
    {
        /* array should look like this:

        $testarray =
            [
                "product" => [
                    [
                        '_attributes' =>
                        [
                            'product_id' => ''
                        ],
                        "display-name" =>
                        [
                            '_attributes' => ['_xml:lang' => ''],
                            "__text" => ""
                        ],
                        "brand" => "",
                        "variations" => [
                            "variants" => [
                                "variant"   => [
                                    [
                                        "_product-id" => "",
                                        "_default" => ""
                                    ],
                                    [
                                        "_product-id" => ""
                                    ]
                                ]
                            ],
                        ],
                        "custom-attributes" =>
                        [
                            "custom-attribute" =>
                            [
                                [
                                    "_attribute-id" => "",
                                    "__text" => "",
                                ],
                                [
                                    "_attribute-id" => "",
                                    "__text" => "",
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            */
        $productsarray = [];
        $existingProductKey = 0;
        if (($handle = fopen("products.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                //if product id already exists, then modify build 
                $prodId = $data[0];

                $brand = $data[1];
                $displayName = $data[2];
                $variantProductId = $data[3];
                $variantCustomAttrColor = $data[4];
                $variantCustomAttrSize = $data[5];
                $variantDefault = $data[6];
                //variants should separately appear under the product section ??? how?
                //if product already exists, then add the variants only, but can't add [0] as
                $existingProductKey = array_search($prodId, array_column($productsarray, "product_id")); // this doesn't find it. 
                if ($existingProductKey === false) {

                    $productsarray["product"]["_attributes"]["product_id"] = $prodId;
                    $productsarray["product"]["display-name"]["_value"] = $displayName;
                    $productsarray["product"]["display-name"]["_attributes"]["_xml:lang"] = "x-default";
                    $productsarray["product"]["brand"] = $brand;
                    // if has more variants, then append
                    $productsarray["product"]["variations"]["variants"]["variant"]["_attributes"]["_product-id"] = $variantProductId;
                    if ($variantDefault == "Y") {
                        $productsarray["product"]["variations"]["variants"]["variant"]["_attributes"]["default"] = "true";
                    }
                } else {    
                    $productsarray[$existingProductKey]["product"]["variations"]["variants"]["variant"]["_attributes"]["_product-id"] = $variantProductId;
                    if ($variantDefault == "Y") {
                        $productsarray[$existingProductKey]["product"]["variations"]["variants"]["variant"]["_attributes"]["default"] = "true";
                    }
                }



                $productsarray["product"]["custom-attributes"]["custom-attribute"]["_attribute-id"] = "color";
                $productsarray["product"]["custom-attributes"]["custom-attribute"]["__text"] = $variantCustomAttrColor;

                // if has custom attributes then append
                $productsarray["product"]["custom-attributes"]["custom-attribute"]["_attribute-id"] = "size";
                $productsarray["product"]["custom-attributes"]["custom-attribute"]["__text"] = $variantCustomAttrSize;

                // should have done an object instead...


            }
            fclose($handle);
        }
        // dd($productsarray);
        return $productsarray;
    }
    public function arrayToXml($productsarray, $filename)
    {
        $result = ArrayToXml::convert($productsarray, [
            'rootElementName' => 'catalog',
            '_attributes' => [
                'xmlns' => 'http://www.demandware.com/xml/impex/catalog/2006-10-31',
                'catalog_id' => 'TestCatalog'
            ],
        ], true, 'UTF-8');

        Storage::put('public/' . $filename, $result);
    }
}
