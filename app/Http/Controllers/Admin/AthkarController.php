<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Athkar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class AthkarController extends Controller
{

    public function index(Request $request)
    {
        // Start the query
        $query = Athkar::query();

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
        return view('admin.athkars.index', compact('data', 'searchQuery'));
    }



    public function create()
    {
        return view('admin.athkars.create');
    }



    public function store(Request $request)
    {
        try {
            $athkar = new athkar();
            $athkar->name = $request->get('name');
            $athkar->description = $request->get('description');
            $athkar->description_en = $request->get('description_en');
            if ($request->has('photo')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $athkar->photo = $the_file_path;
            }
            if ($request->has('voice')) {
                $the_file_path = uploadImage('assets/admin/uploads', $request->voice);
                $athkar->voice = $the_file_path;
            }

            if ($athkar->save()) {

                return redirect()->route('athkars.index')->with(['success' => 'Athkar created']);
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
        if (auth()->user()->can('athkar-edit')) {
            $data = Athkar::findOrFail($id);
            return view('admin.athkars.edit', compact('data'));
        } else {
            return redirect()->back()->with('error', "Access Denied");
        }
    }


    public function update(Request $request, $id)
    {
        $athkar = athkar::findOrFail($id);

        try {

            $athkar->name = $request->get('name');
            $athkar->description = $request->get('description');
            $athkar->description_en = $request->get('description_en');

            if ($request->has('photo')) {
                $filePath = base_path('assets/admin/uploads/' . $athkar->photo);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $the_file_path = uploadImage('assets/admin/uploads', $request->photo);
                $athkar->photo = $the_file_path;
            }

            if ($request->has('voice')) {
                $filePath = base_path('assets/admin/uploads/' . $athkar->voice);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
                $the_file_path = uploadImage('assets/admin/uploads', $request->voice);
                $athkar->voice = $the_file_path;
            }

            if ($athkar->save()) {


                return redirect()->route('athkars.index')->with(['success' => 'Athkar updated']);
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
        $athkar = Athkar::findOrFail($id);
        $athkar->delete();
        return redirect()->route('athkars.index');
    }
}
