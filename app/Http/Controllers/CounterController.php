<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CounterController extends Controller
{
    private const CACHE_KEY = 'counter_value';

    public function index()
    {
        return view('counter');
    }

    public function increment(Request $request)
    {
        $step = $request->input('step', 1);
        $count = Cache::get(self::CACHE_KEY, 0) + $step;
        Cache::put(self::CACHE_KEY, $count);

        // Debug log
        \Illuminate\Support\Facades\Log::info('🔄 CounterController.increment called', [
            'step' => $step,
            'new_count' => $count,
        ]);

        // Broadcast tới tất cả clients
        \Illuminate\Support\Facades\Log::info('📡 Broadcasting CounterUpdated event', ['count' => $count]);
        broadcast(new \App\Events\CounterUpdated($count));
        \Illuminate\Support\Facades\Log::info('✅ CounterUpdated broadcast completed');

        return response()->json([
            'count' => $count,
            'step' => $step
        ]);
    }

    public function reset()
    {
        Cache::put(self::CACHE_KEY, 0);
        \Illuminate\Support\Facades\Log::info('🔄 CounterController.reset called');
        \Illuminate\Support\Facades\Log::info('📡 Broadcasting reset event');
        broadcast(new \App\Events\CounterUpdated(0));
        \Illuminate\Support\Facades\Log::info('✅ Reset broadcast completed');

        return response()->json([
            'count' => 0
        ]);
    }

    public function getCount()
    {
        $count = Cache::get(self::CACHE_KEY, 0);
        return response()->json([
            'count' => $count
        ]);
    }
}
