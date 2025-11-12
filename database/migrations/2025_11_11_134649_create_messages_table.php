<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('talk_room_id');
            $table->uuid('user_id');
            $table->text('content');
            $table->foreign('talk_room_id')->references('id')->on('talk_rooms');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['id', 'talk_room_id', 'user_id']);
            $table->index(['talk_room_id', 'id', 'user_id']);
            $table->index(['user_id', 'id', 'talk_room_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
