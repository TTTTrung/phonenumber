<?php

use App\Models\User;
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
        Schema::create('phonenums', function (Blueprint $table) {
            $table->id();
            $table->string('namephone');
            $table->string('phonenumber')->unique();
            $table->string('department');
            $table->string('building');
            $table->string('role');    
            $table->timestamps();
            $table->foreignIdFor(User::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phonenums');
    }
};
