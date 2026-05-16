<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->uuid('local_id')->nullable()->unique()->index()->after('id');
            $table->unsignedBigInteger('remote_id')->nullable()->index()->after('local_id');
            $table->string('sync_status')->default('pending')->after('remote_id');
            $table->timestamp('last_synced_at')->nullable()->after('sync_status');
            $table->string('photo_path')->nullable()->after('last_synced_at');
            $table->decimal('location_lat', 10, 8)->nullable()->after('photo_path');
            $table->decimal('location_lng', 11, 8)->nullable()->after('location_lat');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['local_id', 'remote_id', 'sync_status', 'last_synced_at', 'photo_path', 'location_lat', 'location_lng']);
        });
    }
};