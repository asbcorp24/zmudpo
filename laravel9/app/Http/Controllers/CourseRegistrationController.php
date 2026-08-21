<?php
namespace App\Http\Controllers;

use App\Models\{Enrollment,Program,User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Hash,Mail,URL};
use Illuminate\Support\Str;

class CourseRegistrationController extends Controller
{
    public function create(Program $program)
    {
        abort_unless($program->is_active && $program->registration_enabled, 404);
        return view('public.register', compact('program'));
    }

    public function store(Request $request, Program $program)
    {
        abort_unless($program->is_active && $program->registration_enabled, 404);
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'phone' => ['nullable','string','max:50'],
            'is_legal_entity' => ['nullable','boolean'],
            'consent' => ['accepted'],
        ], [
            'email.unique' => 'Пользователь с таким e-mail уже зарегистрирован. Войдите в систему или обратитесь к администратору.',
            'consent.accepted' => 'Необходимо подтвердить согласие на обработку данных.',
        ]);

        $user = DB::transaction(function () use ($data,$program,$request) {
            $user = User::create([
                'login' => 'reg_'.Str::lower(Str::random(12)),
                'full_name' => trim($data['full_name']),
                'email' => Str::lower($data['email']),
                'phone' => $data['phone'] ?? null,
                'password' => Hash::make(Str::random(48)),
                'role' => 'student',
                'is_active' => false,
                'is_legal_entity' => $request->boolean('is_legal_entity'),
            ]);
            Enrollment::create([
                'user_id' => $user->id,
                'program_id' => $program->id,
                'status' => 'active',
                'started_at' => now()->toDateString(),
            ]);
            return $user;
        });

        $verifyUrl = URL::temporarySignedRoute('public.registration.verify', now()->addDays(7), ['user'=>$user->id]);
        try {
            Mail::raw(
                "Здравствуйте, {$user->full_name}!\n\nВы зарегистрировались на программу «{$program->title}».\nПодтвердите e-mail по ссылке:\n{$verifyUrl}\n\nПосле подтверждения заявка будет ожидать обработки администратором. После активации вам будет отправлена ссылка для установки пароля.",
                function ($message) use ($user) { $message->to($user->email)->subject('Подтверждение регистрации на обучение'); }
            );
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('public.registration.sent', ['user'=>$user->id]);
    }

    public function sent(User $user)
    {
        abort_unless(!$user->is_active && $user->role === 'student', 404);
        return view('public.registration-sent', compact('user'));
    }

    public function verify(Request $request, User $user)
    {
        abort_unless($request->hasValidSignature(), 403);
        if (!$user->email_verified_at) $user->update(['email_verified_at'=>now()]);
        return view('public.registration-verified', compact('user'));
    }
}
