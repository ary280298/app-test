<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Validator;


class KaryawanController extends Controller
{

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Karyawan::GetDataKaryawan();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '<button data-toggle="tooltip"  
                    data-karyawan_id="' . $row->karyawan_id . '" 
                    data-nama_karyawan="' . $row->nama_karyawan . '" 
                    data-email_aktif="' . $row->email_aktif . '" 
                    data-jenis_kelamin="' . $row->jenis_kelamin . '" 
                    data-nomor_hp="' . $row->nomor_hp . '" 
                    data-salary="' . $row->salary . '" 
                    data-foto_profil="' . $row->foto_profil . '" 
                    class="editData btn btn-primary btn-sm m-1">Edit</a>';
                    $button = $button . ' <button data-karyawan_id="' . $row->karyawan_id . '" class="btn btn-danger btn-sm deleteButton mr-1">Delete</button>';
                    $button = $button . ' <a href="generateWord/' . $row->karyawan_id . '" class="btn btn-primary btn-sm">Generate Word</a>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('karyawan');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama_karyawan' => 'required',
            'email_aktif' => 'required|email|unique:tb_karyawan',
            'jenis_kelamin' => 'required',
            'nomor_hp' => 'required',
            'salary' => 'required',

        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            $nama_karyawan = $request->nama_karyawan;
            $email_aktif = $request->email_aktif;
            $jenis_kelamin = $request->jenis_kelamin;
            $nomor_hp = $request->nomor_hp;
            $salary = $request->salary;
            $foto_profil =  $request->file('foto_profil');
            $extension =  $request->file('foto_profil')->extension();

            $rand_img = date('ysm') .  str_replace(" ", "", $nama_karyawan) . '.' . $extension;
            $foto_profil->move(\base_path() . "/public/assets/karyawan-img/", $rand_img);

            //insert data karyawan
            $karyawan = new Karyawan();
            DB::beginTransaction();
            $karyawan->nama_karyawan = $nama_karyawan;
            $karyawan->email_aktif = $email_aktif;
            $karyawan->jenis_kelamin = $jenis_kelamin;
            $karyawan->nomor_hp = $nomor_hp;
            $karyawan->salary = $salary;
            $karyawan->foto_profil = $rand_img;
            $karyawan->status = 1;
            $data = $karyawan->save();

            if ($data) {
                DB::commit();
                return response()->json(['Message' => 'Data karyawan berhasil ditambahkan'], 200);
            } else {
                DB::rollBack();
                return response()->json(['Message' => 'Data gagal ditambahkan'], 500);
            }
        }
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama_karyawan' => 'required',
            'jenis_kelamin' => 'required',
            'nomor_hp' => 'required',
            'salary' => 'required',

        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {

            $foto_profil =  $request->file('foto_profil');

            //update data karyawan
            $karyawan = new Karyawan();
            DB::beginTransaction();
            if ($foto_profil == null) {
                $karyawan = array(
                    'nama_karyawan' => $request->nama_karyawan,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'nomor_hp' => $request->nomor_hp,
                    'salary' => $request->salary
                );

                $update = Karyawan::where('karyawan_id', $request->karyawan_id)->update($karyawan);
            } else {
                $extension =  $request->file('foto_profil')->extension();
                $rand_img = date('ysm') .  str_replace(" ", "", $request->nama_karyawan) . '.' . $extension;
                $foto_profil->move(\base_path() . "/public/assets/karyawan-img/", $rand_img);

                $karyawan = array(
                    'nama_karyawan' => $request->nama_karyawan,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'nomor_hp' => $request->nomor_hp,
                    'salary' => $request->salary,
                    'foto_profil' => $rand_img
                );
                $update = Karyawan::where('karyawan_id', $request->karyawan_id)->update($karyawan);
            }


            if ($update) {
                DB::commit();
                return response()->json(['Message' => 'Data karyawan berhasil diubah'], 200);
            } else {
                DB::rollBack();
                return response()->json(['Message' => 'Data gagal diubah'], 500);
            }
        }
    }

    public function delete(Request $request)
    {

        DB::beginTransaction();
        $update = Karyawan::where('karyawan_id', $request->karyawan_id)->update(array('status' => 0));

        if ($update) {
            DB::commit();
            return response()->json(['Message' => 'Data karyawan berhasil dihapus'], 200);
        } else {
            DB::rollBack();
            return response()->json(['Message' => 'Data gagal dihapus'], 500);
        }
    }

    public function generateDocx($id)
    {

        $karyawan = Karyawan::getDataDetail($id);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();
        $img = url('assets/karyawan-img') . '/' . $karyawan->foto_profil;

        $styleCell = array('borderTopSize' => 1, 'borderTopColor' => 'black', 'borderBottomColor' => 'black', 'borderLeftSize' => 1, 'borderBottomSize' => 1, 'borderLeftColor' => 'black', 'borderRightSize' => 1, 'borderRightColor' => 'black', 'borderTopSize' => 1, 'marginBottom' => 1, 'borderBottomColor' => 'black');
        $table = $section->addTable('myOwnTableStyle', array('borderSize' => 1, 'borderColor' => '999999', 'afterSpacing' => 0, 'Spacing' => 0, 'cellMargin' => 1));
        $table->addRow(-0.5, array('exactHeight' => -1));
        $table->addCell(2500, $styleCell)->addText('Field');
        $table->addCell(6000, $styleCell)->addText('Value');
        $table->addRow(-0.5, array('exactHeight' => -1));
        $table->addCell(2500, $styleCell)->addText('Nama');
        $table->addCell(6000, $styleCell)->addText((string)$karyawan->nama_karyawan);
        $table->addRow(-0.5, array('exactHeight' => -1));
        $table->addCell(2500, $styleCell)->addText('Jenis Kelamin');
        $table->addCell(6000, $styleCell)->addText((string)$karyawan->jenis_kelamin);
        $table->addRow(-0.5, array('exactHeight' => -1));
        $table->addCell(2500, $styleCell)->addText('Nomor HP');
        $table->addCell(6000, $styleCell)->addText((string)$karyawan->nomor_hp);
        $table->addRow(-0.5, array('exactHeight' => -1));
        $table->addCell(2500, $styleCell)->addText('Email Aktif');
        $table->addCell(6000, $styleCell)->addText((string)$karyawan->email_aktif);
        $table->addRow(-0.5, array('exactHeight' => -1));
        $table->addCell(2500, $styleCell)->addText('Current Salary');
        $table->addCell(6000, $styleCell)->addText(number_format($karyawan->salary));
        $table->addRow(-0.5, array('exactHeight' => -1));
        $table->addCell(2500, $styleCell)->addText('Foto Profil');
        $table->addCell(6000, $styleCell)->addText($img);


        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        try {
            $objWriter->save(storage_path('helloWorld.docx'));
        } catch (Exception $e) {
        }

        return response()->download(storage_path('karyawan.docx'));
    }
}
