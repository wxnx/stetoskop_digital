<?php

namespace App\Http\Controllers\Pasiens;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pasien;
use App\Models\MachineLearning;
use Illuminate\Support\Facades\Storage;

class PasienController extends Controller
{
    public function index()
    {
        return view('pasien.pasien.dashboard');
    }

    public function user()
    {
        $active = User::find(Auth::id());
        return User::where('id', $active)->get();
    }

    public function addDocNur()
    {
        $addDocNur = User::where('role_id', '2')->get();

        return view('pasien.pasien.addDocNur', compact('addDocNur'));
    }

    public function ownCheck()
    {
        $activeUser = User::find(Auth::id());
        $dataSignal = MachineLearning::where('pasien_id', $activeUser->id)->get();
        return view('pasien.pasien.owncheck', compact('dataSignal'));
    }

    public function store(Request $request)
    {
        $activeUser = User::find(Auth::id());

        Pasien::create([
            'user_id' => $activeUser->id,
            'name' => $activeUser->name,
            'gender' => $activeUser->gender,
            'address' => $activeUser->address,
            'email' => $activeUser->email,
            'phonenumber' => $activeUser->phonenumber,
            'dokter_id' => $request->id,
        ])->save();

        return redirect()->back();
    }

    public function uploadSignal(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:audio/mpeg,mpga,mp3,wav,aac'
        ]);

        $activeUser = User::find(Auth::id());

        $fileModel = new MachineLearning;

        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');

            $fileModel->pasien_id = $activeUser->id;
            $fileModel->name = time() . '_' . $request->file->getClientOriginalName();
            $fileModel->file_path = '/storage/' . $filePath;
            $fileModel->save();

            return back()
                ->with('success', 'File has been uploaded.')
                ->with('file', $fileName);
        }
    }

    public function dataML()
    {
        $activeUser = User::find(Auth::id());
        return MachineLearning::where('pasien_id', $activeUser->id)->get();
    }

    public function result($id)
    {
        ini_set('max_execution_time', 300);
        $readpasien = MachineLearning::where('id', $id)->firstOrfail();
        if (Storage::disk('public')->exists("uploads/$readpasien->name")) {
            $data = Storage::disk('public')->path("uploads/$readpasien->name");
            $python = 'C:/Users/WINORA~1/AppData/Local/Programs/Python/Python38/python.exe';
            $script = escapeshellcmd(base_path('/graph.py'));
            $command = "$python $script $data";
            ob_start();
            $output = exec($command);
            $result = json_decode($output, true);
            $y = dump($result["y"]);
            $x = dump($result["x"]);
            $z = $readpasien->result;
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
            $datauser = Pasien::where('user_id', $readpasien->pasien_id)->firstOrfail();
            $username = User::where('id', $datauser->dokter_id)->get();
            $namafile = $readpasien->name;
            return view('pasien.pasien.result', compact('username', 'namafile', 'x', 'y', 'as', 'mr', 'ms', 'mvp', 'n'));
        }
    }
}
