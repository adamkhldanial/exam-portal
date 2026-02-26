@include('errors.layout', [
    'code' => 404,
    'title' => 'Page Not Found',
    'description' => 'The page you requested could not be found. It may have moved, expired, or the URL may be incorrect.',
    'primaryUrl' => url('/dashboard'),
    'primaryLabel' => 'Go to Dashboard',
])
