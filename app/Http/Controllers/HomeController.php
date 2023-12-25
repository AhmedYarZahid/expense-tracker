<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Expense;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        $categories = Category::all();
        return view('home', ['categories' => $categories]);
    }

    /**
     * get expense stats based on filters
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getExpenseStats(Request $request)
    {
        $startDate = null;
        $endDate = null;

        // Set the start and end dates based on the selected filter
        switch ($request->get('duration_filter')) {
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case 'last_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'last_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        $queryIn = Expense::query();
        $queryOut = Expense::query();

        $categoryId = $request->get('category_filter');
        if ($categoryId && $categoryId !== 'all') {
            $queryIn->where('category_id', $categoryId);
            $queryOut->where('category_id', $categoryId);
        }

        if ($startDate && $endDate) {
            $queryIn->whereBetween('created_at', [$startDate, $endDate]);
            $queryOut->whereBetween('created_at', [$startDate, $endDate]);
            $expenses = Expense::when($request->get('duration_filter'), function ($query, $filter) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })->when($categoryId && $categoryId !== 'all', function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })->with('category')->get();
        } else {
            $expenses = Expense::with('category')->when($categoryId && $categoryId !== 'all', function ($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })->get();
        }

        $totalInExpenses = $queryIn->where('expense_type', 'in')->sum('amount');
        $totalOutExpenses = $queryOut->where('expense_type', 'out')->sum('amount');

        return response()->json([
            'totalInExpenses' => $totalInExpenses,
            'totalOutExpenses' => $totalOutExpenses,
            'expenses' => $expenses
        ]);
    }
}
