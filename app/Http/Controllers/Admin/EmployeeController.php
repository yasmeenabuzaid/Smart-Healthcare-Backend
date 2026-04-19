<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    // 1. عرض الصفحة الأساسية (التيمبلت)
    public function index()
    {
        $hospitals = Hospital::select('id', 'name')->get();
        return view('admin.employees.index', compact('hospitals'));
    }

    // 2. جلب البيانات ديناميكياً (للجدول والبحث والفلترة)
    public function fetch(Request $request)
    {
        $query = Employee::with('hospital:id,name');

        // البحث بالاسم أو الإيميل
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        // الفلترة بالدور والمستشفى
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }

        // إرجاع البيانات مع الترقيم (Pagination)
        $employees = $query->latest()->paginate(10);
        return response()->json($employees);
    }

    // 3. إضافة موظف جديد (AJAX)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,doctor,receptionist,manager',
            'hospital_id' => 'required|exists:hospitals,id',
        ]);

        $validated['password'] = Hash::make($validated['password']); // تشفير الباسورد

        Employee::create($validated);

        return response()->json(['message' => 'تم إضافة الموظف بنجاح'], 201);
    }

    // 4. حذف موظف (Soft Delete)
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
