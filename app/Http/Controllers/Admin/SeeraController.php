<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Seera;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class SeeraController extends Controller
{

    public function index(Request $request)
    {
        // Start the query
        $query = Seera::query();

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
        return view('admin.seeras.index', compact('data', 'searchQuery'));
    }



    public function create()
    {
        return view('admin.seeras.create');
    }



    public function store(Request $request)
    {
        try {
            $Seera = new Seera();
            $Seera->name = $request->get('name');
            $Seera->description = $request->get('description');
            $Seera->description_en = $request->get('description_en');
         
            if ($Seera->save()) {

                return redirect()->route('seeras.index')->with(['success' => 'Seera created']);
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
        if (auth()->user()->can('seera-edit')) {
            $data = Seera::findOrFail($id);
            return view('admin.seeras.edit', compact('data'));
        } else {
            return redirect()->back()->with('error', "Access Denied");
        }
    }


    public function update(Request $request, $id)
    {
        $Seera = Seera::findOrFail($id);

        try {

            $Seera->name = $request->get('name');
            $Seera->description = $request->get('description');
            $Seera->description_en = $request->get('description_en');

            if ($Seera->save()) {
                return redirect()->route('seeras.index')->with(['success' => 'Seera updated']);
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
        $Seera = Seera::findOrFail($id);
        $Seera->delete();
        return redirect()->route('seeras.index');
    }
}
