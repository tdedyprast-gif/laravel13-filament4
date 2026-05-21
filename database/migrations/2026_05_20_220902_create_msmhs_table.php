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
        Schema::create('msmhs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_perguruan_tinggi', 6)->comment('KDPTIMSMHS - Kode Perguruan Tinggi');
            $table->string('kode_program_studi', 5)->comment('KDPSTMSMHS - Kode Program Studi');
            $table->string('kode_jenjang_studi', 2)->comment('KDJENMSMHS - Kode Jenjang Studi');
            $table->string('nim', 30)->unique()->comment('NIMHSMSMHS - Nomor Induk Mahasiswa');
            $table->string('nama_mahasiswa', 150)->comment('NMMHSMSMHS - Nama Mahasiswa');
            $table->string('tempat_lahir', 100)->nullable()->comment('TPLHRMSMHS - Tempat Lahir');
            $table->date('tanggal_lahir')->nullable()->comment('TGLHRMSMHS - Tanggal Lahir');
            $table->string('jenis_kelamin', 1)->nullable()->comment('KDJEKMSMHS - Kode Jenis Kelamin');
            $table->unsignedSmallInteger('tahun_masuk')->nullable()->comment('TAHUNMSMHS - Tahun Masuk');
            $table->string('semester_awal', 5)->nullable()->comment('SMAWLMSMHS - Semester Awal');
            $table->string('batas_studi', 5)->nullable()->comment('BTSTUMSMHS - Batas Studi');
            $table->string('kode_provinsi_asal', 2)->nullable()->comment('ASSMAMSMHS - Kode Provinsi asal pendidikan terakhir');
            $table->date('tanggal_masuk')->nullable()->comment('TGMSKMSMHS - Tanggal Masuk');
            $table->date('tanggal_lulus')->nullable()->comment('TGLLSMSMHS - Tanggal Lulus');
            $table->string('status_aktivitas_mahasiswa', 1)->nullable()->comment('STMHSMSMHS - Kode Status Aktivitas Mahasiswa');
            $table->string('status_awal_mahasiswa', 1)->nullable()->comment('STPIDMSMHS - Kode Status Awal Mahasiswa');
            $table->unsignedSmallInteger('sks_diakui')->default(0)->comment('SKSDIMSMHS - Jumlah SKS diakui bagi mahasiswa pindahan');
            $table->string('nim_asal', 30)->nullable()->comment('ASNIMMSMHS - NIM asal dari Perguruan Tinggi sebelumnya');
            $table->string('kode_pt_asal', 6)->nullable()->comment('ASPTIMSMHS - Kode Perguruan Tinggi sebelumnya');
            $table->string('kode_jenjang_asal', 2)->nullable()->comment('ASJENMSMHS - Kode Jenjang Program Studi sebelumnya');
            $table->string('kode_prodi_asal', 5)->nullable()->comment('ASPSTMSMHS - Kode Program Studi sebelumnya');
            $table->string('kode_biaya_studi', 2)->nullable()->comment('BISTUMSMHS - Kode Biaya Studi');
            $table->string('kode_pekerjaan', 2)->nullable()->comment('PEKSBMSMHS - Kode Pekerjaan');
            $table->string('nama_tempat_bekerja', 150)->nullable()->comment('NMPEKMSMHS - Nama Tempat Bekerja');
            $table->string('kode_pt_tempat_bekerja', 6)->nullable()->comment('PTPEKMSMHS - Kode PT Tempat Bekerja');
            $table->string('kode_prodi_tempat_bekerja', 5)->nullable()->comment('PSPEKMSMHS - Kode PS Tempat Bekerja');
            $table->string('nidn_promotor', 20)->nullable()->comment('NMPRMMSMHS - NIDN Promotor');
            $table->string('nidn_ko_promotor_1', 20)->nullable()->comment('NOKP1MSMHS - NIDN Ko-Promotor #1');
            $table->string('nidn_ko_promotor_2', 20)->nullable()->comment('NOKP2MSMHS - NIDN Ko-Promotor #2');
            $table->string('nidn_ko_promotor_3', 20)->nullable()->comment('NOKP3MSMHS - NIDN Ko-Promotor #3');
            $table->string('nidn_ko_promotor_4', 20)->nullable()->comment('NOKP4MSMHS - NIDN Ko-Promotor #4');
            $table->timestamps();

            $table->index(['kode_perguruan_tinggi', 'kode_program_studi']);
            $table->index('status_aktivitas_mahasiswa');
            $table->index('tahun_masuk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('msmhs');
    }
};
