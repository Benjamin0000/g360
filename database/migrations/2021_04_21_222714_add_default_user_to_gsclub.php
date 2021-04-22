<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class AddDefaultUserToGsclub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gs_clubs', function (Blueprint $table) {
            $table->boolean('def')->default(0);
            $table->string('def_refs')->default(0);
            $table->string('tag')->nullable();
            $table->boolean('switch')->default(0);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gs_clubs', function (Blueprint $table) {
            $table->dropColumn(['def', 'def_refs', 'tag']);
        });
    }
}
