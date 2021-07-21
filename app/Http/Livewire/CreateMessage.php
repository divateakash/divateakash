<?php

namespace App\Http\Livewire;

use App\MessageGroup;
use App\Support\Spintax;
use Livewire\Component;
use MadeITBelgium\Spintax\SpintaxFacade;

class CreateMessage extends Component
{
    public $messageGroup;

    public $content = '';

    public $maxChars;

    public function mount(MessageGroup $messageGroup)
    {
        $this->fill([
            'messageGroup' => $messageGroup,
            'maxChars' => config('konnectext.max_chars'),
        ]);
    }

    public function render()
    {
        return view('livewire.create-message');
    }

    public function getCharacterCountProperty()
    {
        return strlen(Spintax::longest($this->content));
    }

    public function getOverMaxCharsProperty()
    {
        return $this->getCharacterCountProperty() > $this->maxChars;
    }

    public function getVariationsCountProperty()
    {
        return count(SpintaxFacade::parse($this->content)->getAll());
    }

    public function getOverMaxMessagesProperty()
    {
        return $this->getVariationsCountProperty() > $this->messageGroup->availableMessagesCount();
    }

    public function addMessage()
    {
        $this->messageGroup->messages()->create(['content' => $this->content]);

        return redirect()->route('message-groups.show', $this->messageGroup);
    }
}
