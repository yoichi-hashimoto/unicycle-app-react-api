<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('member')->index();
            $table->boolean('beginner_mode')->default(false);
            $table->unsignedTinyInteger('beginner_step')->default(1);
        });

        DB::table('users')
            ->where('is_admin', true)
            ->update(['role' => 'admin']);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
            $table->unsignedBigInteger('user_avatar_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false);
        });

        DB::table('users')
            ->where('role', 'admin')
            ->update(['is_admin' => true]);

        $fallbackAvatarId = DB::table('user_avatars')->min('id');
        if ($fallbackAvatarId !== null) {
            DB::table('users')
                ->whereNull('user_avatar_id')
                ->update(['user_avatar_id' => $fallbackAvatarId]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('user_avatar_id')->nullable(false)->change();
            $table->dropIndex(['role']);
            $table->dropColumn(['role', 'beginner_mode', 'beginner_step']);
        });
    }
};
