async function loadBoard() {
    const token = localStorage.getItem("token");

    // cek token dulu (HARUS di luar fetch)
    if (!token) {
        alert("login dulu");
        return;
    }

    try {
        const res = await fetch(`/api/events/${eventId}/board`, {
            headers: {
                Authorization: "Bearer " + token,
            },
        });

        if (!res.ok) {
            throw new Error("Gagal ambil data");
        }

        const data = await res.json();

        renderColumn("todo", data.todo);
        renderColumn("progress", data.progress);
        renderColumn("done", data.done);
    } catch (err) {
        console.error(err);
        alert("error load board");
    }
}

function renderColumn(id, tasks) {
    const el = document.getElementById(id);
    el.innerHTML = "";

    tasks.forEach((task) => {
        const div = document.createElement("div");
        div.className = "card";
        div.dataset.id = task.id_task;
        div.innerText = task.nama_tugas;
        el.appendChild(div);
    });
}

function initDrag() {
    ["todo", "progress", "done"].forEach((status) => {
        new Sortable(document.getElementById(status), {
            group: "kanban",
            animation: 150,
            onEnd: async function (evt) {
                const taskId = evt.item.dataset.id;
                const newStatus = evt.to.id;

                await updateTask(taskId, newStatus);
            },
        });
    });
}

async function updateTask(id, status) {
    const token = localStorage.getItem("token");

    if (!token) {
        alert("login dulu");
        return;
    }

    try {
        const res = await fetch(`/api/tasks/${id}/move`, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                Authorization: "Bearer " + token,
            },
            body: JSON.stringify({ status }),
        });

        if (!res.ok) {
            throw new Error("Gagal update task");
        }
    } catch (err) {
        console.error(err);
        alert("error update task");
    }
}

loadBoard();
initDrag();
