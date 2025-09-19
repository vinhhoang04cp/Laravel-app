<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>@yield('title','Mail Module')</title>
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap5/bootstrap.min.css') }}">
  <style>
    body { padding: 20px; }
    .code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
    .table td { vertical-align: middle; }
    .template-body { min-height: 260px; }
    .badge + .badge { margin-left: 4px; }
  </style>
  @yield('head')
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light mb-3 rounded border">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('mail.ui.templates.index') }}">Mail Module</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="{{ route('mail.ui.templates.index') }}">Templates</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('mail.ui.configs.index') }}">Configs</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @yield('content')
</div>

<script src="{{ asset('plugins/bootstrap5/bootstrap.bundle.min.js') }}"></script>
<script>
// Tiny helper
async function postJSON(url, data) {
  const res = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '{{ csrf_token() }}'
    },
    body: JSON.stringify(data || {})
  });
  if (!res.ok) throw new Error(await res.text());
  return res.json();
}
</script>
@yield('scripts')
</body>
</html>
