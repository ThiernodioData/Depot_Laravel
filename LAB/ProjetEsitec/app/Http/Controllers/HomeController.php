<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $name = "John Doe";
        return view('front.services', [
            'name' => $name,
        ])->with([
            'name' => $name,
        ]);
    }

    public function about()
    {
        return view('front.about');
    }
    public function contact()
    {
        return view('front.contact');
    }
    public function services()
    {
        return view('front.services');
    }
    public function user($id, $name)
    {
        return view('user.index', [
            'id' => $id,
            'name' => $name,
        ])->with([
            'id' => $id,
            'name' => $name,
        ]);
    }

}
