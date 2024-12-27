<?php
namespace App\Controllers;

class HomeController extends Controllers
{
    public function index()
    {
        return view('welcome');
    }
}
