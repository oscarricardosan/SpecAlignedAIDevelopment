<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        return view('apps.index');
    }

    public function create()
    {
        return view('apps.create');
    }

    public function store(Request $request)
    {
        // TODO: persist app
        return redirect('/dashboard')->with('status', 'Application created.');
    }
}
