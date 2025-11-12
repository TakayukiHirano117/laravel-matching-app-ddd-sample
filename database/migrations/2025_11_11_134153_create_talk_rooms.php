<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('talk_rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('other_user_id');
            $table->string('last_message')->nullable();

            $table->string('unique_user_id_pair_combination')
                ->storedAs("
                    CASE
                        WHEN user_id::text < other_user_id::text
                        THEN user_id::text || '-' || other_user_id::text
                        ELSE other_user_id::text || '-' || user_id::text
                    END
                ");

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('other_user_id')->references('id')->on('users');

            $table->index(['user_id', 'other_user_id']);

            $table->unique('unique_user_id_pair_combination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talk_rooms');
    }
};
