<?php

namespace App\Imports;

use App\Pegawai;
use Maatwebsite\Excel\Concerns\ToModel;

class PegawaiImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pegawai([
            'nama' => $row[1],
            'alamat' => $row[2],
            'email' => $row[3],
            'no_telpon' => $row[4],
            'jenis_kelamin' => $row[5],
        ]);
    }
}
