<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Services\ModerationHeuristic;

class ItemController extends Controller
{
    public function store(Request $request) //POST
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $heuristic = app(ModerationHeuristic::class)->evaluate(
            $validated['title'],
            $validated['content']
        );

        $item = Item::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'state' => 'pending',
            'risk_score' => $heuristic['risk_score'],
            'flags' => $heuristic['flags'],
        ]);

        return response()->json([
            'item' => $item,
            'suggested_action' => $heuristic['suggested_action'],
        ], 201);
    }

    public function index(Request $request)
    {

        $query = \App\Models\Item::query();

        if ($request->filled('state')) {
            $query->where('state', $request->string('state'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }

        $sort = $request->input('sort', 'created_at');
        $dir  = $request->input('dir', 'desc');

        $allowedSort = ['created_at', 'risk_score'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'created_at';
        }
        $dir = $dir === 'asc' ? 'asc' : 'desc';

        $items = $query->orderBy($sort, $dir)->get();

        return response()->json($items);
    }

    public function review(Request $request, $id)
    {
        $item = \App\Models\Item::findOrFail($id);

        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'note'   => 'nullable|string',
        ]);

        $item->state = $validated['action'] === 'approve'
            ? 'approved'
            : 'rejected';

        $item->review_note = $validated['note'] ?? null;
        $item->reviewed_at = now();

        $item->save();

        return response()->json($item);
    }
}
