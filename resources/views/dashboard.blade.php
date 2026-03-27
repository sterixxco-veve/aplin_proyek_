<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPath Optimizer - Dashboard</title>
    
    <!-- Fonts & Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Libs: FullCalendar & Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .fc-v-event { border: none !important; border-radius: 12px !important; padding: 6px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
        .fc-timegrid-slot { height: 3em !important; }
        .glass-card { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-[#f1f5f9] text-slate-900">

    <div class="min-h-screen flex flex-col">
        <!-- Navigasi -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🎯</span>
                        <span class="text-xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-600">EduPath</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-semibold text-slate-500 hidden md:block">Halo, Mahasiswa! 👋</span>
                        <div class="h-8 w-8 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center text-xs font-bold text-indigo-600">M</div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- KIRI: Statistik & Magic Box -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Kartu Statistik -->
                    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                        <div class="relative z-10">
                            <p class="text-indigo-100 text-sm font-medium mb-1">IPK Kumulatif</p>
                            <h2 class="text-5xl font-black mb-4">{{ number_format($gpa ?? 0, 2) }}</h2>
                            <div class="flex justify-between text-xs font-bold mb-2">
                                <span>Progres SKS</span>
                                <span>{{ $totalCredits ?? 0 }} / 144</span>
                            </div>
                            <div class="w-full bg-white/20 h-3 rounded-full overflow-hidden">
                                <div class="bg-white h-full transition-all duration-1000" style="width: {{ (($totalCredits ?? 0) / 144) * 100 }}%"></div>
                            </div>
                        </div>
                        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                    </div>

                    <!-- Magic Box Parser -->
                    <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <span>🪄</span> Magic Box Parser
                        </h3>
                        <form id="magicBoxForm">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Semester</label>
                                    <select name="semester" class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                        @for($i=1; $i<=8; $i++)
                                            <option value="{{ $i }}">Semester {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tempel Data Portal</label>
                                    <textarea 
                                        name="raw_text"
                                        class="w-full mt-1 h-48 p-4 bg-slate-50 border border-slate-200 rounded-[1.5rem] outline-none focus:ring-2 focus:ring-indigo-500 font-mono text-xs leading-relaxed"
                                        placeholder="Format: KODE NAMA SKS NILAI HARI JAM&#10;Contoh: IF101 Kalkulus 3 A Senin 08:00-10:00"
                                    ></textarea>
                                </div>
                                <button type="submit" id="btnSubmit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-indigo-600 transition-all duration-300 shadow-lg active:scale-95">
                                    Proses Data Otomatis
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- KANAN: Visualisasi & Wawasan -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Bagian Jadwal -->
                    <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                            <h3 class="text-xl font-bold text-slate-800">Visual Schedule</h3>
                            <div class="flex gap-2 w-full sm:w-auto">
                                <!-- Tombol Export PDF -->
                                <a href="{{ route('academic.pdf') }}" class="flex flex-1 sm:flex-none justify-center items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-2xl hover:bg-indigo-600 transition font-bold text-sm shadow-xl shadow-slate-200">
                                    <span>📥</span> Ekspor PDF Resmi
                                </a>
                            </div>
                        </div>
                        <div id="calendar" class="min-h-[600px]"></div>
                    </div>

                    <!-- Kisi Wawasan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-emerald-50 rounded-[2rem] p-6 border border-emerald-100">
                            <h4 class="font-bold text-emerald-800 mb-3 flex items-center gap-2">
                                <span>🚀</span> Lazy Path Planner
                            </h4>
                            <div class="space-y-2">
                                @forelse($courses->where('difficulty', 'Santai')->take(3) as $c)
                                    <div class="flex justify-between items-center bg-white/50 p-3 rounded-xl border border-emerald-200">
                                        <span class="text-xs font-semibold text-emerald-900">{{ $c->name }}</span>
                                        <span class="text-[10px] bg-emerald-500 text-white px-2 py-1 rounded-full font-bold">Direkomendasikan</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-emerald-600 italic">Belum ada data mata kuliah santai.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="bg-amber-50 rounded-[2rem] p-6 border border-amber-100">
                            <h4 class="font-bold text-amber-800 mb-3 flex items-center gap-2">
                                <span>⚠️</span> Empty Slot Finder
                            </h4>
                            <p class="text-xs text-amber-700 leading-relaxed">
                                Berdasarkan jadwal Anda, hari <strong>Kamis & Jumat</strong> adalah waktu terbaik untuk kegiatan organisasi atau magang.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Inisialisasi FullCalendar
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                headerToolbar: false,
                dayHeaderFormat: { weekday: 'long' },
                allDaySlot: false,
                slotMinTime: '07:00:00',
                slotMaxTime: '20:00:00',
                height: 'auto',
                events: [
                    @foreach($courses ?? [] as $course)
                        @foreach($course->schedules as $s)
                        {
                            title: '{{ $course->name }}',
                            daysOfWeek: [{{ ['Minggu'=>0,'Senin'=>1,'Selasa'=>2,'Rabu'=>3,'Kamis'=>4,'Jumat'=>5,'Sabtu'=>6][$s->day] }}],
                            startTime: '{{ $s->start_time }}',
                            endTime: '{{ $s->end_time }}',
                            backgroundColor: '{{ $s->color_hex }}',
                            extendedProps: { room: '{{ $s->room ?? "N/A" }}' }
                        },
                        @endforeach
                    @endforeach
                ],
                eventContent: function(arg) {
                    return {
                        html: `<div class="p-1 overflow-hidden">
                                <div class="font-bold text-[10px] truncate leading-tight">${arg.event.title}</div>
                                <div class="text-[9px] opacity-80 mt-0.5">${arg.event.extendedProps.room}</div>
                               </div>`
                    };
                }
            });
            calendar.render();
        });

        // Menangani Pengiriman Magic Box via Ajax
        document.getElementById('magicBoxForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btnSubmit');
            const originalText = btn.innerText;
            
            btn.disabled = true;
            btn.innerText = '✨ Sedang Mengolah...';

            try {
                const response = await fetch('{{ route("academic.parse") }}', {
                    method: 'POST',
                    body: new FormData(e.target),
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    // Muat ulang halaman untuk melihat hasil
                    window.location.reload();
                } else {
                    alert(result.message || 'Gagal memproses data.');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                btn.disabled = false;
                btn.innerText = originalText;
            }
        });
    </script>
</body>
</html>