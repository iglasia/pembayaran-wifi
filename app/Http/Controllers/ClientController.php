<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\{InternetPackage, Position,User};

class ClientController extends Controller
{
    private $path = 'images/clients/house-image/';

    public function __construct(
        private UploadHandlerController $uploadHandlerController
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Client::with('internet_package')->select(
        'id',
        'internet_package_id',
        'name',
        'phone_number',
        'ip_address',
        'longitude',
        'latitude',
        'subscription_status',
        'subscription_ended_at',
        'subscription_reactivated_at',
    );

        // Filter berdasarkan status pembayaran
        if ($request->has('payment_status') && $request->payment_status !== '') {
            if ($request->payment_status === 'paid') {
                $query->whereHas('payments', function($q) use ($request) {
                    if ($request->has('month')) {
                        $q->whereYear('created_at', substr($request->month, 0, 4))
                          ->whereMonth('created_at', substr($request->month, 5, 2));
                    }
                });
            } else {
                $query->whereDoesntHave('payments', function($q) use ($request) {
                    if ($request->has('month')) {
                        $q->whereYear('created_at', substr($request->month, 0, 4))
                          ->whereMonth('created_at', substr($request->month, 5, 2));
                    }
                });
            }
        }

        $clients = $query->latest()->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $internet_packages = InternetPackage::select('id', 'name', 'price')->orderBy('price')->get();
        $positions = Position::select('id', 'name')->orderBy('name')->get();
        return view('clients.create', compact('internet_packages', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function store(StoreClientRequest $request)
    {
        $houseImagePath = null;
        
        try {
            return DB::transaction(function () use ($request, &$houseImagePath) {
                // 1. Upload gambar terlebih dahulu
                $houseImagePath = $this->uploadHandlerController->upload($request, $this->path, 'house_image');


                 // 2. Simpan data user terkait
                $user = User::create([
                    'position_id' => 3,
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password)
                ]);
                
                // 3. Simpan data client
                $client = Client::create([
                    'internet_package_id' => $request->internet_package_id,
                    'name' => $request->name,
                    'ip_address' => $request->ip_address,
                    'phone_number' => $request->phone_number,
                    'house_image' => $houseImagePath,
                    'address' => $request->address,
                    'longitude' => $request->longitude,
                    'latitude' => $request->latitude,
                    'nik' => $request->nik,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'position_id' => 3,
                    'user_id' => $user->id,
                ]);

                return redirect()->route('klien.index')->with('success', 'Data berhasil ditambahkan!');
            });
            
        } catch (\Exception $e) {
            // Rollback file jika ada error
            if ($houseImagePath && Storage::exists($houseImagePath)) {
                Storage::delete($houseImagePath);
            }
            // Kembalikan ke halaman sebelumnya dengan pesan error
            return back()->withInput()
                        ->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    /**
     * Toggle status berlangganan klien
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleSubscription($id)
    {
        $client = Client::findOrFail($id);
        
        if ($client->subscription_status === 'active') {
            // Nonaktifkan berlangganan
            $client->update([
                'subscription_status' => 'inactive',
                'subscription_ended_at' => now()
            ]);
            
            return redirect()->route('klien.index')
                ->with('success', 'Status berlangganan klien berhasil dinonaktifkan!');
        } else {
            // Aktifkan kembali berlangganan
            $client->update([
                'subscription_status' => 'active',
                'subscription_reactivated_at' => now()
            ]);
            
            return redirect()->route('klien.index')
                ->with('success', 'Status berlangganan klien berhasil diaktifkan kembali!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::with('internet_package')->findOrFail($id);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $internet_packages = InternetPackage::select('id', 'name', 'price')->get();

        return view('clients.edit', compact('client', 'internet_packages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, $id)
    {
        $client = Client::findOrFail($id);

        // Jika gambar ada maka update kolom house_image pada database.
        if ($request->file('house_image') !== null) {
            $client->update([
                'house_image' => $this->uploadHandlerController->upload($request, $this->path, 'house_image')
            ]);
        }

        $data = [
            'internet_package_id' => $request->internet_package_id,
            'name' => $request->name,
            'ip_address' => $request->ip_address,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'longitude' => $request->longitude,  
            'latitude' => $request->latitude,
            'nik' => $request->nik,
            'email' => $request->email,
        ];

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $client->update($data);

        return redirect()->route('klien.index')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        if ($client->internet_package->exists()) {
            return redirect()->route('klien.index')->with('warning', 'Data yang masih memiliki relasi tidak dapat dihapus!');
        }

        $client->delete();

        return redirect()->route('klien.index')->with('success', 'Data berhasil dihapus!');
    }

    public function rules()
    {
        return [
            // ... validasi lain ...
            'email' => 'required|email|unique:clients,email,' . $this->route('klien'),
            'password' => 'nullable|min:6',
        ];
    }
}
