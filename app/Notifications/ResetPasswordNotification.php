<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ResetPasswordNotification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
{
    return (new MailMessage)
        ->subject('Reset Your Password')
        ->greeting('Hello ' . $notifiable->name . ',')
        ->line('We received a request to reset your password.')
        ->action('Reset Password', env('FRONTEND_URL') . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email))
        ->line('If you did not request a password reset, no further action is required.')
        ->salutation('Regards, ' . config('app.name'));
}

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
     
    public function sendResetLinkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email'
    ]);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    if ($status == Password::RESET_LINK_SENT) {
        return response()->json(['message' => __($status)], 200);
    } else {
        return response()->json(['error' => __($status)], 400);
    }
}

public function reset(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
    );

    if ($status == Password::PASSWORD_RESET) {
        return response()->json(['message' => __($status)], 200);
    } else {
        return response()->json(['error' => __($status)], 400);
    }
}

   public function sendPasswordResetNotification($token)
   {
    $this->notify(new ResetPasswordNotification($token));
   }

}
