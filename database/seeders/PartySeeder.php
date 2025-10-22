<?php

namespace Database\Seeders;

use App\Models\Party;
use Illuminate\Database\Seeder;

class PartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create two default political parties
        $parties = [
            [
                'name' => 'Progressive Alliance',
                'slogan' => 'Progress Through Unity',
                'color' => '#0d6efd', // Blue
                'description' => 'A forward-thinking party focused on innovation and student welfare.'
            ],
            [
                'name' => 'Unity Party',
                'slogan' => 'Strength in Diversity',
                'color' => '#dc3545', // Red
                'description' => 'A party dedicated to bringing all students together for common goals.'
            ]
        ];
        
        foreach ($parties as $partyData) {
            Party::firstOrCreate(
                ['name' => $partyData['name']],
                $partyData
            );
        }
        
        echo "Two political parties created:\n";
        echo "1. Progressive Alliance (Blue)\n";
        echo "2. Unity Party (Red)\n\n";
    }
}