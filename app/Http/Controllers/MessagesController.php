<?php

namespace App\Http\Controllers;

use App\Jobs\BulkImportMessages;
use App\Jobs\ImportMessage;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use MadeITBelgium\Spintax\SpintaxFacade as Spintax;

class MessagesController extends Controller
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
        $group = team()->messageGroups()->find(request('message_group_id'));

        if (request()->has('file')) {
            request()->validate([
                'file' => ['required', 'file'],
            ]);

            BulkImportMessages::dispatch($group, request()->file('file')->get());
        } else {
            request()->validate([
                'content' => ['required', 'string'],
            ]);

            $this->generateMessage($group);
        }

        return redirect()->route('message-groups.show', $group);
    }

    public function destroy(Message $message)
    {
        abort_if($message->messageGroup->team_id != team()->id, 401);

        $message->delete();

        return back();
    }

    protected function generateMessage($group)
    {
        if (request('variations') == 'on') {
            $variations = Spintax::parse(request('content'))->getAll();
            $count = $group->messages()->count();
            $available = $group->availableMessagesCount();

            if ($count > $available) {
                throw ValidationException::withMessages([
                    'count' => "Please add an amount of messages lower than {$available}",
                ]);
            }

            foreach ($variations as $variation) {
                ImportMessage::dispatch($group, $variation);
            }

            return;
        }

        $group->messages()->create([
            'content' => request('content'),
        ]);
    }
}
