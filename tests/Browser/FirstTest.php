<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Services\WriteXmlService;

use function PHPUnit\Framework\assertFileExists;

class FirstTest extends DuskTestCase
{

    /** @test */
    public function testWriteXml(): void
    {
        $writeXmlService = new WriteXmlService;
        $array = ["this" => "is an array"];
        $filename = "products.xml";
        $writeXmlService->arrayToXml($array, $filename);
        $this->assertFileExists('storage/app/public/' . $filename);
    }
}
