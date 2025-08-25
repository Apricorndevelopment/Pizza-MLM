<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('login_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable(); // mobile, desktop, tablet
            $table->string('browser')->nullable(); // Chrome, Firefox, etc.
            $table->string('platform')->nullable(); // Windows, macOS, Linux, iOS, Android
            $table->string('session_id')->nullable();
            $table->timestamp('login_time');
            $table->timestamp('logout_time')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('login_time');
            $table->index('session_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('login_activities');
    }
};
