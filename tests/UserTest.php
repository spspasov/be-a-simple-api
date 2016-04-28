<?php

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
        $user = factory(App\User::class, 1)->create(['gender' => 'm']);
        $id = $user->id;

        $firstName = $user->first_name;
        $lastName = $user->last_name;

        $this->json('PUT', "/users/$id", [
            'gender' => 'f',
        ])
            ->seeJsonStructure([
                'status',
                'data',
            ])
            ->seeStatusCode(200);

        $user = App\User::find($id);

        $this->assertEquals($firstName, $user->first_name);
        $this->assertEquals($lastName, $user->last_name);
        $this->assertEquals('f', $user->gender);
    }
}
