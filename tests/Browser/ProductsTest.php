<?php

namespace Tests\Browser;

use DemandwareXml\Writer\Entity\Product;
use DemandwareXml\Writer\Xml\XmlWriter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use DemandwareXml\Test\FixtureHelper;
use DemandwareXml\Writer\Entity\CustomAttribute;
use DemandwareXml\Writer\Entity\DeletedProduct;
use DemandwareXml\Writer\EntityWriter\CustomAttributeWriter;
use InvalidArgumentException;

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
        if (($handle = fopen("public/products.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // read the array of products
                // run build the document on each line
                $xml->writeEntity($this->buildProductElement($data));
                // end loop
            }
        }


        $xml->endCatalog();
        $xml->endDocument();

        return $xml;
    }
    protected function buildProductElement($data): Product
    {
        $element = $this->buildBaseElement('Product',0 , $data);
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
