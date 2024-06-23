<?php

namespace Tests\Browser;

use DemandwareXml\Writer\Entity\Product;
use DemandwareXml\Writer\Xml\XmlWriter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use DateTimeImmutable;

class ProductsTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */

    public function test_products_xml(): void
    {
        $this->buildDocument();
        $this->assertXmlFileEqualsXmlFile(
            "public/test1.xml",
            "public/files/prodsfromtest.xml"
            // $this->buildDocument()->outputMemory(true)
        );
    }
    protected function buildDocument(): XmlWriter
    {
        $xml = new XmlWriter;
        $xml->openURI('public/files/prodsfromtest.xml');
        $xml->setIndentDefaults();
        $xml->startDocument();
        $xml->startCatalog('TestCatalog');
        $productsArray = [];
        $savedProductId = "";
        if (($handle = fopen("public/products.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // make "default" to a boolean
                $defaultVariant = $data[6] == "Y" ? true : false;

                // write the array. 
                // first element is the main product with id, display name, brand
                if ($savedProductId == "") {
                    $productsArray[$data[0]] = [
                        "product_id" => $data[0],
                        "brand" => $data[1],
                        "display_name" => $data[2],
                        "variations" => [
                            "variants" => [
                                "variant" =>
                                [
                                    "_attributes"
                                    => [
                                        "_product-id" => $data[3],
                                        "default" => $defaultVariant
                                    ]
                                ]
                            ]
                        ]
                    ];
                    $savedProductId = $data[0];
                } else {
                    // if variants exist, add all of them from second item to array as first level elements
                    // as variant id, custom attributes
                    $productsArray[$savedProductId]["variations"]["variants"]["variant"] =  [
                        "_attributes"
                        => [
                            "_product-id" => $data[3],
                            "default" => $data[6]
                        ]
                    ];

                    // along with the variants as first level elements, add the id to the main product level as well, along
                    // with the default = true/false
                    $productsArray[$data[3]] = [
                        "product_id" => $data[3],
                        "custom-attributes" =>
                        [
                            "custom-attribute" =>
                            [
                                "attribute-id" => "colour",
                                "__text" => $data[4]
                            ],
                            "custom-attribute" =>
                            [
                                "attribute-id" => "size",
                                "__text" => $data[5]
                            ],
                        ]
                    ];
                }
                // if they are over, jump to the next main product id
            }
        }

        // read the array of products
        // run build the document on each line
        $xml->writeEntity($this->buildProductElement($productsArray));
        // end loop

        $xml->endCatalog();
        $xml->endDocument();

        return $xml;
    }
    protected function buildProductElement($data): Product
    {
        $element = $this->buildBaseElement('Product', 0, $data);
        // $element->setEncoding();

        return $element;
    }
    protected function buildBaseElement($data): Product
    {
        foreach ($data as $dataItem) {
            // building the xml 
            // add product id or variant id
            $element = new Product($dataItem["product_id"]);

            //if exists, add display name and brand
            if (isset($dataItem["display_name"])) {
                $element->setDisplayName($dataItem["display_name"]);
                $element->setBrand($dataItem["brand"]);
                // if the product has variations, loop through these
                foreach ($dataItem["variations"] as $variation) {
                    /* $element->setVariants([
                            'SKU0000001' => false,
                            'SKU0000002' => false,
                            'SKU0000003' => true,
                        ]);
                        */
                    if (
                        $variation["variant"]["_attributes"]["_product-id"] && $variation["variant"]["_attributes"]["default"]
                        && is_bool($variation["variant"]["_attributes"]["default"])
                    ) {
                        $element->setVariants(
                            [
                                $variation["variant"]["_attributes"]["_product-id"] => $variation["variant"]["_attributes"]["default"]
                            ]
                        );
                    }
                }
            } else if (count($dataItem["custom-attributes"]) > 0) {
                foreach ($dataItem["custom-attributes"] as $custom_attribute) {
                    $element->addCustomAttributes([
                        $custom_attribute["attribute-id"] => $custom_attribute["__text"]
                    ]);
                }
            }
        }

        return $element;
    }
}
