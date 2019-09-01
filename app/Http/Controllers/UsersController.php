<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Http\Resources\UserResource;
     */
    public function index()
    {
        return UserResource::collection(User::orderBy('updated_at', 'desc')->get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Http\Resources\UserResource;
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->all());

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return App\Http\Resources\UserResource;
     */
    public function update(UserRequest $request)
    {
        $user = User::findOrFail($request->id);

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email
        ]);

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return App\Http\Resources\UserResource;
     */
    public function destroy(User $user)
    {
        $user->delete();

        return new UserResource($user);
    }

    /**
    * Check available users from jsonplaceholder.typicode.com/ API
    *
    * @param  \Illuminate\Http\Request  $request
    * @return users array
    */
    public function checkJsonUsers(Request $request)
    {
        $availableUsers = [];
        foreach ($request->json() as $user) {
            $find = DB::table('users')->where('email', $user['email'])->first();
            if (!$find)
                array_push($availableUsers, $user);            
        }

        return $availableUsers;
    }
}
