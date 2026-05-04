<!DOCTYPE html>
<html>
<head>
    <title>Finance</title>
</head>
<body>

<h1>Finance</h1>

<a href="/dashboard">← Back</a>

<h3>Tambah Pengeluaran</h3>

<input id="nominal" placeholder="Nominal"><br>
<input id="bank" placeholder="Bank"><br>
<input id="rek" placeholder="No Rekening"><br>
<input id="desc" placeholder="Deskripsi"><br>
<input type="file" id="foto"><br>

<button onclick="submitFinance()">Submit</button>

<hr>

<h3>List Pengeluaran</h3>
<div id="list"></div>

<script>
const eventId = {{ $eventId }};

async function loadFinance() {
    const token = localStorage.getItem("token");

    const res = await fetch(`/api/finance/${eventId}`, {
        headers: {
            Authorization: 'Bearer ' + token
        }
    });

    const data = await res.json();

    let html = '';

    data.forEach(f => {
        html += `
            <div style="border:1px solid #ccc; margin:10px; padding:10px;">
                <p>Rp ${f.nominal}</p>
                <p>${f.deskripsi}</p>
                <p>Status: ${f.status}</p>
                ${f.foto_nota_url ? `<img src="/storage/${f.foto_nota_url}" width="100">` : ''}
                <br>
                <button onclick="approve(${f.id_finance})">Approve</button>
                <button onclick="reject(${f.id_finance})">Reject</button>
            </div>
        `;
    });

    document.getElementById('list').innerHTML = html;
}

async function submitFinance() {
    const token = localStorage.getItem("token");

    const formData = new FormData();
    formData.append('id_event', eventId);
    formData.append('nominal', document.getElementById('nominal').value);
    formData.append('jenis_bank', document.getElementById('bank').value);
    formData.append('no_rekening', document.getElementById('rek').value);
    formData.append('deskripsi', document.getElementById('desc').value);

    const file = document.getElementById('foto').files[0];
    if (file) {
        formData.append('foto', file);
    }

    await fetch('/api/finance', {
        method: 'POST',
        headers: {
            Authorization: 'Bearer ' + token
        },
        body: formData
    });

    loadFinance();
}

async function approve(id) {
    const token = localStorage.getItem("token");

    await fetch(`/api/finance/${id}/approve`, {
        method: 'PATCH',
        headers: {
            Authorization: 'Bearer ' + token
        }
    });

    loadFinance();
}

async function reject(id) {
    const token = localStorage.getItem("token");

    await fetch(`/api/finance/${id}/reject`, {
        method: 'PATCH',
        headers: {
            Authorization: 'Bearer ' + token
        }
    });

    loadFinance();
}

loadFinance();
</script>

</body>
</html>