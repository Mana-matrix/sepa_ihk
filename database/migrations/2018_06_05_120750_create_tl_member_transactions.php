<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Illuminate\Database\Migrations\Migration;

class CreateTlMemberTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tl_member_transactions', function (Blueprint $table) {
            $table->increments('id');
            //$table->unsignedInteger('user_id');
            //$table->integer('user_id',false,true);
            $table->integer('tl_member_id')->unsigned();
            $table->foreign('tl_member_id')->references('id')->on('tl_member')->onDelete('cascade');
            $table->integer('transactions_id')->unsigned();
            $table->foreign('transactions_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->string('iban')->collation('utf8_unicode_ci');
            $table ->decimal('fee',8,2);
            //$table->timestamps();

                $table->timestamp('createdOn', 0)->nullable();

                $table->timestamp('tstamp', 0)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
