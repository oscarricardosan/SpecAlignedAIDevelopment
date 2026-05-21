<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        // TODO: fetch projects from DB — currently always shows empty state
        return view('projects.index');
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        // TODO: persist project
        return redirect('/dashboard')->with('status', 'Project created.');
    }
}
