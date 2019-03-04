<?php

namespace Snek\Http\Controllers;

use Snek\Http\Requests;
use Illuminate\Http\Request;

class LobbyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('lobbies.mes');
    }
}
