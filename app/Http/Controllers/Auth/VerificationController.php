<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('signed')->only('verify');
        $this->middleware('throttle:accountverify')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Illuminate\Database\Eloquent\Model $user
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request, User $user)
    {
        // Check that the URL is validly signed URL
        if (!URL::hasValidSignature($request)) {
            return response()->json([
                'error' => [
                    'title' => 'Verification Error',
                    'details' => 'Invalid verification link.'
                ]
            ], 403);
        }

        // Check if user has already verified email
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'error' => [
                    'title' => 'Verification Error',
                    'details' => "Email {$user->email} is already verified."
                ]
            ], 422);
        }

        // Verify email
        $user->markEmailAsVerified();

        // Fire verifed event
        event(new Verified($user));

        return response()->json([
            'message' => 'Email verified successfully'
        ], 200);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => ['email', 'required']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'error' => [
                    'title' => 'Verification Error',
                    'details' => "User with email: {$request->email} not found"
                ]
            ], 404);
        }

        // Check if user has already verified
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'error' => [
                    'title' => 'Verification Error',
                   'details' => "Email {$request->email} is already verified."
                ]
            ], 422);
        }

        // Resend verification notification
        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => "Verification link has been resent to email: {$request->email}",
        ]);
    }
}
