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

if (modal) {
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
}

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
            if (data.success) {
                location.reload();
            } else {
                checkbox.checked = !checkbox.checked;
            }
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

(function(){
    async function loadLang(lang){
        try{
            const res = await fetch('php/save_data.php?action=lang&lang=' + encodeURIComponent(lang));
            if(!res.ok) return {};
            const json = await res.json();
            return json;
        }catch(e){ return {}; }
    }

    const map = {
        'auth': [{ sel: 'a[href="login.php"]', prop: 'text' }],
        'try_free': [{ sel: 'a[href="register.php"]', prop: 'text' }, { sel: '.hero-cta a.btn-primary', prop: 'text' }, { sel: '.cta-box a.btn-primary', prop: 'text' }],
        'contact': [{ sel: 'a[href="contact.php"]', prop: 'text' }],
        'login': [{ sel: 'a[href="login.php"]', prop: 'text' }],
        'register': [{ sel: 'a[href="register.php"]', prop: 'text' }],
        'hero_title': [{ sel: '.hero h1', prop: 'html' }],
        'hero_line2': [{ sel: '.hero .line2', prop: 'text' }],
        'hero_p': [{ sel: '.hero p', prop: 'text' }],
        'hero_cta_primary': [{ sel: '.hero-cta a.btn-primary', prop: 'text' }],
        'hero_cta_ghost': [{ sel: '.hero-cta a.btn-ghost', prop: 'text' }],
        'hero_note': [{ sel: '.hero-note', prop: 'text' }],
        'cta_heading': [{ sel: '.cta-box h2', prop: 'text' }],
        'cta_p': [{ sel: '.cta-box p', prop: 'text' }],
        'cta_cta_primary': [{ sel: '.cta-box a.btn-primary', prop: 'text' }],
        'cta_cta_ghost': [{ sel: '.cta-box a.btn-ghost', prop: 'text' }],
        'mini_menu': [{ sel: '.mini-section-title', prop: 'text' }],
        'mini_all': [{ sel: '.mini-nav-item:nth-of-type(2)', prop: 'text' }],
        'mini_today': [{ sel: '.mini-nav-item:nth-of-type(3)', prop: 'text' }],
        'mini_done': [{ sel: '.mini-nav-item:nth-of-type(4)', prop: 'text' }],
        'mini_expired': [{ sel: '.mini-nav-item:nth-of-type(5)', prop: 'text' }],
        'mini_categories': [{ sel: '.mini-section-title:nth-of-type(2)', prop: 'text' }],
        'mini_stat_total_label': [{ sel: '.mini-stats .mini-stat:nth-of-type(1)', prop: 'text' }],
        'mini_stat_active_label': [{ sel: '.mini-stats .mini-stat:nth-of-type(2)', prop: 'text' }],
        'mini_stat_done_label': [{ sel: '.mini-stats .mini-stat:nth-of-type(3)', prop: 'text' }],
        'mini_stat_expired_label': [{ sel: '.mini-stats .mini-stat:nth-of-type(4)', prop: 'text' }],
        'feat_title_manage': [{ sel: '.features-grid .feat-card:nth-of-type(1) h3', prop: 'text' }],
        'feat_manage_desc': [{ sel: '.features-grid .feat-card:nth-of-type(1) p', prop: 'text' }],
        'feat_title_cat': [{ sel: '.features-grid .feat-card:nth-of-type(2) h3', prop: 'text' }],
        'feat_cat_desc': [{ sel: '.features-grid .feat-card:nth-of-type(2) p', prop: 'text' }],
        'feat_title_stats': [{ sel: '.features-grid .feat-card:nth-of-type(3) h3', prop: 'text' }],
        'feat_stats_desc': [{ sel: '.features-grid .feat-card:nth-of-type(3) p', prop: 'text' }],
        'feat_title_filter': [{ sel: '.features-grid .feat-card:nth-of-type(4) h3', prop: 'text' }],
        'feat_filter_desc': [{ sel: '.features-grid .feat-card:nth-of-type(4) p', prop: 'text' }],
        'feat_title_account': [{ sel: '.features-grid .feat-card:nth-of-type(5) h3', prop: 'text' }],
        'feat_account_desc': [{ sel: '.features-grid .feat-card:nth-of-type(5) p', prop: 'text' }],
        'feat_title_fast': [{ sel: '.features-grid .feat-card:nth-of-type(6) h3', prop: 'text' }],
        'stats_strip_cat_label': [{ sel: '.stats-strip .stat-item:nth-of-type(1) .label', prop: 'text' }],
        'stats_strip_priority_label': [{ sel: '.stats-strip .stat-item:nth-of-type(2) .label', prop: 'text' }],
        'stats_strip_free_label': [{ sel: '.stats-strip .stat-item:nth-of-type(3) .label', prop: 'text' }],
        'steps_label': [{ sel: '.section-header .section-label', prop: 'text' }],
        'steps_title': [{ sel: '.section-header h2', prop: 'text' }],
        'step1_title': [{ sel: '.steps .step:nth-of-type(1) h4', prop: 'text' }],
        'step1_desc': [{ sel: '.steps .step:nth-of-type(1) p', prop: 'text' }],
        'step2_title': [{ sel: '.steps .step:nth-of-type(2) h4', prop: 'text' }],
        'step2_desc': [{ sel: '.steps .step:nth-of-type(2) p', prop: 'text' }],
        'step3_title': [{ sel: '.steps .step:nth-of-type(3) h4', prop: 'text' }],
        'step3_desc': [{ sel: '.steps .step:nth-of-type(3) p', prop: 'text' }],
        'step1_title': [{ sel: '.step:nth-of-type(1) h4', prop: 'text' }],
        'step1_desc': [{ sel: '.step:nth-of-type(1) p', prop: 'text' }],
        'step2_title': [{ sel: '.step:nth-of-type(2) h4', prop: 'text' }],
        'step2_desc': [{ sel: '.step:nth-of-type(2) p', prop: 'text' }],
        'step3_title': [{ sel: '.step:nth-of-type(3) h4', prop: 'text' }],
        'step3_desc': [{ sel: '.step:nth-of-type(3) p', prop: 'text' }],
        'footer_copy': [{ sel: 'footer .copy', prop: 'text' }, { sel: 'footer', prop: 'text' }],
        'footer_login': [{ sel: 'footer .links a[href="login.php"]', prop: 'text' }],
        'footer_register': [{ sel: 'footer .links a[href="register.php"]', prop: 'text' }],
        'modal_title': [{ sel: '#taskModal h3', prop: 'text' }],
        'modal_save': [{ sel: '#modalSave', prop: 'text' }],
        'modal_saving': [{ sel: '#modalSave', prop: 'data-saving' }],
        'task_title_placeholder': [{ sel: '#taskTitle', prop: 'placeholder' }],
        'task_desc_placeholder': [{ sel: '#taskDesc', prop: 'placeholder' }],
        'task_due_placeholder': [{ sel: '#taskDue', prop: 'placeholder' }],
        'task_category_label': [{ sel: 'label[for="taskCategory"]', prop: 'text' }],
        'task_priority_label': [{ sel: 'label[for="taskPriority"]', prop: 'text' }],
        'delete_confirm': [{ sel: 'body', prop: 'data-delete-confirm' }],
        'send_msg': [{ sel: 'form[action="contact.php"] button[type="submit"]', prop: 'text' }, { sel: 'form[action="contact.php"] button', prop: 'text' }],
        'name': [{ sel: 'form[action="contact.php"] label:nth-of-type(1)', prop: 'text' }],
        'email': [{ sel: 'form[action="contact.php"] label:nth-of-type(2)', prop: 'text' }],
        'message': [{ sel: 'form[action="contact.php"] label:nth-of-type(3)', prop: 'text' }],
        'theme_toggle': [{ sel: '#themeToggle', prop: 'title' }],
        'search_placeholder': [{ sel: '#searchInput', prop: 'placeholder' }]
    };

    Object.assign(map, {
        'nav_auth': [{ sel: 'header .nav-actions a[href="login.php"]', prop: 'text' }, { sel: 'footer .links a[href="login.php"]', prop: 'text' }],
        'nav_register': [{ sel: 'header .nav-actions a[href="register.php"]', prop: 'text' }, { sel: 'footer .links a[href="register.php"]', prop: 'text' }],
        'nav_contact': [{ sel: 'header .nav-actions a[href="contact.php"]', prop: 'text' }],
        'menu_all': [{ sel: 'nav ul li:nth-of-type(1) a', prop: 'text' }],
        'menu_today': [{ sel: 'nav ul li:nth-of-type(2) a', prop: 'text' }],
        'menu_done': [{ sel: 'nav ul li:nth-of-type(3) a', prop: 'text' }],
        'menu_priority': [{ sel: 'nav ul li:nth-of-type(4) a', prop: 'text' }],
        'menu_expired': [{ sel: 'nav ul li:nth-of-type(5) a', prop: 'text' }],
        'stat_total': [{ sel: '.stat-card:nth-of-type(1) .label', prop: 'text' }],
        'stat_active': [{ sel: '.stat-card:nth-of-type(2) .label', prop: 'text' }],
        'stat_done': [{ sel: '.stat-card:nth-of-type(3) .label', prop: 'text' }],
        'stat_expired': [{ sel: '.stat-card:nth-of-type(4) .label', prop: 'text' }],
        'filter_all': [{ sel: '.filters a[href*="status=toate"] button', prop: 'text' }, { sel: '.filters a[href*="status=toate"]', prop: 'text' }],
        'filter_active': [{ sel: '.filters a[href*="status=active"] button', prop: 'text' }],
        'filter_done': [{ sel: '.filters a[href*="status=finalizate"] button', prop: 'text' }],
        'filter_all_categories': [{ sel: '.filters select option[value="toate"]', prop: 'text' }],
        'priority_high': [{ sel: '.filters select option[value="ridicată"]', prop: 'text' }],
        'priority_med': [{ sel: '.filters select option[value="medie"]', prop: 'text' }],
        'priority_low': [{ sel: '.filters select option[value="scăzută"]', prop: 'text' }],
        'add_task': [{ sel: '#openModal', prop: 'text' }],
        'no_active_tasks': [{ sel: 'p[style*="Nicio sarcină"]', prop: 'text' }, { sel: 'p.section-label', prop: 'text' }],
        'contact_heading': [{ sel: '.contact-box h2', prop: 'text' }],
        'contact_send': [{ sel: 'form[action="contact.php"] button[type="submit"]', prop: 'text' }],
        'footer_copy_full': [{ sel: 'footer', prop: 'text' }]
    });

    function apply(trans){
        Object.keys(map).forEach(key => {
            const targets = map[key];
            const txt = trans[key];
            if (!txt) return;
            targets.forEach(({sel, prop}) => {
                document.querySelectorAll(sel).forEach(el => {
                    if (prop === 'text') el.textContent = txt;
                    else if (prop === 'html') el.innerHTML = txt;
                    else if (prop === 'placeholder') el.setAttribute('placeholder', txt);
                    else if (prop === 'title') el.setAttribute('title', txt);
                    else if (prop === 'data-saving') el.setAttribute('data-saving', txt);
                    else if (prop === 'data-delete-confirm') document.body.setAttribute('data-delete-confirm', txt);
                });
            });
        });
    }

    async function init(){
        let sel = document.getElementById('langSelect');
        if (!sel) {
            const container = document.querySelector('.nav-actions') || document.querySelector('.header-right') || document.querySelector('header') || document.body;
            const created = document.createElement('select');
            created.id = 'langSelect';
            created.setAttribute('aria-label', 'Language');
            created.style.marginLeft = '8px';
            created.style.background = 'transparent';
            created.style.border = '1px solid rgba(0,0,0,0.08)';
            created.style.padding = '6px';
            created.style.borderRadius = '6px';
            const optRo = document.createElement('option'); optRo.value = 'ro'; optRo.textContent = 'RO';
            const optEn = document.createElement('option'); optEn.value = 'en'; optEn.textContent = 'EN';
            created.appendChild(optRo); created.appendChild(optEn);
            container.appendChild(created);
            sel = created;
        }

        const stored = localStorage.getItem('lang');
        const docLang = document.documentElement && document.documentElement.lang ? document.documentElement.lang : null;
            let lang = stored || (sel && sel.value) || 'ro';
            if (docLang && docLang !== lang) {
                lang = docLang;
                localStorage.setItem('lang', lang);
            } else if (!stored) {
                localStorage.setItem('lang', lang);
            }
        const trans = await loadLang(lang);
        if (trans) apply(trans);
        const page = window.location.pathname.split('/').pop();
        if (page === 'login.php'){
            const h = document.querySelector('.auth-box h2');
            if (h && trans.login_h2) h.innerHTML = trans.login_h2;
            const btn = document.querySelector('form[action="login.php"] button[type="submit"]');
            if (btn && trans.login_submit) btn.textContent = trans.login_submit;
            const linkA = document.querySelector('.auth-box .link a');
            if (linkA && trans.register_link) linkA.textContent = trans.register_link;
        } else if (page === 'register.php'){
            const h = document.querySelector('.auth-box h2');
            if (h && trans.register_h2) h.innerHTML = trans.register_h2;
            const btn = document.querySelector('form[action="register.php"] button[type="submit"]');
            if (btn && trans.register_submit) btn.textContent = trans.register_submit;
            const linkA = document.querySelector('.auth-box .link a');
            if (linkA && trans.login_link) linkA.textContent = trans.login_link;
        } else if (page === 'contact.php'){
            const h = document.querySelector('.contact-box h2');
            if (h && trans.contact) h.textContent = trans.contact;
        } else if (page === 'todo.php'){
            const logoutBtn = document.querySelector('.header-right .notif-btn');
            if (logoutBtn && trans.logout) logoutBtn.title = trans.logout;
            const addBtn = document.getElementById('openModal');
            if (addBtn && trans.modal_title) addBtn.textContent = trans.modal_title;
            const mainH = document.querySelector('main h1');
            if (mainH && trans['hero_title']) mainH.innerHTML = trans['hero_title'];
        }

        if (sel){ sel.value = lang; sel.addEventListener('change', async ()=>{
                const newL = sel.value; localStorage.setItem('lang', newL);
                const t = await loadLang(newL);
                if (t) { apply(t);
                    const p = window.location.pathname.split('/').pop();
                    if (p === 'login.php' && document.querySelector('.auth-box h2') && t.login_h2) document.querySelector('.auth-box h2').innerHTML = t.login_h2;
                    if (p === 'register.php' && document.querySelector('.auth-box h2') && t.register_h2) document.querySelector('.auth-box h2').innerHTML = t.register_h2;
                    if (p === 'contact.php' && document.querySelector('.contact-box h2') && t.contact) document.querySelector('.contact-box h2').textContent = t.contact;
                    if (p === 'todo.php'){
                        if (document.querySelector('.header-right .notif-btn') && t.logout) document.querySelector('.header-right .notif-btn').title = t.logout;
                        if (document.getElementById('openModal') && t.modal_title) document.getElementById('openModal').textContent = t.modal_title;
                        if (document.querySelector('main h1') && t['hero_title']) document.querySelector('main h1').innerHTML = t['hero_title'];
                    }
                }
        }); }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();