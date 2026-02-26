@php
    $hasLogin = \Illuminate\Support\Facades\Route::has('login');
    $loginUrl = $hasLogin ? route('login') : url('/');
    $previousUrl = url()->previous();
    $currentUrl = url()->current();
    $canGoBack = $previousUrl && $previousUrl !== $currentUrl;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} - {{ $title }}</title>
    <style>
        :root {
            --bg-1: #0f172a;
            --bg-2: #1e293b;
            --card: rgba(15, 23, 42, 0.86);
            --text: #e2e8f0;
            --muted: #94a3b8;
            --accent: #38bdf8;
            --accent-2: #0ea5e9;
            --border: rgba(148, 163, 184, 0.25);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif;
            background: radial-gradient(1200px 600px at 15% 0%, #0b1224 0%, var(--bg-1) 55%),
                        linear-gradient(145deg, var(--bg-1), var(--bg-2));
            color: var(--text);
            padding: 24px;
        }
        .card {
            width: min(680px, 100%);
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 24px 60px rgba(2, 6, 23, 0.45);
            backdrop-filter: blur(3px);
        }
        .code {
            display: inline-block;
            border: 1px solid rgba(56, 189, 248, 0.45);
            border-radius: 999px;
            color: #bae6fd;
            padding: 5px 12px;
            font-size: 12px;
            letter-spacing: 0.08em;
            font-weight: 700;
        }
        h1 {
            margin: 16px 0 10px;
            font-size: clamp(1.6rem, 2vw, 2rem);
            line-height: 1.2;
        }
        p {
            margin: 0;
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.6;
        }
        .actions {
            margin-top: 22px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .btn {
            text-decoration: none;
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid var(--border);
            color: var(--text);
            font-weight: 600;
            font-size: 0.92rem;
        }
        .btn:hover { border-color: rgba(186, 230, 253, 0.65); }
        .btn-primary {
            border-color: rgba(56, 189, 248, 0.55);
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #082f49;
        }
        .hint {
            margin-top: 14px;
            font-size: 0.85rem;
            color: #cbd5e1;
        }
    </style>
</head>
<body>
    <main class="card">
        <span class="code">{{ $code }}</span>
        <h1>{{ $title }}</h1>
        <p>{{ $description }}</p>

        <div class="actions">
            @if (!empty($primaryUrl) && !empty($primaryLabel))
                <a href="{{ $primaryUrl }}" class="btn btn-primary">{{ $primaryLabel }}</a>
            @endif

            @if ($hasLogin)
                <a href="{{ $loginUrl }}" class="btn">Login</a>
            @endif

            <a href="{{ url('/') }}" class="btn">Home</a>

            @if ($canGoBack)
                <a href="{{ $previousUrl }}" class="btn">Back</a>
            @endif
        </div>

        @if (!empty($autoRedirectToLogin) && $hasLogin)
            <p class="hint">Redirecting to login in <span id="countdown">8</span>s...</p>
            <script>
                let seconds = 8;
                const counter = document.getElementById('countdown');
                const timer = setInterval(() => {
                    seconds -= 1;
                    counter.textContent = String(seconds);
                    if (seconds <= 0) {
                        clearInterval(timer);
                        window.location.assign(@json($loginUrl));
                    }
                }, 1000);
            </script>
        @endif
    </main>
</body>
</html>
