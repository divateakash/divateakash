<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageGroupsControllerStoreRequest;
use App\Http\Requests\MessageGroupStoreRequest;
use App\MessageGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MessageGroupsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = team()->messageGroups()->withCount('messages', 'deletedMessages')->get();

        return view('message_groups.index', compact('groups'));
    }

    public function create()
    {
        return $this->edit(new MessageGroup);

        return view('message_groups.create');
    }

    public function edit(MessageGroup $messageGroup)
    {
        Gate::authorize('view', $messageGroup);

        return view('message_groups.edit', compact('messageGroup'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\MessageGroup $messageGroup
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, MessageGroup $messageGroup)
    {
        $messages = $messageGroup->messages()->withTrashed()->get();

        return view('message_groups.show', compact('messageGroup', 'messages'));
    }

    /**
     * @param \App\Http\Requests\MessageGroupsControllerStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(MessageGroupStoreRequest $request)
    {
        $data = $request->only(['name', 'type']);

        $messageGroup = team()->messageGroups()->create($data + [
            'single_message_only' => $request->boolean('single_message_only'),
        ]);

        return redirect()->route('message-groups.show', [$messageGroup]);
    }

    /**
     * @param \App\Http\Requests\MessageGroupsControllerStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(MessageGroup $messageGroup, MessageGroupStoreRequest $request)
    {
        Gate::authorize('view', $messageGroup);

        $data = $request->only(['name', 'type']);

        $messageGroup->update($data + [
            'single_message_only' => $request->boolean('single_message_only'),
        ]);

        return redirect()->route('message-groups.show', [$messageGroup]);
    }

    public function showCreateMessageForm(MessageGroup $messageGroup)
    {
        return view('message_groups.messages.create', compact('messageGroup'));
    }
}
