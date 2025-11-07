<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view or return employee data for DataTables via AJAX.
     *
     * Handles both standard and AJAX requests:
     * - For AJAX requests, returns a JSON response containing employee data,
     *   including department info, salary formatting, and status badges.
     * - For non-AJAX requests, renders the dashboard view.
     *
     * Applies server-side filtering on the 'name' column.
     * Handles soft-deleted employees and displays their status.
     * Logs errors and returns appropriate error responses.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = Employee::with('department')->withTrashed()->orderBy('created_at', 'desc');

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->editColumn('salary', function ($row) {
                        return number_format($row->salary, 2);
                    })
                    ->addColumn('status', function ($row) {
                        $isInactive = !is_null($row->deleted_at);
                        $badgeClass = $isInactive ? 'badge text-bg-danger w-100' : 'badge text-bg-success w-100';
                        $badgeText = $isInactive ? 'Inactive' : 'Active';
                        return '<span class="' . $badgeClass . '">' . $badgeText . '</span>';
                    })
                    ->rawColumns(['status'])
                    ->filterColumn('name', function ($query, $keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    })
                    ->make(true);
            }
            return view('dashboard');
        } catch (\Exception $e) {
            \Log::error('DashboardController@index error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load data.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to load dashboard.');
        }
    }
}
