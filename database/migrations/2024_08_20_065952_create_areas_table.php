<?php

use App\Models\Category;
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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Category::class)->constrained();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('geojson'); // If you want to store raw GeoJSON data
            $table->geometry('geometry'); // PostGIS geometry column
            $table->boolean('in_breaches');
            $table->date('valid_from');
            $table->date('valid_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
