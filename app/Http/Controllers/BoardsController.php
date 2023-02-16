<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Inertia\Inertia;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardsController extends Controller
{
    public function index()
    {
        return Inertia::render('Boards/Index', [
            'boards' => auth()->user()->currentTeam->boards
        ]);
    }

    public function show(Board $board, Card $card = null)
    {
        $this->authorize($board->team, 'show');

        $board->load([
            'lists.cards' => fn($query) => $query->orderBy('position')
        ]);

        return Inertia::render('Boards/Show', [
            'board' => $board,
            'card' => $card,
        ]);
    }

    public function update(Board $board)
    {
        $this->authorize($board->team, 'show');

        request()->validate([
            'name' => ['required', 'max:255']
        ]);

        $board->update(['name' => request('name')]);

        return redirect()->back();
    }

    public function store()
    {
        $this->authorize(auth()->user()->currentTeam, 'show');

        request()->validate([
            'name' => ['required']
        ]);

        Board::create([
            'team_id' => auth()->user()->currentTeam->id,
            'user_id' => auth()->id(),
            'name' => request('name')
        ]);

        return redirect()->back();
    }
}
