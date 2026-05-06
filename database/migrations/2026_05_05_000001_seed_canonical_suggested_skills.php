<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Deduplicate existing rows (keep oldest id per name) before adding unique index
        DB::statement("
            DELETE s1 FROM suggested_skills s1
            INNER JOIN suggested_skills s2
            WHERE s1.name = s2.name AND s1.id > s2.id
        ");

        // 2. Add unique index on name (idempotent — skip if already present)
        $hasUnique = collect(DB::select('SHOW INDEX FROM suggested_skills'))
            ->contains(fn ($i) => $i->Key_name === 'suggested_skills_name_unique');

        if (! $hasUnique) {
            Schema::table('suggested_skills', function (Blueprint $table) {
                $table->unique('name');
            });
        }

        // 3. Upsert canonical taxonomy — leaves user-added entries untouched
        $skills = [
            // EDITING & POST
            'Video editing',
            'Color grading',
            'Sound design',
            'Motion graphics',
            'VFX',
            'AI generation',
            'Compositing',
            'Subtitle',
            'Multi-cam editing',

            // GRAPHICS & ANIMATION
            '2D animation',
            '3D animation',
            'Logo animation',
            'Lottie animation',
            'Lower thirds',
            'Infographics',

            // CONTENT TYPES
            'YouTube videos',
            'Short-form',
            'Podcast editing',
            'Wedding',
            'Real estate',
            'Documentary',
            'Commercial',
            'Educational',
            'Gaming content',
            'Sports highlights',

            // PROFESSIONAL TOOLS
            'Adobe Premiere Pro',
            'Adobe After Effects',
            'Adobe Photoshop',
            'DaVinci Resolve',
            'Final Cut Pro',
            'Avid Media Composer',
            'CapCut',
            'Cinema 4D',
            'Blender',

            // OUTPUT FORMATS
            'Vertical (9:16)',
            'Horizontal (16:9)',
            'Square (1:1)',
        ];

        $now = now();

        $rows = array_map(fn ($name) => [
            'name'       => $name,
            'created_at' => $now,
            'updated_at' => $now,
        ], $skills);

        DB::table('suggested_skills')->upsert($rows, ['name'], ['updated_at']);
    }

    public function down(): void
    {
        // No rollback — canonical taxonomy + unique index are intentional and shared with user-added rows
    }
};
