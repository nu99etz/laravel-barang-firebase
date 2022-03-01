<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Factory;
use Jenssegers\Agent\Agent;

class BarangController extends Controller
{

    protected $auth;
    protected $database;


    /**
     * Init Firebase Realtime Database Configure
     * 
     */
    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/config-firebase.json')
            ->withDatabaseUri('https://decoded-indexer-311609-default-rtdb.asia-southeast1.firebasedatabase.app/');

        $this->auth = $factory->createAuth();
        $this->database = $factory->createDatabase();
    }

    /**
     * Init DataTable Barang
     * 
     */
    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $barang = Barang::query();
            return datatables($barang)
                ->addIndexColumn()
                ->editColumn('harga', function ($data) {
                    return "Rp. " . number_format($data->harga);
                })
                ->editColumn('action', function ($data) {
                    $html = '';
                    if (Auth::user()) {
                        if (Auth::user()->role == 1 || Auth::user()->role == 2) {
                            $html .= '<button type="button" name="update" action="' . route('barang.edit', $data->id) . '" class="update btn btn-warning btn-xs" data-toggle="tooltip" data-placement="bottom" title="Ubah Data"><i class = "fa fa-pencil"></i> Ubah Data</button> ';
                        }
                        if (Auth::user()->role == 1) {
                            $html .= '<button type="button" name="delete" action="' . route('barang.destroy', $data->id) . '" class="delete btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Hapus Data"><i class = "fa fa-trash"></i> Hapus Data</button> ';
                        }
                    }
                    $html .= '<button type="button" name="read" action="' . route('barang.show', $data->id) . '" class="read btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Hapus Data"><i class = "fa fa-trash"></i> Lihat Data</button>';
                    return $html;
                })
                ->escapeColumns([])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('barang');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $data = [
            'action' => route('barang.store'),
        ];
        return view('form_barang', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
        ];

        $rules = [
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric'
        ];

        $errors = Validator::make($request->all(), $rules, $messages);

        if ($errors->fails()) {
            return response()->json([
                'status' => 422,
                'messages' => $errors->errors()->all()
            ]);
        }

        $request->request->add([
            'kode_barang' => strtoupper($request->kode_barang),
            'nama_barang' => ucwords($request->nama_barang),
            'stok' => $request->stok,
            'harga' => $request->harga,
        ]);

        // Cek apakah ada barang yang sama

        $barang = Barang::where('kode_barang', $request->kode_barang)->first();

        if ($barang) {
            return response()->json([
                'status' => 422,
                'messages' => ['Barang sudah ada']
            ]);
        }

        DB::beginTransaction();

        try {
            Barang::create($request->all());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 422,
                'messages' => ['Terjadi kesalahan saat memasukkan data ' . $e->getMessage()],
            ]);
        }

        // Insert Log To Firebase
        $agent = new Agent();

        try {
            $this->database->getReference('log_barang/insert/' . $request->kode_barang)->set(([
                'user' => Auth::user()->username,
                'insert' => date('d-M-Y H:i:s'),
                'agent' => [
                    'os' => $agent->platform(),
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'browser' => $agent->browser()
                ],
                'param' => [
                    'kode_barang' => $request->kode_barang,
                    'nama_barang' => $request->nama_barang,
                    'stok' => $request->stok,
                    'harga' => 'Rp. ' . number_format($request->harga)
                ]
            ]));
        } catch (Exception $e) {
            return response()->json([
                'status' => 422,
                'messages' => ['Terjadi kesalahan saat memasukkan data ' . $e->getMessage()],
            ]);
        }

        DB::commit();
        return response()->json([
            'status' => 200,
            'messages' => 'Barang sukses di input'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

        $barang = Barang::where('id', $id)->first();
        $data = [
            'barang' => $barang,
            'readonly' => 'readonly',
            'action' => '#'
        ];
        return view('form_barang', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $barang = Barang::where('id', $id)->first();
        $data = [
            'barang' => $barang,
            'action' => route('barang.update', $id)
        ];
        return view('form_barang', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
        ];

        $rules = [
            'kode_barang' => 'required',
            'nama_barang' => 'required',
            'stok' => 'required|numeric',
            'harga' => 'required|numeric'
        ];

        $errors = Validator::make($request->all(), $rules, $messages);

        if ($errors->fails()) {
            return response()->json([
                'status' => 422,
                'messages' => $errors->errors()->all()
            ]);
        }

        $request->request->add([
            'kode_barang' => strtoupper($request->kode_barang),
            'nama_barang' => ucwords($request->nama_barang),
            'stok' => $request->stok,
            'harga' => $request->harga,
        ]);

        // Cek apakah ada barang yang sama

        if ($request->kode_barang != $request->kode_barang_edit) {
            $barang = Barang::where('kode_barang', $request->kode_barang)->first();

            if ($barang) {
                return response()->json([
                    'status' => 422,
                    'messages' => ['Barang sudah ada']
                ]);
            }
        }

        DB::beginTransaction();

        try {
            $barang = Barang::where('id', $id)->first();
            $barang->update($request->all());
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 422,
                'messages' => ['Terjadi kesalahan saat memasukkan data ' . $e->getMessage()],
            ]);
        }

        // Insert Log To Firebase

        $agent = new Agent();

        try {
            $this->database->getReference('log_barang/update/' . $request->kode_barang)->set(([
                'user' => Auth::user()->username,
                'update' => date('d-M-Y H:i:s'),
                'agent' => [
                    'os' => $agent->platform(),
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'browser' => $agent->browser()
                ],
                'param' => [
                    'kode_barang' => $request->kode_barang,
                    'nama_barang' => $request->nama_barang,
                    'stok' => $request->stok,
                    'harga' => 'Rp. ' . number_format($request->harga)
                ]
            ]));
        } catch (Exception $e) {
            return response()->json([
                'status' => 422,
                'messages' => ['Terjadi kesalahan saat memasukkan data ' . $e->getMessage()],
            ]);
        }


        DB::commit();
        return response()->json([
            'status' => 200,
            'messages' => 'Barang sukses di input'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

        $barang = Barang::where('id', $id)->first();

        // Insert Log To Firebase

        $agent = new Agent();

        try {
            $this->database->getReference('log_barang/delete/' . $barang->kode_barang)->set(([
                'user' => Auth::user()->username,
                'delete' => date('d-M-Y H:i:s'),
                'agent' => [
                    'os' => $agent->platform(),
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'browser' => $agent->browser()
                ],
                'param' => [
                    'kode_barang' => $barang->kode_barang,
                    'nama_barang' => $barang->nama_barang,
                    'stok' => $barang->stok,
                    'harga' => 'Rp. ' . number_format($barang->harga)
                ]
            ]));
        } catch (Exception $e) {
            return response()->json([
                'status' => 422,
                'messages' => ['Terjadi kesalahan saat hapus data ' . $e->getMessage()],
            ]);
        }

        $barang->delete();
        return response()->json([
            'status' => 200,
            'messages' => 'Data Sukses Dihapus'
        ]);
    }
}
