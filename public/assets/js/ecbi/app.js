// Souscription Mercure
const url = new URL('https://localhost/.well-known/mercure');
url.searchParams.append('topic', 'notifications/USER_ID'); // remplacer USER_ID par l'id de l'utilisateur connecté

const eventSource = new EventSource(url);

eventSource.onmessage = e => {
    const data = JSON.parse(e.data);
    showToast(data.title, data.message);
};

// Fonction pour afficher un toast Bootstrap
function showToast(title, message) {
    const toastContainer = document.getElementById("toastContainer");

    const toastEl = document.createElement("div");
    toastEl.className = "toast align-items-center text-bg-primary border-0";
    toastEl.setAttribute("role", "alert");
    toastEl.setAttribute("aria-live", "assertive");
    toastEl.setAttribute("aria-atomic", "true");

    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <strong>${title}</strong><br>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
        </div>
    `;

    toastContainer.appendChild(toastEl);

    const toast = new bootstrap.Toast(toastEl, { delay: 5000 }); // disparaît après 5s
    toast.show();
}
