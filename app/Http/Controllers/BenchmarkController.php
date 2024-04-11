<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Benchmark;

class BenchmarkController extends Controller
{
    public function users(int $iterations = 10): JsonResponse
    {
        $res = Benchmark::measure([
            'User::count()' => fn () => User::count(),
            'User::all()->count()' => fn () => User::all()->count(),
            'User::all()' => fn () => User::all(),
        ], iterations: $iterations);

        return response()->json([
            'users count' => User::count(),
            'iterations' => $iterations,
            'benchmark' => $res,
        ]);
    }
}
