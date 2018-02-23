@component('mail::message')
# One Last Step

We Just need you to confirm your email address to prove that you're a human. Your get it, right? Yeah

@component('mail::button', ['url' => route('confirmation.index',
       ['token' => $user->confirmation_token])
])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
