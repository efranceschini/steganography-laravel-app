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
            ->orderBy('title', 'asc')
            ->paginate(5);

        $allImages = $request->user()
            ->images()
            ->orderBy('title', 'asc')
            ->get(['id', 'title']);

        return view('dashboard', compact('images', 'allImages'));
    }
}
