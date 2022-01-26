<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = 'tb_karyawan';
    protected $fillable =  [
        'karyawan_id', 'nama_karyawan', 'jenis_kelamin', 'nomor_hp', 'email_aktif', 'salary', 'foto_profil',
        'status'

    ];

    static function CekDataEmail($email)
    {
        $data = Karyawan::select('*')
            ->where('email_aktif', '=', $email)
            ->get();
        return $data;
    }

    static function GetDataKaryawan()
    {
        $data = Karyawan::select('*')
            ->where('status', '=', 1)
            ->orderBy('karyawan_id', 'desc');
        return $data;
    }

    static function getDataDetail($id)
    {
        $data = Karyawan::select('*')
            ->where('karyawan_id', '=', $id)
            ->first();
        return $data;
    }
}
