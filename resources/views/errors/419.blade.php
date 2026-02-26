@include('errors.layout', [
    'code' => 419,
    'title' => 'Session Expired',
    'description' => 'Your session timed out while you were inactive. Please log in again to continue safely.',
    'primaryUrl' => \Illuminate\Support\Facades\Route::has('login') ? route('login') : url('/'),
    'primaryLabel' => 'Continue to Login',
    'autoRedirectToLogin' => true,
])
