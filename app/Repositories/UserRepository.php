<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = User::with('role');

        if (!empty($filters['role'])) {
            $query->whereHas('role', fn($q) => $q->where('slug', $filters['role']));
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                    ->orWhere('email', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['is_active'])) {
            $query->where('is_active', $filters['is_active'] === 'true' || $filters['is_active'] === true);
        }

        $perPage = $filters['per_page'] ?? 15;
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return User::with('role')->find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getActiveUsers(): array
    {
        return User::active()->with('role')->get()->toArray();
    }

    public function getUsersByRole(string $role): array
    {
        return User::whereHas('role', fn($q) => $q->where('slug', $role))
            ->with('role')
            ->get()
            ->toArray();
    }
}
