<?php

namespace Src\Auth\Presentation\HTTP;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Src\Common\Infrastructure\Laravel\Controller;
use Src\Common\Infrastructure\Laravel\Providers\RouteServiceProvider;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
