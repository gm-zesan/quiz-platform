<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = array(
            array('id' => '1','question_id' => '1','option' => 'opt-1','is_correct' => '0','created_at' => '2025-01-22 22:42:36','updated_at' => '2025-01-23 01:29:24'),
            array('id' => '2','question_id' => '1','option' => 'opt-2','is_correct' => '1','created_at' => '2025-01-22 22:42:36','updated_at' => '2025-01-22 22:42:36'),
            array('id' => '3','question_id' => '1','option' => 'opt-3','is_correct' => '1','created_at' => '2025-01-22 22:42:36','updated_at' => '2025-01-22 22:42:36'),
            array('id' => '4','question_id' => '1','option' => 'opt-4','is_correct' => '0','created_at' => '2025-01-22 22:42:36','updated_at' => '2025-01-22 22:42:36'),
            array('id' => '5','question_id' => '2','option' => 'opt-1','is_correct' => '1','created_at' => '2025-01-22 23:19:27','updated_at' => '2025-01-22 23:19:27'),
            array('id' => '6','question_id' => '2','option' => 'opt-2','is_correct' => '0','created_at' => '2025-01-22 23:19:27','updated_at' => '2025-01-22 23:19:27'),
            array('id' => '7','question_id' => '2','option' => 'opt-3','is_correct' => '0','created_at' => '2025-01-22 23:19:27','updated_at' => '2025-01-22 23:19:27'),
            array('id' => '8','question_id' => '2','option' => 'opt-4','is_correct' => '1','created_at' => '2025-01-22 23:19:27','updated_at' => '2025-01-23 01:29:34'),
            array('id' => '9','question_id' => '3','option' => 'opt-1','is_correct' => '1','created_at' => '2025-01-26 17:47:38','updated_at' => '2025-01-26 17:47:38'),
            array('id' => '10','question_id' => '3','option' => 'opt-2','is_correct' => '0','created_at' => '2025-01-26 17:47:38','updated_at' => '2025-01-26 17:47:38'),
            array('id' => '11','question_id' => '3','option' => 'opt-3','is_correct' => '1','created_at' => '2025-01-26 17:47:38','updated_at' => '2025-01-26 17:55:52'),
            array('id' => '12','question_id' => '3','option' => 'opt-4','is_correct' => '0','created_at' => '2025-01-26 17:47:38','updated_at' => '2025-01-26 17:47:38'),
            array('id' => '13','question_id' => '6','option' => 'opt-1','is_correct' => '0','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 19:48:17'),
            array('id' => '14','question_id' => '6','option' => 'opt-2','is_correct' => '1','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 22:54:30'),
            array('id' => '15','question_id' => '6','option' => 'opt-3','is_correct' => '0','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 18:42:31'),
            array('id' => '16','question_id' => '6','option' => 'opt-4','is_correct' => '0','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 19:45:05'),
            array('id' => '17','question_id' => '7','option' => 'option-1','is_correct' => '1','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 19:32:45'),
            array('id' => '18','question_id' => '7','option' => 'option-2','is_correct' => '0','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 19:32:45'),
            array('id' => '19','question_id' => '7','option' => 'option-3','is_correct' => '1','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 19:48:17'),
            array('id' => '20','question_id' => '7','option' => 'option-4','is_correct' => '1','created_at' => '2025-01-26 17:58:35','updated_at' => '2025-01-26 19:32:38')
        );

        foreach ($options as $option) {
            Option::create($option);
        }
    }
}
