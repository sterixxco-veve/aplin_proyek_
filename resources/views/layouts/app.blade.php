<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>GDGOC EventFlow</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --gd-blue: #4285F4;
            --gd-red: #EA4335;
            --gd-yellow: #FBBC05;
            --gd-green: #34A853;
            --bg-light: #F8F9FA;
            --sidebar-width: 260px;
        }

        /* RESET GLOBAL (BIARKAN BODY SCROLL ALAMI) */
        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: var(--bg-light);
            font-family: 'Figtree', sans-serif;
            color: #1f2937;
        }

        /* Sidebar Styling (Fixed Kunci di Kiri) */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: white;
            border-right: 1px solid #e5e7eb;
            z-index: 1030;
            display: flex;
            flex-direction: column;
        }

        /* Content Wrapper (Geser ke Kanan Seukuran Sidebar & Pakai Flexbox) */
        .content-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: calc(100% - var(--sidebar-width));
        }

        .content-wrapper.no-sidebar {
            margin-left: 0;
            width: 100%;
        }

        /* MAIN COMPONENT AREA (KUNCI ANTI-KEPOTONG) */
        main {
            padding-top: 102px !important;
            /* Tinggi Navbar (approx 70px) + Jarak Renggang Udara (32px) */
            padding-bottom: 60px !important;
            /* Ruang napas bawah agar card/tombol tidak terpotong footer */
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: var(--gd-blue);
            border: none;
            border-radius: 12px;
            font-weight: 600;
        }

        .form-control-search {
            border-radius: 12px;
            background-color: #f3f4f6;
            border: none;
            padding: 10px 20px;
            width: 300px;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
                width: 100%;
            }

            main {
                padding-top: 90px !important;
                /* Sedikit lebih rapat di mobile view */
            }
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 10px 14px;
            font-weight: 500;
            color: #6c757d;
            border-bottom: 2px solid transparent;
        }

        .tab-btn.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
        }

        .tab-btn:hover {
            color: #0d6efd;
        }

        .tab-content {
            animation: fadeIn 0.2s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    @stack('styles')
</head>

<body class="antialiased">
    <div class="d-flex w-100 min-vh-100">
        {{-- Kondisi Sidebar --}}
        @auth
            @include('layouts.sidebar')
        @endauth

        {{-- Sisi Kanan Layout --}}
        <div class="content-wrapper flex-grow-1 @guest no-sidebar @endguest">
            {{-- Navbar di include di sini --}}
            @include('layouts.navigation')

            {{-- Main Content (Otomatis mendorong komponen ke bawah navbar berkat padding-top baru) --}}
            <main class="px-4 px-lg-5 flex-grow-1">
                @yield('content')
            </main>

            {{-- Footer / Bottom Bar Tetap Rapi Di Bawah Konten --}}
            <footer class="py-4 bg-white border-top text-center mt-auto">
                <p class="text-muted small mb-0">© {{ date('Y') }} GDGOC EventFlow • Built for ISTTS</p>
            </footer>
        </div>
    </div>

    <button class="gemini-chat-btn" onclick="toggleGeminiChat()" title="Tanya Gemini">
        <i class="bi bi-chat-dots-fill"></i>
    </button>

    <div class="gemini-chat-container" id="geminiChatContainer">
        <div class="gemini-chat-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-stars" style="color: #6366f1;"></i>
                <span class="fw-bold">Tanya Gemini AI</span>
            </div>
            <button class="btn btn-sm text-white border-0 opacity-75 hover-opacity-100" onclick="toggleGeminiChat()">
                <i class="bi bi-xl"></i>
            </button>
        </div>

        <div class="gemini-chat-messages" id="geminiChatMessages">
            <div class="gemini-message gemini-msg">
                Halo! Ada yang bisa saya bantu terkait event atau dashboard Anda hari ini? ✨
            </div>
        </div>

        <div class="gemini-chat-input-area">
            <input type="text" id="geminiUserInput" placeholder="Ketik sesuatu di sini..."
                onkeypress="handleGeminiKeyPress(event)">
            <button onclick="sendGeminiMessage()">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>

    <style>
        .gemini-chat-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 56px;
            height: 56px;
            background-color: #4f46e5;
            /* Senada dengan warna tema dashboard-mu (#4f46e5) */
            color: white;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.4);
            font-size: 1.4rem;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;
        }

        .gemini-chat-btn:hover {
            transform: scale(1.08);
            background-color: #4338ca;
        }

        .gemini-chat-container {
            position: fixed;
            bottom: 95px;
            right: 25px;
            width: 360px;
            height: 480px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: none;
            /* Default tersembunyi */
            flex-direction: column;
            overflow: hidden;
            z-index: 9999;
            border: 1px solid #e2e8f0;
            font-family: inherit;
        }

        .gemini-chat-header {
            background-color: #1e293b;
            /* Header gelap elegan */
            color: white;
            padding: 16px;
            font-size: 0.95rem;
        }

        .gemini-chat-messages {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background-color: #f8fafc;
        }

        .gemini-message {
            padding: 10px 14px;
            border-radius: 12px;
            max-width: 85%;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .user-msg {
            background-color: #eef2ff;
            align-self: flex-end;
            color: #4f46e5;
            border-bottom-right-radius: 4px;
            font-weight: 500;
        }

        .gemini-msg {
            background-color: #ffffff;
            align-self: flex-start;
            color: #334155;
            border-bottom-left-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        .gemini-chat-input-area {
            display: flex;
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 12px;
            gap: 8px;
        }

        .gemini-chat-input-area input {
            flex: 1;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            font-size: 0.85rem;
            transition: border-color 0.15s ease;
        }

        .gemini-chat-input-area input:focus {
            border-color: #4f46e5;
        }

        .gemini-chat-input-area button {
            background-color: #4f46e5;
            color: white;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.15s ease;
        }

        .gemini-chat-input-area button:hover {
            background-color: #4338ca;
        }

        .gemini-chat-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 56px;
            height: 56px;
            background-color: #4f46e5;
            color: white;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.4);
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;

            /* UPDATE DI SINI: Naikkan z-index ke level tertinggi */
            z-index: 99999 !important;
        }

        .gemini-chat-btn:hover {
            transform: scale(1.08);
            background-color: #4338ca;
        }

        .gemini-chat-container {
            position: fixed;
            bottom: 95px;
            right: 25px;
            width: 360px;
            height: 480px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            display: none;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            font-family: inherit;

            /* UPDATE DI SINI: Pastikan kontainer chat juga di paling depan */
            z-index: 99999 !important;
        }
    </style>

    <script>
        function toggleGeminiChat() {
            const chatBox = document.getElementById('geminiChatContainer');
            if (chatBox.style.display === 'flex') {
                chatBox.style.display = 'none';
            } else {
                chatBox.style.display = 'flex';
                // Scroll otomatis ke paling bawah saat dibuka
                const msgContainer = document.getElementById('geminiChatMessages');
                msgContainer.scrollTop = msgContainer.scrollHeight;
            }
        }

        function handleGeminiKeyPress(event) {
            if (event.key === 'Enter') sendGeminiMessage();
        }

        async function sendGeminiMessage() {
            const inputEl = document.getElementById('geminiUserInput');
            const messageText = inputEl.value.trim();
            if (!messageText) return;

            const messagesContainer = document.getElementById('geminiChatMessages');

            // 1. Render pesan user ke UI
            const userDiv = document.createElement('div');
            userDiv.className = 'gemini-message user-msg';
            userDiv.innerText = messageText;
            messagesContainer.appendChild(userDiv);
            inputEl.value = '';
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            // 2. Render status "Sedang mengetik..."
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'gemini-message gemini-msg';
            loadingDiv.innerHTML = '<i class="bi bi-three-dots animated-dots"></i> Mengetik...';
            messagesContainer.appendChild(loadingDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;

            try {
                // 3. Request AJAX ke Route Laravel
                const response = await fetch('/gemini-chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Mengamankan request dengan CSRF Token global bawaan blade
                    },
                    body: JSON.stringify({ message: messageText })
                });

                const data = await response.json();

                // 4. Update status mengetik tadi menjadi respon teks dari Gemini
                if (response.ok) {
                    loadingDiv.innerText = data.reply;
                } else {
                    loadingDiv.innerText = data.reply || 'Waduh, server sedang bermasalah.';
                }
            } catch (error) {
                loadingDiv.innerText = 'Gagal terhubung. Pastikan koneksi internet aman.';
                console.error(error);
            }
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>