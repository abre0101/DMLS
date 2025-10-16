<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LetterTemplate;

class LetterTemplatesTableSeeder extends Seeder
{
    public function run()
    {
        LetterTemplate::updateOrCreate(
            ['name' => 'Welcome Letter'],
            ['content' => 'Dear {{name}}, welcome to our service! We are glad to have you.']
        );

        LetterTemplate::updateOrCreate(
            ['name' => 'Thank You Letter'],
            ['content' => 'Dear {{name}}, thank you for choosing our service. We appreciate your trust in us!']
        );

        LetterTemplate::updateOrCreate(
            ['name' => 'Reminder Letter'],
            ['content' => 'Dear {{name}}, this is a friendly reminder about your upcoming appointment on {{date}}.']
        );

        LetterTemplate::updateOrCreate(
            ['name' => 'Farewell Letter'],
            ['content' => 'Dear {{name}}, we are sad to see you go. Thank you for being a part of our community.']
        );

        LetterTemplate::updateOrCreate(
            ['name' => 'Update Notification'],
            ['content' => 'Dear {{name}}, we wanted to inform you about the latest updates to our service. Please check your account for more details.']
        );
    }
}