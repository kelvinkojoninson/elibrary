<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StudentClass;
use App\Models\Students;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $classes = StudentClass::orderBy('name')->where('status','ACTIVE')->get();
        return view('auth.register',[
            'classes' => $classes
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'firstName' => ['required', 'string', 'max:255'],
                'lastName' => ['required', 'string', 'max:255'],
                'class' => ['required', 'string', 'max:255'],
                'userid' => ['required', 'string', 'max:255', 'unique:' . User::class],
                'password' => ['required', Rules\Password::defaults()],
            ]);

            if ($validator->fails()) {
                return redirect('/register')->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $user = User::create([
                'userid' => $request->userid,
                'name' => $request->firstName.' '.$request->lastName. ' ' .$request->otherName,
                'email' => $request->userid . '@happyroyal.com',
                'password' => bcrypt($request->password),
                'role_id' => 3,
                'status' => 'INACTIVE',
                'email_verified_at' => Carbon::now()->getTimestamp(),
            ]);

            $student = Students::create([
                'student_id' => $request->userid,
                'firstname' => $request->firstName,
                'lastname' => $request->lastName,
                'othernames' => $request->otherName,
                'class_id' => $request->class,
            ]);

            DB::commit();

            return redirect('/register')->with('success', 'Registration successful and pending approval.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/register')->with('error', 'Registration failed! Please try again.')->withInput();
        }
    }
}
