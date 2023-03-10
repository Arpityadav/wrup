<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    public function store()
    {
        $this->authorize(auth()->user()->currentTeam, 'show');

        request()->validate([
            'board_id' => ['required', 'exists:boards,id'],
            'card_list_id' => ['required', 'exists:card_lists,id'],
            'title' => ['required']
        ]);

        Card::create([
            'board_id' => request('board_id'),
            'card_list_id' => request('card_list_id'),
            'title' => request('title'),
            'user_id' => auth()->id()
        ]);

        return redirect()->back();
    }

    public function update(Card $card)
    {
        $this->authorize(auth()->user()->currentTeam, 'show');

        request()->validate([
            'title' => ['required']
        ]);

        $card->update([
            'title' => request('title'),
            'description' => request()->has('description') ? request('description') : $card->description,
        ]);

        if (request()->has('redirectUrl')) {
            return redirect(request('redirectUrl'));
        }

        return redirect()->back();
    }

    public function move(Card $card)
    {
//        dd($card->board->team->hasUser(auth()->user()));
//        $this->authorize($card->board->team, 'show');
//        $this->authorize(auth()->user()->currentTeam, 'show');

        request()->validate([
            'cardListId' => ['required', 'exists:card_lists,id'],
            'position' => ['required', 'numeric']
        ]);

        $card->update([
            'card_list_id' => request('cardListId'),
            'position' => round(request('position'), 5)
        ]);

        return redirect()->back();
    }

    public function destroy(Card $card)
    {
        $this->authorize(auth()->user()->currentTeam, 'show');

        $card->delete();
        return redirect()->route('boards.show', ['board' => $card->board_id]);
    }
}
