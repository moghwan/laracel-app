<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Collection;

class BenchmarkController extends Controller
{
    public function users(int $iterations = 1): JsonResponse
    {
        $users = User::all();

        $res = Benchmark::measure([
            'User::count()' => fn () => User::count(),
            'User::all()->count()' => fn () => User::all()->count(),
            'User::all()' => fn () => User::all(),
            'reformat(User::all())' => fn () => $this->reformat($users),
        ], iterations: $iterations);

        $res = collect($res)->map(fn ($time) => round($time, 2));
        $sum = collect($res)->sum();

        return response()->json([
            'users count' => User::count(),
            'benchmark (ms)' => $res,
            'benchmark total (ms)' => round($sum, 2),
            'iterations' => $iterations,
            'ops duration * iterations (ms)' => $sum * $iterations,
            'ops duration * iterations (s)' => $sum * $iterations / 1000,
        ]);
    }

    private function reformat(Collection $users): Collection
    {
        /*
         * keep email
         * change name to full_name
         * format email_verified_at date to Y-m-d
         * group by email domain
         */
        return $users->map(fn ($user) => [
            'email' => $user->email,
            'full_name' => $user->name,
            'email_verified_at' => $user->email_verified_at->format('Y-m-d'),
        ])->groupBy(fn ($user) => explode('@', $user['email'])[1]);

    }
}
