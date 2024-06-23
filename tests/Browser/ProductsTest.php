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
                // write the array. 
                // first element is the main product with id, display name, brand
                if ($savedProductId == "") {
                    $productsArray[] = [
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
                                        "default" => $data[6]
                                    ]
                                ]
                            ]
                        ]
                    ];
                    $savedProductId = $data[0];
                } else {
                    // find the id of the array that has this product id in it
                    die();
                }
                // if variants exist, add all of them from second item to array as first level elements
                // as variant id, custom attributes
                // along with the variants as first level elements, add the id to the main product level as well, along
                // with the default = true/false
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

        $element->setVariants([
            $data[3] => false
        ]);
        // $element->addCustomAttributes([
        //     'color'         => $data[4],
        //     'size'          => $data[5],
        // ]);

        return $element;
    }
    protected function buildBaseElement(string $type, int $number = 0, $data): Product
    {
        $element = new Product($data[0]);
        $element->setDisplayName($data[2]);
        $element->setBrand($data[1]);

        return $element;
    }
}
