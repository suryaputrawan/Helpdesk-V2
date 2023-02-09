<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_ticket', function (Blueprint $table) {
            $table->foreignId('item_id')->constrained('items')
                ->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained('tickets')
                ->onDelete('cascade');
            $table->string('description');
            $table->dateTime('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_ticket');
    }
}
