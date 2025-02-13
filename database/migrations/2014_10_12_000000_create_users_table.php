<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('username')->unique();
            $table->bigInteger('gnumber')->unique();
            $table->bigInteger('placed_by')->default(0);
            $table->string('title', 5);
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('pic')->nullable();
            $table->string('nin_number')->nullable();
            $table->bigInteger('ref_gnum');
            $table->integer('pkg_id')->default(0);
            $table->integer('rank_id')->default(0);
            $table->decimal('with_balance', 65, 2)->default(0); // withdrawal wallet
            $table->decimal('trx_balance', 65, 2)->default(0); // transaction wallet
            $table->decimal('pend_balance', 65, 2)->default(0); //  pending balance
            $table->decimal('pend_trx_balance', 65, 2)->default(0); //  pending transaction balance
            $table->decimal('award_point', 65, 2)->default(0); //  award balance
            $table->decimal('pkg_balance', 65, 2)->default(0); //  package balance
            $table->decimal('loan_elig_balance', 65, 2)->default(0); // loan eligable balance
            $table->decimal('total_loan_balance', 65, 2)->default(0);
            $table->bigInteger('h_token')->default(0); // health token
            $table->bigInteger('cpv')->default(0); // cumulative point value
            $table->decimal('deca', 65, 2)->default(0);
            $table->boolean('def_user')->default(0); // Default user that refers other users without a sponsor
            $table->boolean('status')->default(1); // Void users account
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('free_t_fee')->nullable(); // upgrade fee after free trial
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
