<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KelolaAkunController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $users = User::orderBy('role', 'asc')->orderBy('nama_lengkap', 'asc')->paginate(10);
        return view('admin.kelola-akun', compact('users'));
    }

    public function getData(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where('username', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_lengkap', 'like', '%' . $request->search . '%');
        }

        if ($request->role && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('role', 'asc')->orderBy('nama_lengkap', 'asc')->paginate(10);
        
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'nama_lengkap' => 'required|string|max:100',
            'role' => 'required|in:admin,kasir'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'role' => $request->role
        ]);

        return response()->json(['success' => true, 'message' => 'Akun berhasil ditambahkan', 'data' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')->ignore($id, 'id_user')
            ],
            'nama_lengkap' => 'required|string|max:100',
            'role' => 'required|in:admin,kasir'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user->update([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'role' => $request->role
        ]);

        return response()->json(['success' => true, 'message' => 'Akun berhasil diupdate', 'data' => $user]);
    }

    public function resetPassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['success' => true, 'message' => 'Password berhasil direset']);
    }

    public function changeSelfPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|string|same:new_password'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Password saat ini salah'], 422);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['success' => true, 'message' => 'Password berhasil diubah']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Cegah menghapus akun sendiri
        if ($user->id_user == auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri'], 422);
        }

        $user->delete();
        return response()->json(['success' => true, 'message' => 'Akun berhasil dihapus']);
    }
}