<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_fetches_all_users()
    {
        factory(App\User::class, 5)->create();

        $this->json('GET', '/users')
            ->seeJsonStructure([
                'status',
                'data' => [
                    'users' => [
                        '*' => [
                            'id',
                            'gender',
                            'first_name',
                            'last_name',
                        ],
                    ],
                ],
            ])
            ->seeStatusCode(200);
    }

    /** @test */
    public function it_creates_a_user()
    {
        $this->json('POST', '/users', [
            'gender'     => 'm',
            'first_name' => 'John',
            'last_name'  => 'Doe',
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
    public function it_updates_a_user()
    {
        factory(App\User::class, 1)->create();

        $id = App\User::first()->id;

        $this->json('PUT', '/users/' . $id, [
            'gender' => 'f',
        ])
            ->seeJsonStructure([
                'status',
                'data',
            ])
            ->seeStatusCode(200);
    }
}
