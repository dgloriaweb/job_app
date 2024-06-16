<?php

namespace Tests\Browser;

use DemandwareXml\Writer\Xml\XmlWriter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductsTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */

    public function test_products_xml(): void
    {
        $this->buildDocument()->outputMemory(true);
    }
    protected function buildDocument(): XmlWriter
    {
        $xml = new XmlWriter;
        $xml->openURI('public/files/prodsfromtest.xml');
        $xml->setIndentDefaults();
        $xml->startDocument();
        $xml->startCatalog('TestCatalog');
        // $xml->writeEntity($this->buildProductElement());
        // $xml->writeEntity($this->buildMinimalProductElement());
        // $xml->writeEntity($this->buildSetElement());
        // $xml->writeEntity($this->buildBundleElement());
        // $xml->writeEntity($this->buildVariationElement());
        $xml->endCatalog();
        $xml->endDocument();

        return $xml;
    }
}
