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
        Schema::create('user_calendar_events', function (Blueprint $table) {
            $table->id();
            $table->char('calendar_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->text('calendar_link');
            $table->text('meet_link')->nullable();
            $table->unsignedBigInteger('creater_id')->comment('Создатель события');
            $table->foreign('creater_id')
                ->references('id')
                ->on('social_accounts')
                ->onDelete('cascade');
            $table->unsignedBigInteger('client_id')->comment('Владелец календаря');
            $table->foreign('client_id')
                ->references('id')
                ->on('social_accounts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_events');
    }
};
