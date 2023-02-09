<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->dateTime('date');
            $table->string('title');
            $table->foreignId('category_id')->constrained('categories')
                ->onDelete('cascade');
            $table->foreignId('department_id');
            $table->foreignId('location_id');
            $table->foreignId('status_id')->constrained('status')
                ->onDelete('cascade');
            $table->text('detail_trouble');
            $table->foreignId('requester_id')->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained('users')
                ->onDelete('cascade');
            $table->tinyInteger('assign')->default(0);
            $table->dateTime('assign_date')->nullable();
            $table->dateTime('solved_date')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
