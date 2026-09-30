<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PengaturanController extends Controller
{
    public function index()
    {
        $settings = Pengaturan::getAll();
        $users    = User::orderBy('role')->orderBy('name')->get();

        return view('pengaturan.index', compact('settings', 'users'));
    }

    public function update(Request $request)
    {
        $keys = [
            'nama_laundry',
            'slogan',
            'alamat',
            'telepon',
            'email',
            'logo',
            'footer_struk',
            'syarat_ketentuan',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Pengaturan::set($key, $request->input($key, ''));
            }
        }

        // Upload file gambar logo jika diunggah
        if ($request->hasFile('logo_image')) {
            $file = $request->file('logo_image');
            $extension = $file->getClientOriginalExtension();
            $filename = 'logo_' . time() . '.' . $extension;
            $destinationPath = public_path('uploads');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $filename);
            Pengaturan::set('logo', '/uploads/' . $filename);
        }

        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan & Logo berhasil disimpan!');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,kasir,owner',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'no_hp'    => $request->no_hp,
        ]);

        return redirect()->route('pengaturan.index')->with('success', "User {$request->name} berhasil ditambahkan!");
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,kasir,owner',
            'no_hp'    => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('pengaturan.index')->with('success', "Data user {$user->name} berhasil diperbarui!");
    }

    public function destroyUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login!');
        }

        $nama = $user->name;
        $user->delete();

        return redirect()->route('pengaturan.index')->with('success', "User {$nama} berhasil dihapus.");
    }
}
