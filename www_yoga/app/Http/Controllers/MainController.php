<?php

namespace App\Http\Controllers;

use EasyWeChat\Factory;

use Illuminate\Http\Request;


class MainController extends Controller
{

	public function __construct()
	{

	}

	/**
	 *  入口
	 */
	public function index()
	{
		return header('location:/campaign/find');
	}

}
