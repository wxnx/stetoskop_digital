<?php

namespace App\Http\Controllers\MachineLearning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Pasiens\PasienController;
use App\Models\MachineLearning;
use App\Models\Pasien;
use App\Models\User;

class MLController extends Controller
{
    protected $PasienController;
    public function __construct(PasienController $PasienController)
    {
        $this->PasienController = $PasienController;
    }

    public function runml(Request $request)
    {
        ini_set('max_execution_time', 300);
        if (Storage::disk('public')->exists("uploads/$request->name")) {
            $data = Storage::disk('public')->path("uploads/$request->name");
            $model = 'C:\xampp\htdocs\stetoskop_digital\model.tflite';
            $python = 'C:/Users/WINORA~1/AppData/Local/Programs/Python/Python38/python.exe';
            $script = escapeshellcmd(base_path('/ml_scrypt.py'));
            $command = "$python $script $data $model";
            ob_start();
            $output = exec($command);
            $result = json_decode($output, true);
            $z = dump($result["z"]);
            $y = dump($result["y"]);
            $x = dump($result["x"]);
            MachineLearning::where('name', $request->name)->update([
                'result' => $z
            ]);
            if ($z == '0') {
                $as = 'True';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'False';
                $n = 'False';
            } elseif ($z == '1') {
                $as = 'False';
                $mr = 'True';
                $ms = 'False';
                $mvp = 'False';
                $n = 'False';
            } elseif ($z == '2') {
                $as = 'False';
                $mr = 'False';
                $ms = 'True';
                $mvp = 'False';
                $n = 'False';
            } elseif ($z == '3') {
                $as = 'False';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'True';
                $n = 'False';
            } elseif ($z == '4') {
                $as = 'False';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'False';
                $n = 'True';
            } else {
                $as = 'False';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'False';
                $n = 'False';
            }
            $output = ob_get_clean();
            $dataml = MachineLearning::where('name', $request->name)->firstOrfail();
            $datauser = Pasien::where('user_id', $dataml->pasien_id)->firstOrfail();
            $username = User::where('id', $datauser->dokter_id)->get();
            $namafile = $request->name;
            return view('pasien.pasien.result', compact('username', 'namafile', 'as', 'mr', 'ms', 'mvp', 'n', 'y', 'x'));
        }
    }

    public function runIndocnur($id)
    {
        ini_set('max_execution_time', 300);
        $readpasien = MachineLearning::where('id', $id)->firstOrfail();
        if (Storage::disk('public')->exists("uploads/$readpasien->name")) {
            $data = Storage::disk('public')->path("uploads/$readpasien->name");
            $model = 'C:\xampp\htdocs\stetoskop_digital\model.tflite';
            $python = 'C:/Users/WINORA~1/AppData/Local/Programs/Python/Python38/python.exe';
            $script = escapeshellcmd(base_path('/ml_scrypt.py'));
            $command = "$python $script $data $model";
            ob_start();
            $output = exec($command);
            $result = json_decode($output, true);
            $z = dump($result["z"]);
            $y = dump($result["y"]);
            $x = dump($result["x"]);
            MachineLearning::where('name', $readpasien->name)->update([
                'result' => $z
            ]);
            if ($z == '0') {
                $as = 'True';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'False';
                $n = 'False';
            } elseif ($z == '1') {
                $as = 'False';
                $mr = 'True';
                $ms = 'False';
                $mvp = 'False';
                $n = 'False';
            } elseif ($z == '2') {
                $as = 'False';
                $mr = 'False';
                $ms = 'True';
                $mvp = 'False';
                $n = 'False';
            } elseif ($z == '3') {
                $as = 'False';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'True';
                $n = 'False';
            } elseif ($z == '4') {
                $as = 'False';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'False';
                $n = 'True';
            } else {
                $as = 'False';
                $mr = 'False';
                $ms = 'False';
                $mvp = 'False';
                $n = 'False';
            }
            $output = ob_get_clean();
            $username = Pasien::where('user_id', $readpasien->pasien_id)->get();
            $namafile = $readpasien->name;
            return view('dokter.dokter.resultOffline', compact('username', 'namafile', 'as', 'mr', 'ms', 'mvp', 'n', 'y', 'x'));
        }
    }
}
