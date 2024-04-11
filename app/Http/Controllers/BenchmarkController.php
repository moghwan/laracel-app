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
        $res = Benchmark::measure([
            'User::count()' => fn () => User::count(),
            'User::all()->count()' => fn () => User::all()->count(),
            'User::all()' => fn () => User::all(),
            'reformat(User::all())' => fn () => $this->reformat(User::all()),
        ], iterations: $iterations);

        return response()->json([
            'users count' => User::count(),
            'iterations' => $iterations,
            'benchmark' => $res,
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
