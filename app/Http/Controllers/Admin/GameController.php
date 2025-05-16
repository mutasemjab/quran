<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class GameController extends Controller
{

    public function index(Request $request)
    {
        // Start the query
        $query = Game::query();

        // Apply search filtering
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where(\DB::raw('CONCAT_WS(" ", `name`)'), 'like', '%' . $request->search . '%');
            });
        }

        // Paginate the results
        $data = $query->paginate(PAGINATION_COUNT);

        // Capture the search query
        $searchQuery = $request->search;

        // Return the view with data
        return view('admin.games.index', compact('data', 'searchQuery'));
    }



    public function create()
    {
        return view('admin.games.create');
    }



    public function store(Request $request)
    {
        try {
            $game = new game();
            $game->name = $request->get('name');
            $game->url = $request->get('url');

            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $game->photo = $the_file_path;
            }

            if ($game->save()) {

                return redirect()->route('games.index')->with(['success' => 'game created']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }





    public function edit($id)
    {
        if (auth()->user()->can('game-edit')) {
            $data = Game::findOrFail($id);
            return view('admin.games.edit', compact('data'));
        } else {
            return redirect()->back()->with('error', "Access Denied");
        }
    }


    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);

        try {

            $game->name = $request->get('name');
            $game->url = $request->get('url');

            if ($request->has('photo')) {
                $filePath = base_path('assets/admin/uploads/' . $game->photo);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $game->photo = $the_file_path;
            }


            if ($game->save()) {


                return redirect()->route('games.index')->with(['success' => 'game updated']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();
        return redirect()->route('games.index');
    }
}
