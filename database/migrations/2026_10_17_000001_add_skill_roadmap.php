<?php

use App\Support\SkillRoadmapCatalog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->unsignedInteger('roadmap_order')->default(0);
            $table->unsignedInteger('required_pair_skills')->default(0);
        });
        Schema::create('skill_prerequisites', function (Blueprint $table) {
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->foreignId('prerequisite_id')->constrained('skills')->cascadeOnDelete();
            $table->primary(['skill_id', 'prerequisite_id']);
        });
        Schema::table('users', fn (Blueprint $table) => $table->foreignId('target_skill_id')->nullable()->constrained('skills')->nullOnDelete());
        SkillRoadmapCatalog::apply();
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['target_skill_id']);
            $table->dropColumn('target_skill_id');
        });
        Schema::dropIfExists('skill_prerequisites');
        foreach (SkillRoadmapCatalog::definitions() as $name => $definition) {
            DB::table('skills')->where('name', $name)->update(['category' => $definition[0] === 5 ? 'ペア' : 'ソロ中級']);
        }
        Schema::table('skills', fn (Blueprint $table) => $table->dropColumn(['roadmap_order', 'required_pair_skills']));
    }
};
