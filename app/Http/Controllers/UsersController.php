<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;
use Illuminate\Support\Facades\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        return response()->json([
            'status' => 'success',
            'data'   => [
                'users' => User::all(),
            ],
        ]);
    }

    /**
     * Create a resource.
     *
     * In order for the method to work properly,
     * a header of Content-Type: application/json must be set.
     *
     * @return \Illuminate\Http\Response
     */
    public function post()
    {
        $data = Request::only('gender', 'first_name', 'last_name');

        if ($user = User::create($data)) {

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'id' => $user->id,
                ],
            ], 201);
        }
    }

    /**
     * Update a resource.
     *
     * In order for the method to work properly,
     * a header of Content-Type: application/json must be set.
     *
     * @param $userId
     * @return \Illuminate\Http\Response
     */
    public function put($userId)
    {
        $data = Request::only('gender', 'first_name', 'last_name');

        $user = User::find($userId);
        $user->update($data);

        return response()->json([
            'status' => 'success',
            'data'   => null,
        ]);
    }
}
