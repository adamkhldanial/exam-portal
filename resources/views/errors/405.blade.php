@include('errors.layout', [
    'code' => 405,
    'title' => 'Method Not Allowed',
    'description' => 'This page cannot be opened with the current request method. Return to a normal navigation page and try again.',
    'primaryUrl' => url('/dashboard'),
    'primaryLabel' => 'Go to Dashboard',
])
