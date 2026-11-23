<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Атрибути, дозволені для масового присвоєння.
     * Поле 'role' відсутнє для захисту від mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Атрибути, приховані під час серіалізації.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Приведення типів атрибутів.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Перевірити, чи має користувач роль адміністратора.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Перевірити, чи має користувач права менеджера (manager або admin).
     *
     * @return bool
     */
    public function isManager(): bool
    {
        return in_array($this->role, ['manager', 'admin'], true);
    }

    /**
     * Перевірити, чи має користувач права клієнта (client, manager або admin).
     *
     * @return bool
     */
    public function isClient(): bool
    {
        return in_array($this->role, ['client', 'manager', 'admin'], true);
    }

    /**
     * Отримати ідентифікатор користувача, який буде збережено в claim "sub" JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Отримати додаткові claims для додавання в payload JWT.
     *
     * @return array<string, mixed>
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'role' => $this->role,
        ];
    }
}