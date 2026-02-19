<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function showDashboard(Request $request)
    {
        $images = $request->user()
            ->images()
            ->latest()
            ->paginate(10);

        return view('dashboard', compact('images'));
    }
}
