<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // employee_id সাধারণত ইউনিক হয়, তাই unique() দেওয়া হয়েছে
            $table->string('employee_id')->unique()->nullable()->after('id');
            $table->string('phone')->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_id', 'phone']);
        });
    }
};