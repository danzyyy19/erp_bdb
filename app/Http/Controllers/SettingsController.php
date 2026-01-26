<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        // Settings update logic here
        return redirect()->back()->with('success', 'Settings berhasil diperbarui.');
    }
}
