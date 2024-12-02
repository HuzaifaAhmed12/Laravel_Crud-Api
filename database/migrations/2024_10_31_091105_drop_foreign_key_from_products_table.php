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
        Schema::table('products', function (Blueprint $table) {
            // Check if the foreign key exists before trying to drop it
            if (Schema::hasTable('products')) {
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_NAME = 'products' 
                    AND TABLE_SCHEMA = '" . env('DB_DATABASE') . "'
                    AND CONSTRAINT_NAME = 'products_category_id_foreign'
                ");
                if (count($foreignKeys) > 0) {
                    $table->dropForeign(['category_id']);
                }
            }
            // Now drop the category_id column if it exists
            if (Schema::hasColumn('products', 'category_id')) {
                $table->dropColumn('category_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
