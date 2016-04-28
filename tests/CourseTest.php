<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class CourseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_fetches_all_courses()
    {
        factory(App\Course::class, 3)->create();

        $this->json('GET', '/courses')
            ->seeJsonStructure([
                'status',
                'data' => [
                    'courses' => [
                        '*' => [
                            'id',
                            'begin',
                            'end',
                            'title',
                            'candidate_limit',
                            'candidates',
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);
    }

    /** @test */
    public function it_creates_a_course()
    {
        $this->json('POST', '/courses', [
            'begin'           => '1409249244',
            'end'             => '1409250244',
            'title'           => 'A new updated course',
            'candidate_limit' => 7,
        ])
            ->seeJsonStructure([
                'status',
                'data' => [
                    'id',
                ],
            ])
            ->seeStatusCode(201);
    }

    /** @test */
    public function it_returns_an_error_when_it_tries_to_create_a_course_with_no_begin_field()
    {
        $this->json('POST', '/courses', [
            'end'             => '1409250244',
            'title'           => 'A new updated course',
            'candidate_limit' => 7,
        ])
            ->seeJsonEquals([
                'status'  => 'error',
                'message' => 'Fields begin, end and title are required',
            ])
            ->seeStatusCode(400);
    }

    /** @test */
    public function it_returns_an_error_when_it_tries_to_create_a_course_with_no_end_field()
    {
        $this->json('POST', '/courses', [
            'begin'           => '1409250244',
            'title'           => 'A new updated course',
            'candidate_limit' => 7,
        ])
            ->seeJsonEquals([
                'status'  => 'error',
                'message' => 'Fields begin, end and title are required',
            ])
            ->seeStatusCode(400);
    }

    /** @test */
    public function it_returns_an_error_when_it_tries_to_create_a_course_with_no_title()
    {
        $this->json('POST', '/courses', [
            'begin'           => '1409250144',
            'end'             => '1409250244',
            'candidate_limit' => 7,
        ])
            ->seeJsonEquals([
                'status'  => 'error',
                'message' => 'Fields begin, end and title are required',
            ])
            ->seeStatusCode(400);
    }

    /** @test */
    public function it_registers_a_user_for_a_course()
    {
        factory(App\Course::class, 1)->create();
        factory(App\User::class, 1)->create();

        $courseId = App\Course::first()->id;
        $course = App\Course::find($courseId);

        $userId = App\User::first()->id;

        $this->json('POST', "/courses/$courseId/register", [
            'id_user' => $userId,
        ])
            ->seeJsonEquals([
                'status' => 'success',
                'data'   => null,
            ])
            ->seeStatusCode(201);

        $this->assertEquals($userId, $course->candidates[0]->id);
    }

    /** @test */
    public function it_fails_when_it_tries_to_register_a_user_for_a_course_that_is_full()
    {
        factory(App\Course::class, 1)->create(['candidate_limit' => 0]);
        factory(App\User::class, 1)->create();

        $courseId = App\Course::first()->id;
        $course = App\Course::find($courseId);

        $userId = App\User::first()->id;

        $this->json('POST', "/courses/$courseId/register", [
            'id_user' => $userId,
        ])
            ->seeJson([
                'status' => 'fail',
                'data'   => [
                    'message'         => 'Candidate limit reached',
                    'candidate_limit' => $course->candidate_limit,
                ],
            ])
            ->seeStatusCode(200);
    }

    /** @test */
    public function it_fails_when_it_tries_to_register_a_user_that_does_not_exist_for_a_course()
    {
        factory(App\Course::class, 1)->create();

        $courseId = App\Course::first()->id;
        $userId = 100;

        $this->json('POST', "/courses/$courseId/register", [
            'id_user' => $userId,
        ])
            ->seeJson([
                'status'  => 'error',
                'message' => 'Candidate not found',
            ])
            ->seeStatusCode(404);
    }

    /** @test */
    public function it_removes_a_user_from_a_course()
    {
        $course = factory(App\Course::class, 1)->create();
        $user = factory(App\User::class, 1)->create();
        $course->candidates()->attach($user);

        $this->json('DELETE', "/courses/$course->id/register", [
            'id_user' => $user->id,
        ])
            ->seeJsonEquals([
                'status' => 'success',
                'data'   => null,
            ])
            ->seeStatusCode(200);

        $this->assertEquals(0, count($course->candidates));
    }

    /** @test */
    public function it_returns_an_error_when_it_tries_to_remove_a_user_that_is_not_registered_for_the_course()
    {
        $course = factory(App\Course::class, 1)->create();

        $this->json('DELETE', "/courses/$course->id/register", [
            'id_user' => 100,
        ])
            ->seeJsonEquals([
                'status'  => 'error',
                'message' => 'Candidate not found',
            ])
            ->seeStatusCode(404);
    }
}
