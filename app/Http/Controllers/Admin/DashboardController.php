<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Clas; // Replace with your actual class model name

class DashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $teachersCount = Teacher::count();
        $classesCount = Clas::count(); // Replace with actual model name
    
        return view('admin.dashboard', compact('usersCount', 'teachersCount', 'classesCount'));
    }
}
