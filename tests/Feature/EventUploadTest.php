<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventUploadTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->adminUser = new User([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->adminUser->role = 'admin';
        $this->adminUser->save();

        // Create category
        $this->category = Category::create([
            'name' => 'Workshop',
            'slug' => 'workshop',
        ]);
    }

    /** @test */
    public function it_rejects_negative_ticket_price()
    {
        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.events.store'), [
                'category_id' => $this->category->id,
                'title' => 'Sample Workshop',
                'description' => 'A workshop description.',
                'date' => '2026-06-12 10:00:00',
                'location' => 'Amikom Gedung 3',
                'price' => -5, // Negative price
                'stock' => 10,
            ]);

        $response->assertSessionHasErrors(['price']);
        $this->assertEquals(0, Event::count());
    }

    /** @test */
    public function it_uploads_event_poster_and_stores_correctly()
    {
        Storage::fake('public');

        $poster = UploadedFile::fake()->create('poster.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.events.store'), [
                'category_id' => $this->category->id,
                'title' => 'Sample Workshop',
                'description' => 'A workshop description.',
                'date' => '2026-06-12 10:00:00',
                'location' => 'Amikom Gedung 3',
                'price' => 50000,
                'stock' => 10,
                'poster' => $poster,
            ]);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success', 'Data Event berhasil ditambahkan.');

        $this->assertEquals(1, Event::count());
        $event = Event::first();
        
        $this->assertNotNull($event->poster_path);
        Storage::disk('public')->assertExists($event->poster_path);
    }

    /** @test */
    public function it_deletes_old_poster_when_updating_new_one()
    {
        Storage::fake('public');

        $oldPoster = UploadedFile::fake()->create('old.jpg', 100, 'image/jpeg');
        $oldPath = $oldPoster->store('posters', 'public');

        $event = Event::create([
            'category_id' => $this->category->id,
            'title' => 'Sample Workshop',
            'description' => 'A workshop description.',
            'date' => '2026-06-12 10:00:00',
            'location' => 'Amikom Gedung 3',
            'price' => 50000,
            'stock' => 10,
            'poster_path' => $oldPath,
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $newPoster = UploadedFile::fake()->create('new.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.events.update', $event->id), [
                'category_id' => $this->category->id,
                'title' => 'Updated Workshop',
                'description' => 'An updated description.',
                'date' => '2026-06-12 10:00:00',
                'location' => 'Amikom Gedung 3',
                'price' => 60000,
                'stock' => 15,
                'poster' => $newPoster,
            ]);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success', 'Event berhasil diperbarui.');

        $event->refresh();
        $this->assertNotEquals($oldPath, $event->poster_path);
        Storage::disk('public')->assertExists($event->poster_path);
        Storage::disk('public')->assertMissing($oldPath);
    }

    /** @test */
    public function it_displays_event_details_dinamically()
    {
        $event = Event::create([
            'category_id' => $this->category->id,
            'title' => 'Dynamic Event Title',
            'description' => 'Dynamic Event Description',
            'date' => '2026-06-12 10:00:00',
            'location' => 'Amikom Gedung 3',
            'price' => 50000,
            'stock' => 10,
            'poster_path' => null,
        ]);

        $response = $this->get(route('events.show', $event->id));
        $response->assertStatus(200);
        $response->assertSee('Dynamic Event Title');
        $response->assertSee('Dynamic Event Description');
        $response->assertSee('12 Jun 2026, 10:00');
        $response->assertSee('Amikom Gedung 3');
        $response->assertSee('Rp 50.000');
        $response->assertSee('10 Tiket lagi!');
        $response->assertSee(url('checkout/' . $event->id));
    }
}
