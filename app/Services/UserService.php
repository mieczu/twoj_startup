<?php

namespace App\Services;

use App\Mail\WelcomeUserMail;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class UserService
{
    public function __construct(private UserRepository $repository) {}

    public function list(): Collection
    {
        return $this->repository->all();
    }

    public function store(array $data): User
    {
        $emails = $data['emails'];
        unset($data['emails']);

        $user = $this->repository->create($data);
        foreach ($emails as $email) {
            $user->emails()->create(['email' => $email]);
        }

        return $user->load('emails');
    }

    public function update(User $user, array $data): User
    {
        $emails = $data['emails'] ?? null;
        unset($data['emails']);

        $user = $this->repository->update($user, $data);

        if ($emails) {
            $user->emails()->delete();
            $user->emails()->createMany($emails);
        }

        return $user->load('emails');
    }

    public function delete(User $user): void
    {
        $this->repository->delete($user);
    }

    public function sendWelcomeEmails(User $user): void
    {
        foreach ($user->emails as $email) {
            Mail::to($email->email)->send(new WelcomeUserMail($user));
        }
    }
}
