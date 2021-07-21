<?php

namespace App\Http\Controllers;

use App\FileUpload;
use App\Jobs\ProcessFileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use League\Csv\Reader;

class FileUploadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        request()->validate([
            'catalog_id' => ['required', Rule::exists('catalogs', 'id')->where(fn($query) =>
                $query->where('team_id', team()->id
                ))],
            'file' => ['required', 'file'],
        ]);

        $catalog = team()->catalogs()->find(request('catalog_id'));
        $file = request()->file('file');

        $csv = Reader::createFromString($file->get());
        $csv->setHeaderOffset(0);
        $headers = $csv->getHeader();

        // if (($totalHeaders = count($headers)) != ($uniqueHeaders = collect($headers)->unique()->count())) {
        //     $duplicates = $totalHeaders - $uniqueHeaders;

        //     throw ValidationException::withMessages([
        //         'file' => "Your file contains {$totalHeaders} values in the header, but {$duplicates} are duplicates. Please fix them and upload the file again.",
        //     ]);
        // }

        $name = Str::random(40) . '.csv';
        $path = 'lists/' . $name;

        $file->storeAs('lists', $name);

        $fileUpload = $catalog->fileUploads()->create([
            'name' => $file->getClientOriginalName(),
            'headers' => array_map('trim', $csv->getHeader()),
            'path' => $path,
            'team_id' => team()->id,
        ]);

        return redirect()->route('file-uploads.show', $fileUpload);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FileUpload $fileUpload)
    {
        return view('file_uploads.show', compact('fileUpload'));
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

    public function process(FileUpload $fileUpload)
    {
        $fields = request()->only(['first_name', 'last_name', 'email', 'phone', 'state', 'city']);

        $fileUpload->mapping = $fields;
        $fileUpload->processed_at = now();
        $fileUpload->save();

        ProcessFileUpload::dispatch($fileUpload);

        return redirect()->back();
    }
}
