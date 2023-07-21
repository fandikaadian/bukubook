<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.index', [
            'users' => User::paginate(2)
        ]);
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required'],
            'email'     => ['required', 'email', 'unique:users'],
            'password'  => ['required', 'min:8', 'confirmed']
        ]);

        //dd($request->all());
        //ambil inputan dan simpan ke variabel
        $name = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');

        //simpan ke database lewat model User
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password); //untuk hashing ke bcrypt
        $user->save();

        //redirect ke halaman index
        return redirect()->route('user.index')
            ->with('success', 'Data Berhasil Ditambahkan!');
    }

    public function edit($id)
    {
        //cek apakah kita sudah menerima id
        //dd($id);

        // Mengembalikan ke view edit beserta data user
        // yang akan di edit
        return view('user.edit', [
            'user' => User::find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => ['required'],
             //perlu id untuk exclude unique apabila memang tidak mengubah email
            'email'     => ['required', 'email', 'unique:users,email,' . $id],
            'password'  => ['nullable', 'min:8', 'confirmed']
        ]);

        //ambil inputan dan simpan ke variabel
        $password = $request->get('password');

        //try catch untuk savety handling
        try {
            //code...
            $user = User::find($id);
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->telephone = $request->get('email'); //error here
            if(!empty($password)){
                //ubah password hanya ketika diisi
                $user->password = Hash::make($password); //untuk hashing ke bcrypt
            }
            $user->save();

            //redirect ke halaman index
            return redirect()
                    ->route('user.index')
                    ->with('success', 'Data Berhasil Diubah!');
        } catch (\Exception $e) {
            return redirect()
                    ->route('user.index')
                    ->with('error', $e->getMessage());
        }

        //simpan ke database lewat model User
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()
            ->route('user.index')
            ->with('success', 'Data Berhasil Dihapus!');
    }
}
