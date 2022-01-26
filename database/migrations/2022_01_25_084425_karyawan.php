<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Karyawan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_karyawan', function (Blueprint $table) {
            $table->id('karyawan_id');
            $table->string('nama_karyawan');
            $table->string('jenis_kelamin');
            $table->string('nomor_hp');
            $table->string('email_aktif')->unique();;
            $table->string('salary');
            $table->string('foto_profil');
            $table->timestamps();
            $table->integer('status');
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
