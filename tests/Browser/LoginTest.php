<?php

namespace Tests\Browser;

use App\Services\WriteXmlService;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Indicates which tables should be excluded from truncation.
     *
     * @var array
     */
    protected $exceptTables = ['users'];


    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Laravel');
        });
    }
   
}
