<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Services\WriteXmlService;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\assertFileExists;

class FirstTest extends DuskTestCase
{

    /** @test */
    public function testWriteXml(): void
    {
        $writeXmlService = new WriteXmlService;
        $array = ["this" => "is an array"];
        $path = 'storage/app/public/';
        $filename = "yada.xml";
        $writeXmlService->arrayToXml($array, $filename);
        $this->assertFileExists($path . $filename);
        Storage::delete('public/' . $filename);
    }
}
