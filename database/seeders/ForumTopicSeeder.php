<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ForumTopic;

class ForumTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            ['name' => 'Health and Lifestyle', 'description' => 'Discuss health and lifestyle tips for caregivers and autistic individuals.'],
            ['name' => 'Diet', 'description' => 'Share and learn about dietary needs and tips.'],
            ['name' => 'Q&A', 'description' => 'Ask questions and get answers from the community.'],
            ['name' => 'Introduce Yourself', 'description' => 'New here? Introduce yourself to the community!'],
            ['name' => 'Autistic Adults', 'description' => 'A space for and about autistic adults.'],
        ];
        foreach ($topics as $topic) {
            ForumTopic::firstOrCreate(['name' => $topic['name']], $topic);
        }
    }
}
