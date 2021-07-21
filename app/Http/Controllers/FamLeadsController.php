<?php

namespace App\Http\Controllers;

use App\FamFile;
use App\Jobs\ProcessFamLeads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FamLeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('fam_leads.index', [
            'files' => FamFile::latest()->get(),
        ]);
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
        $file = request()->file('file');

        $name = "{$file->getClientOriginalName()}_input.csv";
        $path = 'fam_leads/' . $name;

        $file->storeAs('fam_leads', $name);

        ProcessFamLeads::dispatch($path, $file->getClientOriginalName())->onqueue('process-fam-leads');

        cache()->forget("last_fam_path");

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download(FamFile $file)
    {
        return Storage::download($file->path);
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
