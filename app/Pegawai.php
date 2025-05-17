<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'nama', 
        'alamat', 
        'email', 
        'no_telpon', 
        'jenis_kelamin'
    ];
}
