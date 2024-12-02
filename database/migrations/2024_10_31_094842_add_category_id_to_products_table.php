<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class AddCategoryIdToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add the category_id column
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the category_id column if rolling back
            $table->dropForeign(['category_id']); // Drop the foreign key constraint
            $table->dropColumn('category_id'); // Drop the column
        });
    }
}
