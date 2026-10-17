<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('notices', fn (Blueprint $table) => $table->string('category')->default('その他'));
        Schema::table('points', fn (Blueprint $table) => $table->string('source_key')->nullable()->unique());
        Schema::table('skill_tips', fn (Blueprint $table) => $table->foreignId('parent_id')->nullable()->constrained('skill_tips')->cascadeOnDelete());
        Schema::create('skill_tip_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_tip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['skill_tip_id', 'from_user_id']);
        });
        Schema::create('practice_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notice_id')->unique()->constrained()->cascadeOnDelete();
            $table->dateTime('starts_at');
            $table->string('location', 100);
            $table->timestamps();
        });
        Schema::create('practice_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('unanswered');
            $table->timestamps();
            $table->unique(['practice_session_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practice_attendances');
        Schema::dropIfExists('practice_sessions');
        Schema::dropIfExists('skill_tip_likes');
        Schema::table('skill_tips', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
        Schema::table('points', function (Blueprint $table) {
            $table->dropUnique(['source_key']);
            $table->dropColumn('source_key');
        });
        Schema::table('notices', fn (Blueprint $table) => $table->dropColumn('category'));
    }
};
