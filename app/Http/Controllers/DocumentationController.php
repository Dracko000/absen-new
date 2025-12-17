<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function index()
    {
        return view('documentation.index');
    }
    
    public function showPage($page = null)
    {
        $pageTitle = ucfirst(str_replace('-', ' ', $page));
        
        // Check if the specific page view exists, otherwise show the main documentation page
        $viewPath = 'documentation.' . $page;
        
        if (view()->exists($viewPath)) {
            return view($viewPath, compact('pageTitle'));
        } else {
            return view('documentation.index');
        }
    }
}