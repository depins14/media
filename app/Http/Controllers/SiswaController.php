<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use DataTables;

class SiswaController extends Controller
{
    public function index()
    {
        return view('admin.siswa');
    }

    public function addSiswa(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'nis' => 'required',
            'nama' => 'required',
            'kelas' => 'required',
            'password' => 'required',
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }else {
            $siswa = new Siswa();

            $siswa->nis = $request->nis;
            $siswa->nama = $request->nama;
            $siswa->kelas = $request->kelas;
            $siswa->password_siswa = $request->password;
            $siswa->password = password_hash($request->password, PASSWORD_DEFAULT);
            
            $query = $siswa->save();

            if(!$query){
                return response()->json(['code'=>0,'msg'=>'Gagal menambahkan data']);
            }else{
                return response()->json(['code'=>1,'msg'=>'Berhasil menambahkan data']);
            }
        }
    }

    public function getSiswa()
    {
        $siswa = Siswa::all();

        return DataTables::of($siswa)
                    ->addIndexColumn()
                    ->addColumn('actions', function($row){
                        return '<div class="btn-group">
                                    <button class="btn btn-sm btn-primary" data-id="'.$row['id'].'" id="editSiswa">Update</button>
                                    <button class="btn btn-sm btn-danger" data-id="'.$row['id']. '" id="deleteSiswa">Delete</button>
                                </div>';
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
    }

    public function detailSiswa(Request $request)
    {
        $siswa_id = $request->siswa_id;
        $siswaDetails = Siswa::find($siswa_id);
        return response()->json(['details'=>$siswaDetails]);
    }

    public function updateSiswa(Request $request)
    {
        $siswa_id = $request->id;

        $validator = \Validator::make($request->all(), [
            'nis' => 'required|unique:siswas,nis,'.$siswa_id,
            'nama' => 'required',
            'kelas' => 'required',
            'password_siswa' => 'required',
        ]);

        if(!$validator->passes()){
            return response()->json(['code'=>0, 'error'=>$validator->errors()->toArray()]);
        }else {
            $siswa = Siswa::find($siswa_id);
            $siswa->nama = $request->nama;
            $siswa->kelas = $request->kelas;
            $siswa->password_siswa = $request->password_siswa;
            $siswa->password = password_hash($request->password_siswa, PASSWORD_DEFAULT);

            $query = $siswa->save();

            if($query){
                return response()->json(['code'=>1, 'msg'=>'Berhasil mengubah data']);
            }else{
                return response()->json(['code'=>0, 'msg'=>'Gagal mengubah data']);
            }
        }
    }

    public function deleteSiswa(Request $request)
    {
        $siswa_id = $request->siswa_id;
        $query = Siswa::find($siswa_id)->delete();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'Berhasil menghapus data']);
        }else{
            return response()->json(['code'=>0, 'msg'=>'Gagal menghapus data']);
        }
    }
}
