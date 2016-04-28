<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class CoursesController extends Controller
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
                'courses' => Course::with('candidates')->get(),
            ],
        ]);
    }

    /**
     * Create a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function post()
    {
        $data = Request::only(
            'begin',
            'end',
            'title',
            'candidate_limit'
        );

        $validator = $this->validator($data);

        if ($validator->fails()) {

            return response()->json([
                'status'  => 'error',
                'message' => 'Fields begin, end and title are required',
            ], 400);
        }

        if ($course = Course::create($data)) {

            return response()->json([
                'status' => 'success',
                'data'   => [
                    'id' => $course->id,
                ],
            ], 201);
        }
    }

    /**
     * Register a user for a course.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function registerUser($id)
    {
        $userId = Request::only('id_user')['id_user'];

        if ( ! $user = User::find($userId)) {

            return response()->json([
                'status'  => 'error',
                'message' => 'Candidate not found',
            ], 404);
        }

        $course = Course::find($id);

        if ($course->candidateLimitReached()) {

            return response()->json([
                'status' => 'fail',
                'data'   => [
                    'message'         => 'Candidate limit reached',
                    'candidate_limit' => $course->candidate_limit,
                ],
            ], 200);
        }

        $course->candidates()->attach($user);

        return response()->json([
            'status' => 'success',
            'data'   => null,
        ], 201);
    }

    /**
     * Remove a user from a course.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function removeUser($id)
    {
        $course = Course::find($id);

        $userId = Request::only('id_user')['id_user'];
        $user = User::find($userId);

        if ( ! $course->candidates()->detach($user)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Candidate not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => null,
        ]);
    }

    /**
     * Validate the provided data.
     *
     * @param array $data
     * @return mixed
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'begin' => 'required',
            'end'   => 'required',
            'title' => 'required',
        ]);
    }
}
