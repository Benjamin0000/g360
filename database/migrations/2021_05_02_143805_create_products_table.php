<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('name');
            $table->string('slug');
            $table->string('user_id');
            $table->string('shop_id');
            $table->string('category_id');
            $table->decimal('price', 65, 2);
            $table->decimal('old_price', 65, 2);
            $table->integer('qty')->default(0);
            $table->longText('description');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('products');
    }
}
