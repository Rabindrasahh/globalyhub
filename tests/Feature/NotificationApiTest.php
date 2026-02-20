<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        NotificationTemplate::create([
            'id' => 1,
            'name' => 'Welcome Template',
            'type' => 'email',
            'subject' => 'Welcome!',
            'body' => 'Hello , welcome to our platform.',
            'is_active' => true
        ]);
    }

    /** @test */
    public function it_can_create_a_notification_via_api()
    {
        $payload = [
            'tenant_id' => 1,
            'notification_template_id' => 1,
            'recipients' => [
                'to' => ['user@example.com']
            ],
            'payload' => [
                'subject' => 'Welcome to Our Platform',
                'body' => 'Hello John, your account has been created successfully.'
            ],
            'scheduled_at' => now()->addMinutes(5)->toDateTimeString(),
        ];

        $response = $this->postJson('/api/v1/notifications', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'tenant_id' => 1
            ]);

        $this->assertDatabaseHas('notifications', [
            'tenant_id' => 1
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/v1/notifications', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'notification_template_id',
                'recipients',
                'payload'
            ]);
    }


    /** @test */
    public function it_can_fetch_notifications_list()
    {
        // Manually create notifications (no factory)
        Notification::create([
            'tenant_id' => 1,
            'notification_template_id' => 1,
            'recipients' => ['to' => ['user1@example.com']],
            'payload' => ['subject' => 'Test 1', 'body' => 'Body 1'],
            'status' => 'pending'
        ]);

        Notification::create([
            'tenant_id' => 1,
            'notification_template_id' => 1,
            'recipients' => ['to' => ['user2@example.com']],
            'payload' => ['subject' => 'Test 2', 'body' => 'Body 2'],
            'status' => 'pending'
        ]);

        Notification::create([
            'tenant_id' => 2,
            'notification_template_id' => 1,
            'recipients' => ['to' => ['user3@example.com']],
            'payload' => ['subject' => 'Test 3', 'body' => 'Body 3'],
            'status' => 'pending'
        ]);

        $response = $this->getJson('/api/v1/notifications');

        $response->assertStatus(200)
            ->assertJsonCount(3); // if returning simple collection
    }

    /** @test */
    public function it_can_fetch_single_notification()
    {
        $notification = Notification::create([
            'tenant_id' => 1,
            'notification_template_id' => 1,
            'recipients' => ['to' => ['single@example.com']],
            'payload' => ['subject' => 'Single', 'body' => 'Single Body'],
            'status' => 'pending'
        ]);

        $response = $this->getJson("/api/v1/notifications/{$notification->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $notification->id,
                'tenant_id' => 1
            ]);
    }
    /** @test */
    // public function it_can_fetch_notifications_list()
    // {
    //     Notification::factory()->count(3)->create();

    //     $response = $this->getJson('/api/v1/notifications');

    //     $response->assertStatus(200)
    //         ->assertJsonCount(3, 'data'); // assuming repository returns paginated 'data'
    // }

    /** @test */
    // public function it_can_fetch_single_notification()
    // {
    //     $notification = Notification::factory()->create();

    //     $response = $this->getJson("/api/v1/notifications/{$notification->id}");

    //     $response->assertStatus(200)
    //         ->assertJsonFragment(['id' => $notification->id]);
    // }

}
