<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use DemandwareXml\Test\FixtureHelper;
use DemandwareXml\Writer\Entity\CustomAttribute;
use DemandwareXml\Writer\Entity\DeletedProduct;
use DemandwareXml\Writer\Entity\Product;
use DemandwareXml\Writer\EntityWriter\CustomAttributeWriter;
use DemandwareXml\Writer\Xml\XmlWriter;

class ExampleTest extends TestCase
{

    use FixtureHelper;
   
    public function test_that_true_is_true(): void
    {
        // $this->assertTrue(true);
        $this->assertXmlStringEqualsXmlString(
            $this->loadFixture('products.xml'),
            $this->buildDocument()->outputMemory(true)
        );
    }
}
