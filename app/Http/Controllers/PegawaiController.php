<?php

namespace App\Http\Controllers;

use App\Imports\PegawaiImport;
use Illuminate\Http\Request;
use App\Pegawai;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class PegawaiController extends Controller
{
    public function index(){
        return view('pegawai.index');
    }

    public function getDataPegawai(){
        $pegawai = Pegawai::select('nama', 'alamat', 'email', 'no_telpon', 'jenis_kelamin')->get();

        return response()->json(['pegawai'=>$pegawai]);
    }

    public function importDataPegawai(Request $request){
        $request->validate([
            'file'=>'required|mimes:csv,txt,xls,xlsx'
        ]);

        $file = $request->file('file');
 
		// membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();
 
		// upload ke folder file_siswa di dalam folder public
		$file->move(public_path('pegawai_file'),$nama_file);
 
		// import data
        $pegawai = new PegawaiImport;
		Excel::import($pegawai, public_path('pegawai_file/'.$nama_file));

        return response()->json($pegawai);
    }

    public function clearDataPegawai(){

        $pegawai = Pegawai::all();
        $sum = $pegawai->count();
        
        if($sum > 0){
            $pegawai->each->delete();
        }

        return response()->json(['pegawai'=>$pegawai, 'count'=>$sum]);
    }

    public function download_template(){
        return Response::download(public_path('template_file/daftar_pegawai.xlsx'), 'contoh_daftar_pegawai.xlsx');
    }

}
