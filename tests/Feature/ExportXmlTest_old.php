<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExportXmlTest extends TestCase
{
    /**
     * Test that two xmls are identical
     */
    public function test_xml_match(): void
    {
        // $actualxmlFile    =  '/testproductoutput.xml';
        $actualxmlFile    =  'storage/app/public/products.xml';
        $expectedxmlFile  =  'public/test2.xml';
          // Assert function to test whether given  
        // expected xml file is equal to actual xml file or not 
        $this->assertXmlFileEqualsXmlFile( 
            $actualxmlFile, 
            $expectedxmlFile,  
            "actual xml file equal to expected xml file or not"
        );  
        
        
     
    }
    //test that xml result fits the requirements

}
