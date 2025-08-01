<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMerchantTableUpdateColumn extends Migration
{
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->string('business_name')->nullable();
        });
    }

    public function down()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->dropColumn('business_name');
        });
    }
}
