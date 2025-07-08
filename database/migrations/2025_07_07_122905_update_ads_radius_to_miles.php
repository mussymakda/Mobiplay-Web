<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // First convert existing km values to miles (1 km = 0.621371 miles)
            DB::statement('UPDATE ads SET radius_km = ROUND(radius_km * 0.621371, 2)');
            
            // Then rename the column
            $table->renameColumn('radius_km', 'radius_miles');
            
            // Update the column to use decimal for more precision
            $table->decimal('radius_miles', 5, 2)->default(5)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            // Convert miles back to km (1 mile = 1.60934 km)
            DB::statement('UPDATE ads SET radius_miles = ROUND(radius_miles * 1.60934, 2)');
            
            // Rename column back
            $table->renameColumn('radius_miles', 'radius_km');
            
            // Change back to integer
            $table->integer('radius_km')->default(5)->change();
        });
    }
};
