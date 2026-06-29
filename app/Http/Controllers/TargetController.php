<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json([
            'data' => Target::all()
        ]);
    }

    // CREATE
    public function store(Request $request)
    {
        $validated = $request->validate([

            'user_id' => 'required',

            'periode' => 'required',

            'target_nominal' => 'required|numeric'
        ]);

        $target = Target::create($validated);

        return response()->json([

            'message' => 'Target berhasil ditambahkan',

            'data' => $target
        ]);
    }

    // DETAIL
    public function show($id)
    {
        return response()->json([
            'data' => Target::findOrFail($id)
        ]);
    }

    // UPDATE
    public function update(
        Request $request,
        $id
    ) {

        $target = Target::findOrFail($id);

        $validated = $request->validate([

            'periode' => 'required',

            'target_nominal' => 'required|numeric'
        ]);

        $target->update($validated);

        return response()->json([

            'message' => 'Target berhasil diupdate',

            'data' => $target
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        Target::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Target berhasil dihapus'
        ]);
    }
}