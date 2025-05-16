<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clas;
use App\Models\ClassDate;
use Illuminate\Http\Request;

class ClasController extends Controller
{

    public function index()
    {

        $data = Clas::get();

        return view('admin.classes.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('class-add')) {
            $weekDays = Clas::WEEKDAYS;
            return view('admin.classes.create', compact('weekDays'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {
        if (auth()->user()->can('class-add')) {
            try {
                // Validate the input
                $request->validate([
                    'name' => 'required|string|max:255',
                    'start_date' => 'required|date',
                    'finish_date' => 'required|date|after_or_equal:start_date',
                    'day_ids' => 'required|array',
                    'holidays_ids' => 'array',
                ]);

                // Create a new class
                $class = new Clas();
                $class->name = $request->get('name');
                $class->start_date = $request->get('start_date');
                $class->finish_date = $request->get('finish_date');
                $class->week_days = json_encode($request->get('day_ids'));
                $class->holidays = json_encode($request->get('holidays_ids'));

                if ($class->save()) {
                    // Generate weekly dates
                    $startDate = \Carbon\Carbon::parse($class->start_date);
                    $endDate = \Carbon\Carbon::parse($class->finish_date);

                    while ($startDate->lte($endDate)) {
                        ClassDate::create([
                            'clas_id' => $class->id,
                            'week_date' => $startDate->toDateString(),
                        ]);
                        $startDate->addWeek(); // Increment by one week
                    }

                    return redirect()->route('class.index')->with(['success' => 'Class created with weekly dates generated']);
                } else {
                    return redirect()->back()->with(['error' => 'Something went wrong']);
                }
            } catch (\Exception $ex) {
                return redirect()->back()
                    ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                    ->withInput();
            }
        } else {
            return redirect()->back()->with('error', "Access Denied");
        }
    }


    public function edit($id)
    {
        if (auth()->user()->can('class-edit')) {
            $data = Clas::findOrFail($id);
            $weekDays = Clas::WEEKDAYS;

            return view('admin.classes.edit', compact('data', 'weekDays'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }


    public function update(Request $request, $id)
    {
        if (auth()->user()->can('class-edit')) {
            try {
                // Find the class
                $class = Clas::findOrFail($id);

                // Validate the input
                $request->validate([
                    'name' => 'required|string|max:255',
                    'start_date' => 'required|date',
                    'finish_date' => 'required|date|after_or_equal:start_date',
                    'day_ids' => 'required|array',
                    'holidays_ids' => 'array',
                ]);

                // Update class attributes
                $class->name = $request->get('name');
                $class->start_date = $request->get('start_date');
                $class->finish_date = $request->get('finish_date');
                $class->week_days = json_encode($request->get('day_ids'));
                $class->holidays = json_encode($request->get('holidays_ids'));

                if ($class->save()) {
                    // Delete existing class dates
                    ClassDate::where('clas_id', $class->id)->delete();

                    // Regenerate weekly dates
                    $startDate = \Carbon\Carbon::parse($class->start_date);
                    $endDate = \Carbon\Carbon::parse($class->finish_date);

                    while ($startDate->lte($endDate)) {
                        ClassDate::create([
                            'clas_id' => $class->id,
                            'week_date' => $startDate->toDateString(),
                        ]);
                        $startDate->addWeek(); // Increment by one week
                    }

                    return redirect()->route('class.index')->with(['success' => 'Class updated with weekly dates regenerated']);
                } else {
                    return redirect()->back()->with(['error' => 'Something went wrong']);
                }
            } catch (\Exception $ex) {
                return redirect()->back()
                    ->with(['error' => 'An error occurred: ' . $ex->getMessage()])
                    ->withInput();
            }
        } else {
            return redirect()->back()->with('error', "Access Denied");
        }
    }


    public function removeWeeklyDate($id)
    {
        if (auth()->user()->can('class-edit')) {
            $classDate = ClassDate::findOrFail($id);

            // Delete the date and associated 
            $classDate->delete();

            return redirect()->back()->with('success', 'Weekly date removed successfully');
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }




    public function destroy($id)
    {
        $class = Clas::findOrFail($id);
        $class->delete();

        return redirect()->route('class.index')->with(['success' => 'Class Delete']);
    }

    public function getHolidayDates(Request $request)
    {
        $weekdays = $request->input('weekdays', []);
        $startDate = $request->input('start_date');
        $endDate = $request->input('finish_date');

        if (!$startDate || !$endDate) {
            return response()->json(['error' => 'Missing date range.'], 422);
        }

        $dates = Clas::getDatesWithDayNames($weekdays, $startDate, $endDate);

        return response()->json($dates);
    }
}
