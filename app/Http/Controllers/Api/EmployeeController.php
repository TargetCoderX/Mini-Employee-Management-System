<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmailJob;
use App\Models\Department;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    /**
     * List employees with pagination and department info.
     *
     * @param Request $request HTTP request containing 'per_page' query.
     * @return \Illuminate\Http\JsonResponse Paginated employees or error response.
     */
    public function listEmployees(Request $request)
    {
        try {
            $perPage = (int) $request->query('per_page', 10);
            if (!is_numeric($perPage) || intval($perPage) < 1) {
                return response()->json(['error' => 'per_page must be a positive integer.'], 400);
            }
            $employees = Employee::with('department')->paginate(intval($perPage));
            return response()->json($employees, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch employees.'], 500);
        }
    }

    /**
     * Creates a new employee record.
     *
     * Validates the incoming request data for required fields:
     * - name: string, required, max 255 characters
     * - email: string, required, must be unique in employees table
     * - position: string, required, max 255 characters
     * - salary: numeric, required, minimum 0
     * - department_slug: string, required, must exist in departments table
     *
     * Associates the employee with the specified department and saves the record.
     * Dispatches a job to send a welcome email to the new employee.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing employee data.
     * @return \Illuminate\Http\JsonResponse
     *     201: Employee created successfully, returns employee data.
     *     400: Validation errors, returns error details.
     *     404: Department not found, returns error message.
     *     500: Other errors, returns generic error message.
     */
    public function createEmployee(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email',
                'position' => 'required|string|max:255',
                'salary' => 'required|numeric|min:0',
                'department_slug' => 'required|exists:departments,slug',
            ]);

            $department = Department::where('slug', $validated['department_slug'])->first();
            if (!$department) {
                return response()->json(['error' => 'Department not found.'], 404);
            }

            $employee = new Employee($validated);
            $employee->department()->associate($department);
            $employee->save();
            SendWelcomeEmailJob::dispatch($employee);
            return response()->json($employee, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create employee.'], 500);
        }
    }

    /**
     * Delete an employee by ID.
     *
     * Handles the deletion of an employee record specified by the given ID.
     * Returns a JSON response indicating success or failure.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @param int $id The ID of the employee to delete.
     * @return \Illuminate\Http\JsonResponse JSON response with success or error message.
     */
    public function deleteEmployee(Request $request, $id)
    {
        try {
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json(['error' => 'Employee not found.'], 404);
            }

            $employee->delete();

            return response()->json(['message' => 'Employee deleted successfully.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete employee.'], 500);
        }
    }
}
