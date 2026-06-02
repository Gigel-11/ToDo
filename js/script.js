const modal       = document.getElementById('taskModal');
const openBtn     = document.getElementById('openModal');
const closeBtn    = document.getElementById('modalClose');
const cancelBtn   = document.getElementById('modalCancel');
const saveBtn     = document.getElementById('modalSave');
const modalError  = document.getElementById('modalError');

function openModal() {
    modal.style.display = 'flex';
    document.getElementById('taskTitle').focus();
}

function closeModal() {
    modal.style.display = 'none';
    document.getElementById('taskTitle').value    = '';
    document.getElementById('taskDesc').value     = '';
    document.getElementById('taskDue').value      = '';
    document.getElementById('taskCategory').value = 'Personal';
    document.getElementById('taskPriority').value = 'medie';
    modalError.style.display = 'none';
}

if (openBtn)   openBtn.addEventListener('click', openModal);
if (closeBtn)  closeBtn.addEventListener('click', closeModal);
if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

modal.addEventListener('click', function(e) {
    if (e.target === modal) closeModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

if (saveBtn) {
    saveBtn.addEventListener('click', function() {
        const title = document.getElementById('taskTitle').value.trim();
        if (!title) {
            modalError.textContent = 'Titlul sarcinii este obligatoriu.';
            modalError.style.display = 'block';
            document.getElementById('taskTitle').focus();
            return;
        }

        const formData = new FormData();
        formData.append('action',      'add');
        formData.append('title',       title);
        formData.append('description', document.getElementById('taskDesc').value.trim());
        formData.append('category',    document.getElementById('taskCategory').value);
        formData.append('priority',    document.getElementById('taskPriority').value);
        formData.append('due_date',    document.getElementById('taskDue').value);

        saveBtn.disabled = true;
        saveBtn.textContent = 'Se salvează...';

        fetch('php/save_data.php', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                    location.reload();
                } else {
                    modalError.textContent = data.error || 'Eroare la salvare.';
                    modalError.style.display = 'block';
                }
            })
            .catch(() => {
                modalError.textContent = 'Eroare de rețea. Încearcă din nou.';
                modalError.style.display = 'block';
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Salvează sarcina';
            });
    });
}

function toggleTask(id, checkbox) {
    const card = checkbox.closest('.task-card');
    const formData = new FormData();
    formData.append('action', 'toggle');
    formData.append('id', id);

    fetch('php/save_data.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
            else checkbox.checked = !checkbox.checked;
        });
}

function deleteTask(id) {
    if (!confirm('Sigur vrei să ștergi această sarcină?')) return;

    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);

    fetch('php/save_data.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = document.querySelector(`.task-card[data-id="${id}"]`);
                if (card) card.remove();
            }
        });
}

const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.task-card').forEach(card => {
            const title = card.querySelector('.task-title')?.textContent.toLowerCase() || '';
            const desc  = card.querySelector('.task-desc')?.textContent.toLowerCase()  || '';
            card.style.display = (title.includes(query) || desc.includes(query)) ? '' : 'none';
        });
    });
}