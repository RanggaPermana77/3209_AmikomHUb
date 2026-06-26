<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Admin Amikom',
            'email' => 'admin@amikom.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $category = \App\Models\Category::create([
            'name' => 'Seminar',
            'slug' => 'seminar-it',
        ]);

        $category2 = \App\Models\Category::create([
            'name' => 'Entertaniment',
            'slug' => 'entertainment',
        ]);

        $category3 = \App\Models\Category::create([
            'name' => 'Career Development',
            'slug' => 'career-development',
        ]);

        $category4 = \App\Models\Category::create([
            'name' => 'E-Sport',
            'slug' => 'e-sport',
        ]);

        $category5 = \App\Models\Category::create([
            'name' => 'Sport',
            'slug' => 'sport',
        ]);

        \App\Models\Event::create([
            'category_id' => $category2->id,
            'title' => 'Jazz Night 2025',
            'description' => 'Nikmati malam yang indah dengan alunan musik.',
            'date' => '2026-05-10 19:00:00',
            'location' => 'Amikom Baru',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-1.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'AI Summit & Expo 2026',
            'description' => 'Jelajahi tren terkini dalam bidang Artificial Intelligence.',
            'date' => '2026-05-01 13:00:00',
            'location' => 'Ruang Cinema',
            'price' => 45000,
            'stock' => 150,
            'poster_path' => 'posters/event-2.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category3->id,
            'title' => 'CV & LinkedIn Optimization Workshop',
            'description' => 'Pelatihan membuat CV profesional dan optimasi LinkedIn untuk dunia kerja.',
            'date' => '2026-06-15 09:00:00',
            'location' => 'Ruang Seminar B',
            'price' => 30000,
            'stock' => 100,
            'poster_path' => 'posters/event-7.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category3->id,
            'title' => 'Interview Simulation Bootcamp',
            'description' => 'Simulasi wawancara kerja bersama HR profesional untuk meningkatkan kepercayaan diri.',
            'date' => '2026-06-20 13:00:00',
            'location' => 'Lab Soft Skill Amikom',
            'price' => 40000,
            'stock' => 80,
            'poster_path' => 'posters/event-8.png',
        ]);

       \App\Models\Event::create([
            'category_id' => $category3->id,
            'title' => 'Career Talk with Industry Expert',
            'description' => 'Sharing session bersama profesional industri tentang peluang karier di dunia IT.',
            'date' => '2026-06-25 10:00:00',
            'location' => 'Auditorium Kampus',
            'price' => 20000,
            'stock' => 150,
            'poster_path' => 'posters/event-9.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category4->id,
            'title' => 'Free Fire Campus Tournament',
            'description' => 'Turnamen Free Fire antar mahasiswa dengan hadiah menarik dan kompetisi seru.',
            'date' => '2026-07-01 14:00:00',
            'location' => 'Gaming Arena Amikom',
            'price' => 25000,
            'stock' => 200,
            'poster_path' => 'posters/event-10.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category4->id,
            'title' => 'PUBG Mobile Battle Royale',
            'description' => 'Turnamen PUBG Mobile tingkat kampus untuk menguji strategi dan teamwork.',
            'date' => '2026-07-05 15:00:00',
            'location' => 'E-Sport Hall Amikom',
            'price' => 30000,
            'stock' => 180,
            'poster_path' => 'posters/event-11.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category5->id,
            'title' => 'Padel Tournament Campus Cup',
            'description' => 'Turnamen padel antar mahasiswa Amikom untuk meningkatkan sportivitas dan kebugaran.',
            'date' => '2026-07-10 08:00:00',
            'location' => 'Lapangan Padel Amikom',
            'price' => 20000,
            'stock' => 120,
            'poster_path' => 'posters/event-12.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category5->id,
            'title' => 'Swimming Championship',
            'description' => 'Kompetisi renang antar mahasiswa dengan berbagai kategori lomba.',
            'date' => '2026-07-12 09:00:00',
            'location' => 'Kolam Renang Amikom',
            'price' => 15000,
            'stock' => 100,
            'poster_path' => 'posters/event-13.png',
        ]);

        // Seed Partner data
        $this->call(PartnerSeeder::class);
    }
}