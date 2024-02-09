<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_can_see_login_form(): void
    {
        $response = $this->get('/dashboard/login');

        $response->assertSee('Log-in dengan Portal');
    }
}
