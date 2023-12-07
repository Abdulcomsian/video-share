<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuggestedSkills;

class SuggestedSkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $video_editing_skills = [
            ["name" => "Premiere Pro"],
            ["name" => "Final Cut"],
            ["name" => "DaVinci Resolve"],
            ["name" => "Filmora"],
            ["name" => "After Effects"],
            ["name" => "Motion Graphics"],
            ["name" => "Color Grading"],
            ["name" => "Audio Editing"],
            ["name" => "Green Screen"],
            ["name" => "Transitions"],
            ["name" => "Storyboarding"],
            ["name" => "Efficient Workflow"],
            ["name" => "Time Management"],
            ["name" => "Client Collaboration"],
            ["name" => "Problem-solving"],
            ["name" => "Attention to Detail"],
            ["name" => "Adaptability"],
            ["name" => "Visual Storytelling"],
            ["name" => "Creative Vision"],
            ["name" => "Communication"],
            ["name" => "Project Portfolio"],
            ["name" => "Marketing"],
            ["name" => "Industry Trends"],
            ["name" => "Continuous Learning"],
        ];
        
        SuggestedSkills::insert($video_editing_skills);

        echo "skills added successfully";
    }
}
