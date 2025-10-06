// resources/js/app.js
import "./bootstrap";
import Alpine from "alpinejs";
import Sortable from "sortablejs"; // <-- TAMBAHKAN INI

window.Alpine = Alpine;
window.Sortable = Sortable; // <-- Tambahkan ini agar bisa diakses global (opsional tapi membantu)

Alpine.start();

// resources/js/app.js
// ... (kode import dan Alpine) ...

document.addEventListener("DOMContentLoaded", () => {
    const columns = document.querySelectorAll(".kanban-column");
    if (columns.length === 0) {
        return;
    }

    columns.forEach((column) => {
        new Sortable(column, {
            group: "kanban", // Menentukan grup, item bisa dipindah antar kolom ini
            animation: 150,
            ghostClass: "blue-background-class",
            onEnd: function (evt) {
                const itemEl = evt.item; // Element yang digeser
                const toColumn = evt.to; // Kolom tujuan

                const taskId = itemEl.getAttribute("data-task-id");
                const newStatus = toColumn.getAttribute("data-status");
                const token = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content");

                // Kirim update ke server
                fetch(`/tasks/${taskId}/status`, {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token,
                    },
                    body: JSON.stringify({
                        status: newStatus,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log(data.message);
                        // Tambahkan feedback ke user jika perlu
                    })
                    .catch((error) => {
                        console.error("Error:", error);
                        // Kembalikan kartu ke posisi semula jika gagal
                    });
            },
        });
    });
});
