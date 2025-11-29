<?php

namespace Tests\Feature;

use Tests\TestCase;

class TemplateTest extends TestCase
{
    public function test_homepage_redirects_to_login_when_guest()
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_login_page_loads()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login Page');
    }

    public function test_starter_requires_auth()
    {
        $response = $this->get('/starter');
        $response->assertRedirect('/login');
    }

    public function test_dashboard_v2_loads()
    {
        $response = $this->get('/dashboard/v2');
        $response->assertStatus(200);
        $response->assertSee('Dashboard v2');
    }

    public function test_sidebar_is_rendered_on_dashboard()
    {
        $response = $this->get('/dashboard/v2');
        $response->assertStatus(200);
        $response->assertSee('Navigation');
        $response->assertSee('Dashboard');
    }

    public function test_header_is_rendered_on_dashboard()
    {
        $response = $this->get('/dashboard/v2');
        $response->assertStatus(200);
        $response->assertSee('Color');
        $response->assertSee('Admin');
    }

    public function test_ionicons_script_is_loaded()
    {
        $response = $this->get('/dashboard/v2');
        $response->assertStatus(200);
        $response->assertSee('ionicons');
    }

    public function test_logout_button_present_in_topbar()
    {
        $response = $this->get('/dashboard/v2');
        $response->assertStatus(200);
        $response->assertSee('Log Out');
        $response->assertSee('/logout');
    }

    public function test_logout_get_redirects_to_login()
    {
        $response = $this->get('/logout');
        $response->assertRedirect('/login');
    }
}
