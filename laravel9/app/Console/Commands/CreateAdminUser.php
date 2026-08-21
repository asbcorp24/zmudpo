<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create
        {--login= : Логин администратора}
        {--name= : ФИО администратора}
        {--email= : E-mail администратора}
        {--password= : Пароль администратора}
        {--force : Обновить существующего пользователя с таким логином без дополнительного подтверждения}';

    protected $description = 'Создать или обновить активного администратора ZMUDPO';

    public function handle(): int
    {
        $login = trim((string)($this->option('login') ?: $this->ask('Логин администратора', 'admin')));
        $name = trim((string)($this->option('name') ?: $this->ask('ФИО администратора', 'Администратор')));
        $email = trim((string)($this->option('email') ?: $this->ask('E-mail администратора', 'admin@zmudpo.local')));
        $password = (string)($this->option('password') ?: $this->secret('Пароль администратора'));

        if ($login === '') {
            $this->error('Логин не может быть пустым.');
            return self::FAILURE;
        }
        if ($name === '') {
            $this->error('ФИО не может быть пустым.');
            return self::FAILURE;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Некорректный e-mail.');
            return self::FAILURE;
        }
        if (mb_strlen($password) < 8) {
            $this->error('Пароль должен содержать минимум 8 символов.');
            return self::FAILURE;
        }

        $existing = User::where('login', $login)->first();
        if ($existing && !$this->option('force')) {
            if (!$this->confirm("Пользователь {$login} уже существует. Сделать его администратором и обновить данные?")) {
                $this->warn('Операция отменена.');
                return self::SUCCESS;
            }
        }

        $emailOwner = User::where('email', Str::lower($email))
            ->when($existing, fn($q) => $q->whereKeyNot($existing->id))
            ->first();
        if ($emailOwner) {
            $this->error('Этот e-mail уже используется другим пользователем.');
            return self::FAILURE;
        }

        $user = User::updateOrCreate(
            ['login' => $login],
            [
                'full_name' => $name,
                'email' => Str::lower($email),
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
                'registration_confirmed_at' => now(),
                'is_legal_entity' => false,
            ]
        );

        $this->newLine();
        $this->info($existing ? 'Администратор обновлён.' : 'Администратор создан.');
        $this->table(['Поле', 'Значение'], [
            ['ID', $user->id],
            ['Логин', $user->login],
            ['ФИО', $user->full_name],
            ['E-mail', $user->email],
            ['Роль', $user->role],
            ['Статус', $user->is_active ? 'Активен' : 'Заблокирован'],
        ]);
        $this->line('Вход: /login');
        $this->line('Админка: /admin');

        return self::SUCCESS;
    }
}
