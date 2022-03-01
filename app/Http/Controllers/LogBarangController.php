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

class LogBarangController extends Controller
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

    public function datatable($chooseLog = '')
    {
        if ($chooseLog == 'insert' || empty($chooseLog)) {
            $log = $this->database->getReference('log_barang/insert')->getValue();
            $chooseLog = 'insert';
        } else if ($chooseLog == 'update') {
            $log = $this->database->getReference('log_barang/update')->getValue();
        } else if ($chooseLog == 'delete') {
            $log = $this->database->getReference('log_barang/delete')->getValue();
        } else {
            $log = [];
        }

        if (!empty($log)) {
            $record = [];
            $no = 1;
            foreach ($log as $key => $value) {
                $row = [];
                $row[] = $no;
                $row[] = json_encode($value['agent']);
                $row[] = $value[$chooseLog];
                $row[] = json_encode($value['param']);
                $row[] = $value['user'];
                $no++;
                $record[] = $row;
            }
        } else {
            $record = [];
        }

        return response()->json([
            'data' => $record
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('log_barang');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }
}
