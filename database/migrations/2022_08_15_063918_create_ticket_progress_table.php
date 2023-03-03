<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id');
            $table->dateTime('date');
            $table->text('description');
            $table->enum('status', ['New Request', 'In Progress', 'Hold', 'Solved', 'Closed']);
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_progress');
    }
}
