<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdministratorApplicationRequest;
use App\Http\Requests\UpdateAdministratorApplicationRequest;
use Illuminate\Support\Facades\DB;
use App\Models\{User,Admin,Position};
use Illuminate\Http\Request;

class AdministratorApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $administrator_applications = User::with('position')
            ->select('id', 'position_id', 'name', 'email', 'created_at')
            ->where('position_id', '!=', 3)
            ->latest()
            ->get();

        $positions = Position::select('id', 'name')
            ->where('id', '!=', 3)
            ->orderBy('name')
            ->get();


        return view('administrator_applications.index', compact('administrator_applications', 'positions'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(StoreAdministratorApplicationRequest $request)
    {
        DB::beginTransaction();

        try {
            // 1. Buat user baru
            $user = User::create([
            'position_id' => $request->position_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
            ]);

            // 2. Buat record admin terkait
            $position = Position::where('id', $request->position_id)->first();

            Admin::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'description' => $request->name,
            ]);

            DB::commit();

            return redirect()->route('administrator-aplikasi.index')
                ->with('success', 'Data administrator berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
               ->withErrors(['error' => 'Gagal menyimpan data: '.$e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdministratorApplicationRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $admin = $user->admin; // Asumsi ada relasi admin di model User

            // Update data user
            $userData = [
                'position_id' => $request->position_id,
                'name' => $request->name,
                'email' => $request->email
            ];

            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }

            $user->update($userData);

            // Update data admin terkait
            if ($admin) {
                $admin->update([
                    'name' => $request->name,
                    'description' => $request->description ?? $admin->description
                ]);
            }

            DB::commit();

            return redirect()->route('administrator-aplikasi.index')
                ->with('success', 'Data administrator berhasil diubah!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                    ->withErrors(['error' => 'Gagal mengupdate data: '.$e->getMessage()]);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();

        return redirect()->route('administrator-aplikasi.index')->with('success', 'Data berhasil dihapus!');
    }
}
