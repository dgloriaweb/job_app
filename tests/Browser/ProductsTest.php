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
        $xml->writeEntity($this->buildProductElement());
        $xml->endCatalog();
        $xml->endDocument();

        return $xml;
    }
    protected function buildProductElement(): Product
    {
        $element = $this->buildBaseElement('Product');
        // $element->setClassificationCategory('CAT123', 'TestCatalog');
        // $element->setSitemap(1.0);
        // // $element->setEncoding();
        // $element->setImages(['product-123.png']);
        // $element->setSharedVariationAttributes(['AT001', 'AT002']);
        $element->setVariants([
            'SKU0000001' => false,
            'SKU0000002' => false,
            'SKU0000003' => true,
        ]);

        // $element->addVariationGroups([
        //     'PRODUCT123-Red',
        //     'PRODUCT123-Yellow',
        //     'PRODUCT123-Green',
        // ]);

        return $element;
    }
    protected function buildBaseElement(string $type, int $number = 0): Product
    {
        $invalidChar1 = chr(30); // Record Separator.
        $invalidChar2 = chr(2);  // Start Of Text.

        $element = new Product(mb_strtoupper($type) . '123');
        $element->setDisplayName($type . ' number 123');
        // $element->setLongDescription('<b>' . $type . '</b> The description for an <i>example</i> ' . mb_strtolower($type) . '! • Bullet' . $invalidChar1 . 'Point');
        // $element->setUpc('50000000000' . $number);
        // $element->setQuantities(); // include, but use defaults
        // $element->setSearchRank(1);
        $element->setBrand('SampleBrand™');
        // $element->setOnlineFlag(true);
        $element->setSearchableFlags(null, false, null);

        // $element->setOnlineFromTo(
        //     new DateTimeImmutable('2015-01-23 01:23:45'),
        //     new DateTimeImmutable('2025-01-23 01:23:45')
        // );

        // $element->setPageAttributes(
        //     'Amazing ' . $type,
        //     'Buy our ' . $type . ' today!',
        //     $type . ', test, example',
        //     'http://example.com/' . mb_strtolower($type) . '/123'
        // );

        $element->addCustomAttributes([
            'type'         => 'Examples',
            'zzz'          => 'Should be exported last within' . $invalidChar2 . 'custom-attributes',
            'primaryImage' => mb_strtolower($type) . '-123.png',
            'multiWow'     => ['so', 'such', 'many', 'much', 'very'],
            'boolTrue'     => true,
            'boolFalse'    => false,
        ]);

        // $element->setImages([mb_strtolower($type) . '-123.png']);

        return $element;
    }
}
