<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password; // Missing import
use Illuminate\Support\Str; // Missing import
use Illuminate\Auth\Events\PasswordReset; // Missing import

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /** Tampilkan form forgot password
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Cek apakah email ada di database
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        // Kirim link reset password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['status' => 'Link reset password telah dikirim ke email Anda.'])
                    : back()->withErrors(['email' => 'Gagal mengirim link reset password.']);
    }

    /**
     * Tampilkan form reset password
     */
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login dengan password baru.')
                    : back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Cek apakah email sudah diverifikasi  
            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Email Anda belum diverifikasi. Silakan cek email untuk verifikasi.',
                ])->with('resend_verification', true);
            }
            
            // Redirect berdasarkan role
            if (Auth::user()->isAdmin()) {
                return redirect()->route('dashboard');
            }
            
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak cocok.',
        ]);
    }

    public function showRegisterForm()
    {
        // Hanya ambil role tenant untuk registrasi publik
        $tenantRole = Role::where('name', 'tenant')->first();
        return view('auth.register', compact('tenantRole'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Dapatkan role tenant
        $tenantRole = Role::where('name', 'tenant')->firstOrFail();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $tenantRole->id, 
        ]);

        // Login user setelah registrasi (perlu untuk verification notice)
        Auth::login($user);

        // Trigger event untuk mengirim email verifikasi
        event(new Registered($user));

        return redirect()->route('verification.notice')
            ->with('message', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Tampilkan halaman verifikasi email notice
     */
    public function showVerificationNotice()
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Jika sudah verified, redirect ke home
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    /**
     * Kirim ulang email verifikasi
     */
    public function resendVerification(Request $request)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Link verifikasi telah dikirim ulang ke email Anda.');
    }

    /**
     * Verify email
     */
    public function verifyEmail(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'User tidak ditemukan.']);
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->withErrors(['email' => 'Link verifikasi tidak valid.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('message', 'Email sudah diverifikasi sebelumnya.');
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        return redirect()->route('login')->with('message', 'Email berhasil diverifikasi. Silakan login.');
    }
}