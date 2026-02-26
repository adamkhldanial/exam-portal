@include('errors.layout', [
    'code' => 500,
    'title' => 'Something Went Wrong',
    'description' => 'An unexpected error occurred. Please try again in a moment or return to the dashboard.',
    'primaryUrl' => url('/dashboard'),
    'primaryLabel' => 'Go to Dashboard',
])
