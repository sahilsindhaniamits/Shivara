<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = Newsletter::orderBy('created_at', 'desc')->paginate(50);
        $totalCount = Newsletter::count();

        return view('admin.newsletter.index', compact('subscribers', 'totalCount'));
    }
}
