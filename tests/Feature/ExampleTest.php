<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\DatabaseSeeder;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test all front-end website pages return 200 OK.
     */
    public function test_all_frontend_pages_return_200_ok(): void
    {
        $pages = [
            '/',
            '/about',
            '/services',
            '/packages',
            '/car-rental',
            '/bus-booking',
            '/contact',
        ];

        foreach ($pages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee('Debpriyan');
        }
    }

    /**
     * Test admin login and dashboard access.
     */
    public function test_admin_authentication_flow(): void
    {
        // 1. Unauthenticated access to /admin redirects to /admin/login
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/admin/login');

        // 2. Login with user ID adminat@12345 and password admin123
        $response = $this->post('/admin/login', [
            'login' => 'adminat@12345',
            'password' => 'admin123',
        ]);
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();

        // 3. Test all admin sections return 200 OK
        $adminUrls = [
            '/admin/dashboard',
            '/admin/sliders',
            '/admin/route-highlight',
            '/admin/services',
            '/admin/specializations',
            '/admin/videos',
            '/admin/vehicles',
            '/admin/packages',
            '/admin/settings',
            '/admin/inquiries',
        ];

        foreach ($adminUrls as $url) {
            $resp = $this->get($url);
            $resp->assertStatus(200);
        }
    }

    /**
     * Test customer inquiry submission.
     */
    public function test_customer_can_submit_inquiry(): void
    {
        $response = $this->post('/inquiry/submit', [
            'name' => 'Sourav Ganguly',
            'phone' => '9876543210',
            'email' => 'sourav@example.com',
            'service_type' => 'Car Rental',
            'pickup_location' => 'Behala',
            'drop_location' => 'Airport',
            'journey_date' => '2026-12-01',
            'message' => 'Need Dzire with AC',
        ]);

        $this->assertDatabaseHas('inquiries', [
            'phone' => '9876543210',
            'name' => 'Sourav Ganguly',
        ]);
    }

    /**
     * Test admin can update sections without 500 error.
     */
    public function test_admin_can_update_all_sections(): void
    {
        $user = User::first();
        $this->actingAs($user);

        // 1. Test Service update
        $service = \App\Models\Service::first();
        $resp = $this->put('/admin/services/' . $service->id, [
            'title' => 'Updated Dzire Rental',
            'bengali_subtitle' => 'আপডেটেড গাড়ি ভাড়া',
            'category' => 'Rental',
            'description' => 'Updated description text',
            'icon' => 'fa-solid fa-car',
            'image' => $service->image,
            'btn_text' => 'Book Now',
            'whatsapp_message' => 'Hello test message',
            'sort_order' => 1,
            'is_active' => '1',
        ]);
        $resp->assertRedirect('/admin/services');
        $this->assertDatabaseHas('services', ['title' => 'Updated Dzire Rental']);

        // 2. Test Settings update
        $resp = $this->post('/admin/settings', [
            'site_name' => 'Debpriyan Travels Live',
            'phone_1' => '9433053668',
            'phone_2' => '9432430360',
        ]);
        $resp->assertRedirect();
        $this->assertDatabaseHas('settings', ['key' => 'site_name', 'value' => 'Debpriyan Travels Live']);

        // 3. Test Route Highlight update
        $resp = $this->put('/admin/route-highlight', [
            'title' => 'All India Bus Booking Live',
            'badge' => 'PAN-INDIA',
            'bengali_tagline' => 'সেরা পরিষেবা',
            'description' => 'Test description',
            'routes' => 'KOLKATA, DELHI, MUMBAI',
            'phone' => '9433053668',
            'email' => 'test@example.com',
            'address' => 'Kolkata',
        ]);
        $resp->assertRedirect();
        $this->assertDatabaseHas('route_highlights', ['title' => 'All India Bus Booking Live']);
    }
}
