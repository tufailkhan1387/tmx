<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        Note::create([
            'created_by' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Note added successfully.');
    }
}
