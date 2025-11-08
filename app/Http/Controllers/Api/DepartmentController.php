<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    /**
     * Create a new department.
     *
     * Validates the incoming request to ensure the 'name' field is present and is a string with a maximum length of 255 characters.
     * Checks if a department with the same name already exists and returns a 409 Conflict response if it does.
     * If validation passes and the department does not exist, creates a new department and returns it with a 201 Created response.
     * Handles validation errors with a 400 Bad Request response and other exceptions with a 500 Internal Server Error response.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing department data.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the created department or error information.
     */
    public function createDepartment(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Check if department already exists
            if (Department::where('name', $validated['name'])->exists()) {
                return response()->json(['error' => 'Department already exists.'], 409);
            }

            $department = Department::create(['name' => trim($validated['name'])]);
            return response()->json($department, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create department.'], 500);
        }
    }

    /**
     * Retrieves a paginated list of departments.
     *
     * Handles the 'per_page' query parameter to determine the number of departments per page.
     * Validates that 'per_page' is a positive integer.
     * Returns a JSON response containing the paginated departments or an error message.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @return \Illuminate\Http\JsonResponse Paginated list of departments or error response.
     */
    public function listDepartments(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);

            if (!is_numeric($perPage) || intval($perPage) < 1) {
                return response()->json(['error' => 'per_page must be a positive integer.'], 400);
            }

            $departments = Department::with('employees')->paginate(intval($perPage));
            return response()->json($departments);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch departments.'], 500);
        }
    }
}
