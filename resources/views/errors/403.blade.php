@include('errors.layout', [
    'code' => 403,
    'title' => 'Access Denied',
    'description' => 'You do not have permission to view this page. Sign in with the correct account or go back.',
    'primaryUrl' => url('/dashboard'),
    'primaryLabel' => 'Go to Dashboard',
])
