<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function index(): JsonResponse
    {
        return response()->json($this->userService->list());
    }

    public function store(Request $request)
    {
//    {$data = $request->validate([
//        'first_name' => 'required|string',
//        'last_name' => 'required|string',
//        'phone_number' => 'required|string',
//        'emails' => 'required|array',
//        'emails.*' => 'required|email'
//    ]);
//
//        $user = User::create($data);
//        foreach ($data['emails'] as $email) {
//            $user->emails()->create(['email' => $email]);
//        }
//
//        return response()->json($user->load('emails'), 201);
        $data = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
            'emails' => 'required|array',
            'emails.*' => 'required|email'
        ]);
        $user = $this->userService->store($data);

        return $this->respond($user, 201);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string',
            'emails' => 'sometimes|required|array',
            'emails.*' => 'required|email'
        ]);

        $result = $this->userService->update($user, $data);

        return $this->respond($result);
    }

    public function show($id)
    {
        $user = User::with('emails')->findOrFail($id);
        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user);

        return response()->noContent();
    }

    public function sendWelcomeEmail(User $user)
    {
        $this->userService->sendWelcomeEmails($user);

        return response()->json(['message' => 'Mails sent successfully.']);
    }
}
