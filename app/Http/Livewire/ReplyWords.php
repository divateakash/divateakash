<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ReplyWords extends Component
{
    public $type;

    public $words;

    public $filter;

    public $newWord;

    public function mount($type)
    {
        $this->type = $type;
        $this->loadWords();
    }

    public function render()
    {
        return view('livewire.reply-words');
    }

    public function filterData()
    {
        $this->loadWords();
    }

    public function addWord()
    {
        if (is_null($this->newWord)) {
            return;
        }

        team()->replyWords()->create([
            'word' => $this->newWord,
            'type' => $this->type,
        ]);

        $this->loadWords();
        $this->newWord = null;
    }

    public function deleteWord($id)
    {
        $word = team()->replyWords()->{$this->type}()->find($id);

        optional($word)->delete();
        $this->loadWords();
    }

    public function getColorClassesProperty()
    {
        if ($this->type == 'good') {
            return 'bg-green-100 text-green-800';
        }

        return 'bg-red-100 text-red-800';
    }

    public function loadWords()
    {
        $this->words = team()
            ->replyWords()
            ->{$this->type}()
            ->when($this->filter, fn($query) => $query->where('word', 'LIKE', "%{$this->filter}%"))
            ->get();
    }
}
