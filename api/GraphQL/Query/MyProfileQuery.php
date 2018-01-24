<?php

namespace Api\GraphQL\Query;

use App\Model\User;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;
use Tymon\JWTAuth\Facades\JWTAuth;

class MyProfileQuery extends Query
{
    private $auth;

    protected $attributes = [
        'name' => 'My Profile Query',
        'description' => 'My Profile Information'
    ];

    public function authorize(array $args)
    {
        try {
            $this->auth = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            $this->auth = null;
        }
        return (boolean) $this->auth;
    }

    public function type()
    {
        return GraphQL::type('my_profile');
    }

    public function resolve($root, $args, SelectFields $fields)
    {
        $user = User::with(array_keys($fields->getRelations()))
            ->where('id', $this->auth->id)
            ->select($fields->getSelect())->first();
        return $user;
    }
}
