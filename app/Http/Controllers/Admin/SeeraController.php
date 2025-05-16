<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\Seera;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $classes = Clas::all(); // Fetch all available classes
        return view('admin.seeras.create', compact('classes'));
    }



    public function store(Request $request)
    {
        try {
            $Seera = new Seera();
            $Seera->name = $request->get('name');
            $Seera->description = $request->get('description');
            $Seera->description_en = $request->get('description_en');
            
            if ($Seera->save()) {
                // If classes were selected, save them to the class_serras table
                if($request->has('classes') && !empty($request->classes)) {
                    foreach($request->classes as $classId) {
                        DB::table('class_serras')->insert([
                            'clas_id' => $classId,
                            'seera_id' => $Seera->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
                
                return redirect()->route('seeras.index')->with(['success' => 'Seera created with classes']);
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
            $classes = Clas::all(); // Get all classes
            $selectedClasses = DB::table('class_serras')
                ->where('seera_id', $id)
                ->pluck('clas_id')
                ->toArray(); // Get currently selected classes
                
            return view('admin.seeras.edit', compact('data', 'classes', 'selectedClasses'));
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
                // Delete existing class associations
                DB::table('class_serras')->where('seera_id', $id)->delete();
                
                // Add new class associations
                if($request->has('classes') && !empty($request->classes)) {
                    foreach($request->classes as $classId) {
                        DB::table('class_serras')->insert([
                            'clas_id' => $classId,
                            'seera_id' => $Seera->id,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
                
                return redirect()->route('seeras.index')->with(['success' => 'Seera updated with classes']);
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
