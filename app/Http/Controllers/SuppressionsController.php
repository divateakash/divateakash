<?php

namespace App\Http\Controllers;

use App\Jobs\SuppressLeadsByPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use League\Csv\Reader;

class SuppressionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('suppressions.index', [
            'suppressions' => team()
                ->suppressions()
                ->when(request('phone'), fn($query) => $query->where('phone', number(request('phone'))))
                ->paginate(50),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    public function file()
    {
        request()->validate([
            'file' => ['required'],
        ]);

        $file = request()->file('file');

        $csv = Reader::createFromString($file->get());

        foreach ($csv->getRecords() as $record) {
            $record = is_array($record) ? Arr::first($record) : $record;

            SuppressLeadsByPhone::dispatch($record, team());
        }

        return back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        team()->suppressions()->findOrFail($id)->delete();

        return back();
    }
}
