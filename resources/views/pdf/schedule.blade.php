<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Jadwal Kuliah PDF</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; margin: 0; padding: 0; }
        .container { padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #4F46E5; padding-bottom: 10px; }
        .header h1 { color: #4F46E5; margin: 0; font-size: 22px; text-transform: uppercase; }
        .header p { margin: 5px 0; font-size: 11px; color: #666; font-style: italic; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        th { background-color: #4F46E5; color: white; font-size: 11px; text-transform: uppercase; padding: 10px; border: 1px solid #4F46E5; }
        td { padding: 10px; border: 1px solid #e2e8f0; font-size: 11px; vertical-align: middle; word-wrap: break-word; }
        
        .day-column { background-color: #f8fafc; font-weight: bold; color: #4F46E5; text-align: center; width: 70px; }
        .time-column { color: #64748b; font-weight: 500; width: 100px; text-align: center; }
        .course-name { font-weight: bold; color: #1e293b; font-size: 12px; }
        .footer { position: fixed; bottom: 20px; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>EduPath Optimizer</h1>
            <p>Laporan Jadwal Kuliah Akademik Otomatis</p>
            <p>Dicetak pada: {{ $date }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">Hari</th>
                    <th style="width: 20%;">Waktu</th>
                    <th style="width: 45%;">Mata Kuliah</th>
                    <th style="width: 10%;">SKS</th>
                    <th style="width: 10%;">Ruang</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                @endphp

                @foreach($days as $day)
                    @php
                        $dailySchedules = collect();
                        foreach($courses as $course) {
                            foreach($course->schedules as $s) {
                                if($s->day == $day) {
                                    $dailySchedules->push([
                                        'name' => $course->name,
                                        'start' => $s->start_time,
                                        'end' => $s->end_time,
                                        'credits' => $course->credits,
                                        'room' => $s->room ?? '-'
                                    ]);
                                }
                            }
                        }
                        $dailySchedules = $dailySchedules->sortBy('start');
                    @endphp

                    @if($dailySchedules->count() > 0)
                        @foreach($dailySchedules as $index => $item)
                        <tr>
                            @if($index == 0)
                                <td class="day-column" rowspan="{{ $dailySchedules->count() }}">{{ $day }}</td>
                            @endif
                            <td class="time-column">{{ substr($item['start'], 0, 5) }} - {{ substr($item['end'], 0, 5) }}</td>
                            <td><span class="course-name">{{ $item['name'] }}</span></td>
                            <td style="text-align: center;">{{ $item['credits'] }}</td>
                            <td style="text-align: center;">{{ $item['room'] }}</td>
                        </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Dokumen ini dihasilkan secara digital oleh sistem EduPath Optimizer.
        </div>
    </div>
</body>
</html>