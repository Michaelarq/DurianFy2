<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul', 'DurianFy')</title>
    <link rel="icon" type="image/png" href="{{ asset('gambar/Logo Durianfy.png') }}">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#006c49',
                        secondary: '#10b981',

                        surface: '#f8fafc',
                        'surface-container': '#f1f5f9',
                        'surface-container-low': '#eef2f7',
                        'surface-container-lowest': '#ffffff',
                        'surface-container-high': '#e2e8f0',

                        'primary-container': '#d1fae5',
                        'secondary-container': '#fef3c7',
                        'secondary-fixed': '#fef9c3',

                        'on-surface': '#0f172a',
                        'on-surface-variant': '#64748b',
                        'on-primary': '#ffffff',
                    },
                    fontFamily: {
                        headline: ['Manrope', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        halus: '0 12px 40px rgba(2, 6, 23, 0.08)',
                        kaca: '0 10px 30px rgba(15, 23, 42, 0.08)',
                    },
                    borderRadius: {
                        xl2: '1.25rem',
                    },
                }
            }
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
            line-height: 1;
            vertical-align: middle;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(24px);
        }

        .signature-gradient {
            background: linear-gradient(135deg, #006c49 0%, #10b981 100%);
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface antialiased">
    @yield('konten')
</body>
</html>