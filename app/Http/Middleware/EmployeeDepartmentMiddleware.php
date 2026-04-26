<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeDepartmentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->role->slug !== 'employee') {
            abort(403, 'Employees only');
        }

        $departmentId = $request->route('departmentId');

        if (!$departmentId) {
            abort(403, 'Department not specified');
        }

        $employee = $user->employee;

        if (!$employee) {
            abort(403, 'Employee record not found');
        }

        $allowed = $employee->departments()
            ->where('departments.id', $departmentId)
            ->exists();

        if (!$allowed) {
            abort(403, 'Not allowed in this department');
        }

        return $next($request);
    }
}
