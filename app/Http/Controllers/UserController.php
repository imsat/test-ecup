<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $users = User::latest()->get();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $users= $request->users;

        if(!empty($users)){
            foreach ($users as $user){
                //Check users is already in our app
                $checkUser = User::whereEmail($user['email'])->first();
                if(empty($checkUser)){
                    User::create([
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'gender' => $user['gender'],
                        'status' => $user['status'],
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Collection loaded successful', 'success' => 'true'], 200);
    }

    /**
     *  Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function show(User $user)
    {
        return (new UserResource($user))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse|object
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'user_name' => 'required|string',
            'user_email' => 'required|email|unique:users,user_email,'.$user->_id
        ]);

        // Update final data;
        $user->update([
            'name' => $request->user_name,
            'email' => $request->user_email,
            'gender' => $request->user_gender,
            'status' => $request->status,
        ]);

        return (new UserResource($user))->response()->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        //Permanent delete
        $user->delete();

        return response()->json(['message' => 'Deleted successful', 'success' => 'true'], 200);

    }

}
