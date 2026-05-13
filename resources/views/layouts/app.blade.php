<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Chamados')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    <div class="page-loader" id="page-loader" aria-live="polite" aria-hidden="true">
        <div class="page-loader-box" role="status">
            <span class="page-loader-spinner" aria-hidden="true"></span>
            <span>Carregando...</span>
        </div>
    </div>

    <div class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <strong>Sistema de Chamados</strong>
            </div>
            <div class="navbar-links">
                <a href="{{ route('home') }}">Abrir Chamado</a>
                <a href="{{ route('chamado.consultar') }}">Consultar Status</a>
                <a href="{{ route('avaliacao.index') }}">Avaliacoes</a>
                @if(session('admin_authenticated'))
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                    <form action="{{ route('admin.logout') }}" method="POST" class="navbar-logout-form">
                        @csrf
                        <button type="submit" class="navbar-logout-btn">Sair</button>
                    </form>
                @else
                    <a href="{{ route('admin.login') }}">Admin</a>
                @endif
            </div>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    @yield('scripts')
    <script>
        (() => {
            const loader = document.getElementById('page-loader');
            const showLoader = () => {
                if (!loader) {
                    return;
                }

                loader.classList.add('is-visible');
                loader.setAttribute('aria-hidden', 'false');
            };

            const hideLoader = () => {
                if (!loader) {
                    return;
                }

                loader.classList.remove('is-visible');
                loader.setAttribute('aria-hidden', 'true');
            };

            window.addEventListener('pageshow', hideLoader);

            document.addEventListener('click', (event) => {
                const link = event.target.closest('a[href]');

                if (!link) {
                    return;
                }

                const url = new URL(link.href, window.location.href);
                const isSameOrigin = url.origin === window.location.origin;
                const opensNewTab = link.target && link.target !== '_self';
                const isDownload = link.hasAttribute('download');
                const isAnchorOnly = url.pathname === window.location.pathname && url.hash;

                if (isSameOrigin && !opensNewTab && !isDownload && !isAnchorOnly) {
                    showLoader();
                }
            });

            document.addEventListener('submit', (event) => {
                const form = event.target;

                if (form.matches('form') && (!form.target || form.target === '_self')) {
                    showLoader();
                }
            });
        })();
    </script>
</body>
</html>
