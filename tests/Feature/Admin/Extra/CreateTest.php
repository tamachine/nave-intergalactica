<?php

namespace Tests\Feature\Admin\Extra;

use App\Http\Livewire\Admin\Extra\Create;
use App\Models\Extra;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $vendor;
    protected $caren_extra;

    public function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->createUser();
        $this->vendor = $this->createVendor();
        $this->caren_extra = $this->createCarenExtra();
    }

    /**
     * @test
     * @group feature
     * @group admin
     * @group extra
     * @group extra-create
     *
     * @return void
     */
    public function createExtraValidatesInput()
    {
        $this->actingAs($this->admin);

        Livewire::test(Create::class, ['caren_extra' => $this->caren_extra->id])
            ->set('name', null)
            ->set('vendor', null)
            ->call('saveExtra')
            ->assertHasErrors([
                'name' => ['required'],
                'vendor' => ['required'],
            ]);
    }

    /**
     * @test
     * @group feature
     * @group admin
     * @group extra
     * @group extra-create
     *
     * @return void
     */
    public function createExtraPostDataSaves()
    {
        $this->actingAs($this->admin);

        Livewire::test(Create::class, ['caren_extra' => $this->caren_extra->id])
            ->set('name', "Extra 1")
            ->set('vendor', $this->vendor->hashid)
            ->call('saveExtra')
            ->assertStatus(200);

        $extra = Extra::first();

        $this->assertEquals("Extra 1", $extra->name);
        $this->assertEquals($this->vendor->id, $extra->vendor_id);
    }
}
