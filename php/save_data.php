<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

if ($action !== 'contact' && $action !== 'lang') {
    requireLogin();
    $user = getCurrentUser();
} else {
    $user = null;
}

switch ($action) {
    case 'add':
        if (empty($_POST['title'])) {
            echo json_encode(['success' => false, 'error' => 'Titlul este obligatoriu.']);
            exit;
        }
        $task = addItem($user['id'], [
            'title'       => $_POST['title'],
            'description' => $_POST['description'] ?? '',
            'category'    => $_POST['category']    ?? 'Personal',
            'priority'    => $_POST['priority']    ?? 'medie',
            'due_date'    => $_POST['due_date']    ?? '',
        ]);
        echo json_encode(['success' => true, 'task' => $task]);
        break;

    case 'contact':
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$name || !$email || !$message) {
            echo json_encode(['success' => false, 'error' => 'Completează toate câmpurile.']);
            exit;
        }

        $to = 'contact@todo.local';
        $subject = "[ToDo Contact] Mesaj de la {$name}";
        $body = "Nume: {$name}\nEmail: {$email}\n\n{$message}\n";
        $headers = "From: {$name} <{$email}>\r\nReply-To: {$email}\r\n";

        $sent = false;
        if (function_exists('mail')) {
            $sent = @mail($to, $subject, $body, $headers);
        }

        if (!$sent) {
            error_log("[ToDo Contact] {$subject}\n{$body}");
        }

        echo json_encode(['success' => true]);
        break;

    case 'lang':
        $lang = preg_replace('/[^a-zA-Z0-9_-]/', '', ($_REQUEST['lang'] ?? 'ro'));

        $translations = [
            'ro' => [
                'auth' => 'Autentificare',
                'try_free' => 'Încearcă gratuit',
                'contact' => 'Contact',
                'login' => 'Autentificare',
                'register' => 'Înregistrare',
                'hero_title' => 'Organizează-ți <span class="accent">ziua</span>',
                'hero_line2' => 'fără efort.',
                'hero_p' => 'Gestionează sarcini, termene-limită și priorități dintr-un singur loc. Simplu, rapid și accesibil de oriunde.',
                'hero_cta_primary' => 'Creează cont gratuit',
                'hero_cta_ghost' => 'Intră în cont →',
                'hero_note' => 'Fără plăți · Gratuit pentru totdeauna',
                'cta_heading' => 'Gata să fii mai organizat?',
                'cta_p' => 'Creează-ți contul acum și începe să-ți gestionezi sarcinile.\nEste gratuit și durează mai puțin de un minut.',
                'cta_cta_primary' => 'Încearcă gratuit',
                'cta_cta_ghost' => 'Am deja cont',
                'step1_title' => 'Planifică rapid',
                'step1_desc' => 'Adaugă sarcini cu detalii și termene-limită în câteva secunde.',
                'step2_title' => 'Organizează',
                'step2_desc' => 'Grupează și prioritizează sarcinile pentru a rămâne concentrat.',
                'step3_title' => 'Urmărește progresul',
                'step3_desc' => 'Bifează sarcinile finalizate și urmărește evoluția.',
                'footer_copy' => '© 2026 ToDo App — Micu Nicolae',
                'footer_login' => 'Autentificare',
                'footer_register' => 'Înregistrare',
                'modal_title' => 'Adaugă sarcină',
                'modal_save' => 'Salvează sarcina',
                'modal_saving' => 'Se salvează...',
                'task_title_placeholder' => 'Titlul sarcinii',
                'task_desc_placeholder' => 'Descriere (opțional)',
                'task_due_placeholder' => 'Data-limită',
                'task_category_label' => 'Categorie',
                'task_priority_label' => 'Prioritate',
                'delete_confirm' => 'Sigur vrei să ștergi această sarcină?',
                'send_msg' => 'Trimite mesajul',
                'name' => 'Nume',
                'email' => 'Email',
                'message' => 'Mesaj',
                'theme_toggle' => 'Schimbă tema',
                'search_placeholder' => 'Caută sarcini...',
                'login_h2' => '✦ Autentificare',
                'register_h2' => '✦ Creare cont',
                'login_submit' => 'Intră în cont',
                'register_submit' => 'Creează cont',
                'no_account' => 'Nu ai cont? Înregistrează-te',
                'have_account' => 'Ai deja cont? Autentifică-te',
                'logout' => 'Deconectare',
                'register_link' => 'Înregistrează-te',
                'login_link' => 'Autentifică-te',
                'nav_auth' => 'Autentificare',
                'nav_register' => 'Încearcă gratuit',
                'nav_contact' => 'Contact',
                'menu_all' => 'Toate sarcinile',
                'menu_today' => 'Azi',
                'menu_done' => 'Finalizate',
                'menu_priority' => 'Priorități înalte',
                'menu_expired' => 'Expirate',
                'stat_total' => 'Total sarcini',
                'stat_active' => 'Active',
                'stat_done' => 'Finalizate',
                'stat_expired' => 'Expirate',
                'filter_all' => 'Toate',
                'filter_active' => 'Active',
                'filter_done' => 'Finalizate',
                'filter_all_categories' => 'Toate categoriile',
                'priority_high' => 'Ridicată',
                'priority_med' => 'Medie',
                'priority_low' => 'Scăzută',
                'add_task' => '+ Sarcină nouă',
                'no_active_tasks' => 'Nicio sarcină activă.',
                'contact_heading' => 'Contact',
                'contact_send' => 'Trimite mesaj',
                'footer_copy_full' => '© 2026 ToDo App — Micu Nicolae',
                // Index-specific
                'mini_menu' => 'Meniu',
                'mini_all' => 'Toate sarcinile',
                'mini_today' => 'Azi',
                'mini_done' => 'Finalizate',
                'mini_expired' => 'Expirate',
                'mini_categories' => 'Categorii',
                'mini_stat_total_label' => 'Total',
                'mini_stat_active_label' => 'Active',
                'mini_stat_done_label' => 'Finalizate',
                'mini_stat_expired_label' => 'Expirate',
                'feat_title_manage' => 'Gestionare sarcini',
                'feat_manage_desc' => 'Adaugă, editează și șterge sarcini cu titlu, descriere, dată-limită și prioritate. Totul cu un singur click.',
                'feat_title_cat' => 'Categorii și priorități',
                'feat_cat_desc' => 'Organizează pe categorii (Muncă, Personal, Sănătate, Educație) și niveluri de prioritate pentru o vizualizare clară.',
                'feat_title_stats' => 'Statistici în timp real',
                'feat_stats_desc' => 'Monitorizează progresul cu carduri de statistici: total, active, finalizate și expirate — actualizate instant.',
                'feat_title_filter' => 'Filtrare avansată',
                'feat_filter_desc' => 'Filtrează sarcinile după stare, categorie sau prioritate. Găsești orice în secunde.',
                'feat_title_account' => 'Conturi personale',
                'feat_account_desc' => 'Înregistrare și autentificare securizată. Fiecare utilizator vede doar propriile sarcini.',
                'feat_title_fast' => 'Rapid și ușor',
                'feat_fast_desc' => 'Interfață AJAX fără reîncărcare de pagină. Acțiunile se reflectă imediat în interfață.',
                'stats_strip_cat_label' => 'Categorii de organizare',
                'stats_strip_priority_label' => 'Niveluri de prioritate',
                'stats_strip_free_label' => 'Gratuit',
                'steps_label' => 'Cum funcționează',
                'steps_title' => 'Trei pași simpli',
                'step1_title' => 'Creează contul',
                'step1_desc' => 'Înregistrare rapidă cu nume, email și parolă. Nicio informație inutilă.',
                'step2_title' => 'Adaugă sarcini',
                'step2_desc' => 'Adaugă sarcini cu titlu, categorie, prioritate și data-limită.',
                'step3_title' => 'Finalizează și urmărește',
                'step3_desc' => 'Bifează sarcinile completate și monitorizează progresul în timp real.'
            ],
            'en' => [
                'auth' => 'Sign in',
                'try_free' => 'Try for free',
                'contact' => 'Contact',
                'login' => 'Sign in',
                'register' => 'Register',
                'hero_title' => 'Organize your <span class="accent">day</span>',
                'hero_line2' => 'effortlessly.',
                'hero_p' => 'Manage tasks, deadlines and priorities in one place. Simple, fast and accessible from anywhere.',
                'hero_cta_primary' => 'Create free account',
                'hero_cta_ghost' => 'Sign in →',
                'hero_note' => 'No payments · Free forever',
                'cta_heading' => "Ready to get organized?",
                'cta_p' => "Create your account now and start managing your tasks.\nIt's free and takes less than a minute.",
                'cta_cta_primary' => 'Try for free',
                'cta_cta_ghost' => 'I already have an account',
                'step1_title' => 'Plan quickly',
                'step1_desc' => 'Add tasks with details and due dates in seconds.',
                'step2_title' => 'Organize',
                'step2_desc' => 'Group and prioritize tasks to stay focused.',
                'step3_title' => 'Track progress',
                'step3_desc' => 'Check off completed tasks and watch your progress.',
                'footer_copy' => '© 2026 ToDo App — Micu Nicolae',
                'footer_login' => 'Sign in',
                'footer_register' => 'Register',
                'modal_title' => 'Add task',
                'modal_save' => 'Save task',
                'modal_saving' => 'Saving...',
                'task_title_placeholder' => 'Task title',
                'task_desc_placeholder' => 'Description (optional)',
                'task_due_placeholder' => 'Due date',
                'task_category_label' => 'Category',
                'task_priority_label' => 'Priority',
                'delete_confirm' => 'Are you sure you want to delete this task?',
                'send_msg' => 'Send message',
                'name' => 'Name',
                'email' => 'Email',
                'message' => 'Message',
                'theme_toggle' => 'Toggle theme',
                'search_placeholder' => 'Search tasks...',
                'login_h2' => '✦ Sign in',
                'register_h2' => '✦ Create account',
                'login_submit' => 'Sign in',
                'register_submit' => 'Create account',
                'no_account' => "Don't have an account? Register",
                'have_account' => 'Already have an account? Sign in',
                'logout' => 'Sign out',
                'register_link' => 'Register',
                'login_link' => 'Sign in',
                'nav_auth' => 'Sign in',
                'nav_register' => 'Try for free',
                'nav_contact' => 'Contact',
                'menu_all' => 'All tasks',
                'menu_today' => 'Today',
                'menu_done' => 'Completed',
                'menu_priority' => 'High priority',
                'menu_expired' => 'Expired',
                'stat_total' => 'Total tasks',
                'stat_active' => 'Active',
                'stat_done' => 'Completed',
                'stat_expired' => 'Expired',
                'filter_all' => 'All',
                'filter_active' => 'Active',
                'filter_done' => 'Completed',
                'filter_all_categories' => 'All categories',
                'priority_high' => 'High',
                'priority_med' => 'Medium',
                'priority_low' => 'Low',
                'add_task' => '+ New task',
                'no_active_tasks' => 'No active tasks.',
                'contact_heading' => 'Contact',
                'contact_send' => 'Send message',
                'footer_copy_full' => '© 2026 ToDo App — Micu Nicolae',
                // Index-specific
                'mini_menu' => 'Menu',
                'mini_all' => 'All tasks',
                'mini_today' => 'Today',
                'mini_done' => 'Completed',
                'mini_expired' => 'Expired',
                'mini_categories' => 'Categories',
                'mini_stat_total_label' => 'Total',
                'mini_stat_active_label' => 'Active',
                'mini_stat_done_label' => 'Completed',
                'mini_stat_expired_label' => 'Expired',
                'feat_title_manage' => 'Manage tasks',
                'feat_manage_desc' => 'Add, edit and delete tasks with title, description, due date and priority. All with one click.',
                'feat_title_cat' => 'Categories & priorities',
                'feat_cat_desc' => 'Organize by categories (Work, Personal, Health, Education) and priority levels for a clear view.',
                'feat_title_stats' => 'Real-time statistics',
                'feat_stats_desc' => 'Monitor progress with stat cards: total, active, completed and expired — updated instantly.',
                'feat_title_filter' => 'Advanced filtering',
                'feat_filter_desc' => 'Filter tasks by status, category or priority. Find anything in seconds.',
                'feat_title_account' => 'Personal accounts',
                'feat_account_desc' => 'Secure registration and authentication. Each user sees only their tasks.',
                'feat_title_fast' => 'Fast & easy',
                'feat_fast_desc' => 'AJAX interface without page reloads. Actions reflect immediately in the UI.',
                'stats_strip_cat_label' => 'Organization categories',
                'stats_strip_priority_label' => 'Priority levels',
                'stats_strip_free_label' => 'Free',
                'steps_label' => 'How it works',
                'steps_title' => 'Three simple steps',
                'step1_title' => 'Create account',
                'step1_desc' => 'Quick signup with name, email and password. No unnecessary info.',
                'step2_title' => 'Add tasks',
                'step2_desc' => 'Add tasks with title, category, priority and due date.',
                'step3_title' => 'Finish and track',
                'step3_desc' => 'Check off completed tasks and monitor progress in real time.'
            ]
        ];

        if (!isset($translations[$lang])) {
            echo json_encode(['success' => false, 'error' => 'Limba nu este disponibilă.']);
            break;
        }

        echo json_encode($translations[$lang]);
        break;

    case 'toggle':
        $ok = toggleItem($_POST['id'] ?? '', $user['id']);
        echo json_encode(['success' => $ok]);
        break;

    case 'delete':
        $ok = deleteItem($_POST['id'] ?? '', $user['id']);
        echo json_encode(['success' => $ok]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acțiune necunoscută.']);
}