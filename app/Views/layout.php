<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gider Takip Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800">

    <nav class="bg-indigo-700 text-white shadow-md p-4 mb-6">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl md:text-2xl font-bold tracking-tight">💰 GiderTakip</h1>
            <div class="text-sm bg-indigo-800 px-3 py-1 rounded-full border border-indigo-400">
                Ocak 2026
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 md:px-0">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="py-10 text-center text-slate-400 text-sm">
        &copy: 2026 Gider Takip Sistemi
    </footer>
</body>

</html>