<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, что пользователь аутентифицирован
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Проверяем, что пользователь является администратором
        // Для простоты используем email для определения администратора
        // В реальном проекте лучше добавить поле is_admin в таблицу users
        $adminEmails = config('app.admin_emails', ['admin@example.com']);

        if (!in_array(Auth::user()->email, $adminEmails)) {
            abort(403, 'Доступ запрещен. Требуются права администратора.');
        }

        return $next($request);
    }
}
