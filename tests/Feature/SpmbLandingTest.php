<?php

namespace Tests\Feature;

use Tests\TestCase;

class SpmbLandingTest extends TestCase
{
    public function test_sd_landing_keeps_sd_information_and_registration(): void
    {
        $this->get('/spmb')->assertOk()
            ->assertSee('SPMB SD')
            ->assertSee('Paket A (MSU/SD)')
            ->assertSee('galeri/foto-5.jpeg', false)
            ->assertDontSee('galeri/foto-1.jpeg', false)
            ->assertSee('Rp650.000')
            ->assertSee('name="jenjang" value="SD"', false)
            ->assertDontSee('Daftar SMP')
            ->assertDontSee('Rp1.650.000');
    }

    public function test_smp_landing_has_its_own_content_and_registration(): void
    {
        $this->get('/spmb-smp')->assertOk()
            ->assertSee('2027/2028')
            ->assertSee('Paket B (MSW/SD)')
            ->assertSee('galeri/foto-1.jpeg', false)
            ->assertDontSee('galeri/foto-5.jpeg', false)
            ->assertSee('Rp1.650.000')
            ->assertSee('30 santri')
            ->assertSee('20 santri')
            ->assertSee('02 Januari 2027 M')
            ->assertSee('name="jenjang" value="SMP"', false)
            ->assertSee('action="'.route('spmb.store').'"', false)
            ->assertSee('081905059919')
            ->assertDontSee('Daftar SD')
            ->assertDontSee('Rp650.000');
    }
}
