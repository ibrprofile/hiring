<?php
// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $telegram = htmlspecialchars($_POST['telegram'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $specialization = htmlspecialchars($_POST['specialization'] ?? '');
    $experience = htmlspecialchars($_POST['experience'] ?? '');
    $portfolio = htmlspecialchars($_POST['portfolio'] ?? '');
    
    if (!empty($name) && !empty($telegram) && !empty($email) && !empty($specialization)) {
        // Формируем текст письма
        $message_body = "
        === НОВАЯ ЗАЯВКА НА ВСТУПЛЕНИЕ В КОМАНДУ ===
        
        Имя: $name
        Телеграм: $telegram
        Почта: $email
        Специализация: $specialization
        Опыт работы: $experience
        Портфолио: $portfolio
        
        Дата подачи: " . date('d.m.Y H:i:s') . "
        IP адрес: " . $_SERVER['REMOTE_ADDR'] . "
        User Agent: " . $_SERVER['HTTP_USER_AGENT'];
        
        // Заголовки для письма
        $to = 'ibrprofile@bk.ru';
        $subject = '🚀 Новая заявка специалиста - ' . $specialization;
        
        // Заголовки
        $headers = "From: noreply@" . $_SERVER['HTTP_HOST'] . "\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Альтернативный метод отправки
        $success = false;
        
        if (@mail($to, $subject, $message_body, $headers)) {
            $success = true;
        } else {
            $success = @mb_send_mail($to, $subject, $message_body, $headers);
        }
        
        // Если не удалось отправить, записываем в файл
        if (!$success) {
            if (!file_exists('logs')) {
                mkdir('logs', 0755, true);
            }
            
            $log_file = 'logs/applications_' . date('Y-m-d') . '.txt';
            $log_content = "=== НОВАЯ ЗАЯВКА (" . date('Y-m-d H:i:s') . ") ===\n";
            $log_content .= $message_body . "\n" . str_repeat("=", 50) . "\n\n";
            
            if (file_put_contents($log_file, $log_content, FILE_APPEND)) {
                $success = true;
            } else {
                $error = true;
            }
        }
    } else {
        $error = true;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@ibrprofile - Карьерные возможности | Присоединяйся к команде профессионалов</title>
    <meta name="description" content="Присоединяйтесь к команде @ibrprofile. Мы ищем талантливых разработчиков, дизайнеров и специалистов в области IT.">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --accent-color: #0ea5e9;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
            --dark-bg: #0f172a;
            --dark-surface: #1e293b;
            --dark-surface-light: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --glass-bg: rgba(30, 41, 59, 0.8);
            --glass-border: rgba(148, 163, 184, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--dark-bg);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Background Effects */
        .background-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(37, 99, 235, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37, 99, 235, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -2;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .background-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 20%, rgba(37, 99, 235, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(14, 165, 233, 0.15) 0%, transparent 50%),
                        radial-gradient(circle at 40% 60%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
            z-index: -1;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(15, 23, 42, 0.98);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-color);
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            cursor: pointer;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 8rem 0 4rem;
        }
        .hero-content {
    display: flex;
    flex-direction: column; /* Располагаем элементы в колонку */
    align-items: center; /* Центрируем по горизонтали */
    justify-content: center; /* Центрируем по вертикали, если необходимо */
    text-align: center; /* Центрируем текст внутри элемента */
}


        .hero-content h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeInUp 1s ease-out;
        }

        .hero-content .subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
            max-width: 600px;
            animation: fadeInUp 1s ease-out 0.2s both;
            text-align: center;

        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: block;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* Glass Card */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
            padding: 2rem;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: rgba(37, 99, 235, 0.3);
        }

        /* Sections */
        .section {
            padding: 5rem 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .section-subtitle {
            font-size: 1.125rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Specializations Tabs */
        .specializations-tabs {
            display: flex;
            justify-content: center;
            margin-bottom: 3rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 2rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .tab-button.active {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .tab-button:hover:not(.active) {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s ease-out;
        }

        .tab-content.active {
            display: block;
        }

        .specialization-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .specialization-card {
            background: var(--dark-surface);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 2rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .specialization-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .specialization-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .specialization-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .specialization-description {
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .specialization-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .skill-tag {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Process Timeline */
        .process-timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .timeline-number {
            width: 3rem;
            height: 3rem;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            margin-right: 2rem;
            flex-shrink: 0;
        }

        .timeline-content {
            flex: 1;
        }

        .timeline-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .timeline-description {
            color: var(--text-secondary);
        }

        /* FAQ Accordion */
        .faq-item {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            overflow: hidden;
        }

        .faq-question {
            padding: 1.5rem;
            background: var(--dark-surface);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.3s ease;
        }

        .faq-question:hover {
            background: var(--dark-surface-light);
        }

        .faq-question h3 {
            font-weight: 600;
            margin: 0;
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-answer {
            padding: 1.5rem;
            max-height: 200px;
        }

        /* Form Styles */
        .application-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 1rem;
            background: var(--dark-surface);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: var(--text-muted);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .submit-button {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 0.5rem;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
        }

        .submit-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Contact Section */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .contact-card {
            text-align: center;
            padding: 2rem;
            background: var(--dark-surface);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .contact-icon {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .contact-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 2rem;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .contact-link:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Messages */
        .message {
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideInDown 0.5s ease-out;
        }

        .message.success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: var(--success-color);
        }

        .message.error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--error-color);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .hero {
                padding: 6rem 0 3rem;
            }

            .hero-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .section {
                padding: 3rem 0;
            }

            .section-title {
                font-size: 2rem;
            }

            .specializations-tabs {
                flex-direction: column;
                align-items: center;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .timeline-item {
                flex-direction: column;
                text-align: center;
            }

            .timeline-number {
                margin-right: 0;
                margin-bottom: 1rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 1rem;
            }

            .glass-card {
                padding: 1.5rem;
            }

            .hero-content h1 {
                font-size: 2rem;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="background-grid"></div>
    <div class="background-gradient"></div>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="logo">@ibrprofile</a>
            <ul class="nav-links">
                <li><a href="#specializations">Специализации</a></li>
                
                <li><a href="#application">Подать заявку</a></li>
                <li><a href="#contact">Контакты</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Присоединяйся к команде профессионалов</h1>
                <p class="subtitle">
                    Мы создаем инновационные IT-решения и ищем талантливых специалистов, 
                    готовых развиваться в динамичной среде высокотехнологичных проектов
                </p>
            </div>
                <div class="hero-content-2">    
                
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number" data-target="50">0</span>
                            <span class="stat-label">Активных проектов</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-target="25">0</span>
                            <span class="stat-label">Специалистов в команде</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-target="98">0</span>
                            <span class="stat-label">% довольных клиентов</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-target="3">0</span>
                            <span class="stat-label">Года на рынке</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Specializations Section -->
    <section class="section" id="specializations">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Востребованные специализации</h2>
                <p class="section-subtitle">
                    Мы ищем экспертов в различных областях IT для работы над амбициозными проектами
                </p>
            </div>

            <div class="specializations-tabs">
                <button class="tab-button active" data-tab="development">Разработка</button>
                <button class="tab-button" data-tab="design">Дизайн</button>
                <button class="tab-button" data-tab="marketing">Маркетинг</button>
                <button class="tab-button" data-tab="management">Управление</button>
            </div>

            <div class="tab-content active" id="development">
                <div class="specialization-grid">
                    <div class="specialization-card">
                        <div class="specialization-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3 class="specialization-title">Frontend Developer</h3>
                        <p class="specialization-description">
                            Создание современных пользовательских интерфейсов с использованием передовых технологий
                        </p>
                        <div class="specialization-skills">
                            <span class="skill-tag">React</span>
                            <span class="skill-tag">Vue.js</span>
                            <span class="skill-tag">TypeScript</span>
                            <span class="skill-tag">Next.js</span>
                        </div>
                    </div>

                    <div class="specialization-card">
                        <div class="specialization-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <h3 class="specialization-title">Backend Developer</h3>
                        <p class="specialization-description">
                            Разработка серверной логики, API и архитектуры высоконагруженных систем
                        </p>
                        <div class="specialization-skills">
                            <span class="skill-tag">Node.js</span>
                            <span class="skill-tag">Python</span>
                            <span class="skill-tag">PostgreSQL</span>
                            <span class="skill-tag">Docker</span>
                        </div>
                    </div>

                    <div class="specialization-card">
                        <div class="specialization-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="specialization-title">Mobile Developer</h3>
                        <p class="specialization-description">
                            Создание нативных и кроссплатформенных мобильных приложений
                        </p>
                        <div class="specialization-skills">
                            <span class="skill-tag">React Native</span>
                            <span class="skill-tag">Flutter</span>
                            <span class="skill-tag">Swift</span>
                            <span class="skill-tag">Kotlin</span>
                        </div>
                    </div>

                    <div class="specialization-card">
                        <div class="specialization-icon">
                            <i class="fas fa-cloud"></i>
                        </div>
                        <h3 class="specialization-title">DevOps Engineer</h3>
                        <p class="specialization-description">
                            Автоматизация процессов разработки и развертывания приложений
                        </p>
                        <div class="specialization-skills">
                            <span class="skill-tag">AWS</span>
                            <span class="skill-tag">Kubernetes</span>
                            <span class="skill-tag">CI/CD</span>
                            <span class="skill-tag">Terraform</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="design">
                <div class="specialization-grid">
                    <div class="specialization-card">
                        <div class="specialization-icon">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <h3 class="specialization-title">UI/UX Designer</h3>
                        <p class="specialization-description">
                            Проектирование интуитивных и привлекательных пользовательских интерфейсов
                        </p>
                        <div class="specialization-skills">
                            <span class="skill-tag">Figma</span>
                            <span class="skill-tag">Adobe XD</span>
                            <span class="skill-tag">Sketch</span>
                            <span class="skill-tag">Prototyping</span>
                        </div>
                    </div>

                    <div class="specialization-card">
                        <div class="specialization-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h3 class="specialization-title">Graphic Designer</h3>
                        <p class="specialization-description">
                            Создание визуальной айдентики и графических материалов для брендов
                        </p>
                        <div class="specialization-skills">
                            <span class="skill-tag">Photoshop</span>
                            <span class="skill-tag">Illustrator</span>
                            <span class="skill-tag">After Effects</span>
                            <span class="skill-tag">Branding</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="marketing">
                <div class="specialization-grid">
                    <div class="specialization-card">
                        <div class="specialization-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="specialization-title">Digital Marketer</h3>
                        <p class="specialization-description">
                            Продвижение продуктов в цифровых каналах и анализ эффективности кампаний
                        </p>
                        <div class="specialization-skills">
                            <span class="skill-tag">Google Ads</span>
                            <span class="skill-tag">Facebook Ads</span>
                            <span class="skill-tag">Analytics</span>
                            <span class="skill-tag">SEO</span>
                        </div>
                    </div>

                    <div class="specialization-card">
                        <div class="specialization-icon">
                            <i class="fas fa-pen-nib"></i>
                        </div>
                        <h3 class="specialization-title">Content Creator</h3>
                        <p class="specialization-description">
                            Создание качественного контента для различных платформ и аудиторий
                        </p>
                        <div class="specialization-skills">
                            <span class="skill-tag">Copywriting</span>
                            <span class="skill-tag">SMM</span>
                            <span class="skill-tag">Video Editing</span>
                            <span class="skill-tag">Content Strategy</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="management">
                <div class="specialization-grid">
                    <div class="specialization-card">
                        <div class="specialization-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h3 class="specialization-title">Project Manager</h3>
                        <p class="specialization-description">
                            Управление проектами от концепции до успешного запуска
                        </p>
                        <div class="specialization-skills">
                            <span class="skill-tag">Agile</span>
                            <span class="skill-tag">Scrum</span>
                            <span class="skill-tag">Jira</span>
                            <span class="skill-tag">Risk Management</span>
                        </div>
                    </div>

                    <div class="specialization-card">
                        <div class="specialization-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h3 class="specialization-title">Team Lead</h3>
                        <p class="specialization-description">
                            Техническое лидерство и координация работы команды разработчиков
                        </p>
                        <div class="specialization-skills">
                            <span class="skill-tag">Leadership</span>
                            <span class="skill-tag">Mentoring</span>
                            <span class="skill-tag">Architecture</span>
                            <span class="skill-tag">Code Review</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <!-- FAQ Section -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Часто задаваемые вопросы</h2>
                <p class="section-subtitle">
                    Ответы на популярные вопросы о работе в нашей команде
                </p>
            </div>

            <div class="glass-card">
                

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Какие требования к опыту работы?</h3>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Мы рассматриваем кандидатов с различным уровнем опыта - от junior до senior специалистов. Важнее мотивация и готовность развиваться.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Как происходит оплата труда?</h3>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Оплата зависит от сложности проекта и вашей экспертизы. Мы предлагаем конкурентные ставки и своевременные выплаты.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Что делать, если я сомневаюсь в своих умениях?</h3>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Ничего страшного, наша команда поможет вам в ваших вопросах и проблемах, возникающих во время работы.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Сколько времени занимает процесс отбора?</h3>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Обычно процесс занимает 1-2 дня от подачи заявки до финального решения. Мы стараемся давать обратную связь максимально быстро.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Application Form Section -->
    <section class="section" id="application">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Подать заявку</h2>
                <p class="section-subtitle">
                    Заполните форму, и мы свяжемся с вами в ближайшее время
                </p>
            </div>

            <?php if (isset($success)): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i>
                    <span>Спасибо! Ваша заявка успешно отправлена. Мы свяжемся с вами в ближайшее время.</span>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Произошла ошибка при отправке заявки. Пожалуйста, проверьте все поля и попробуйте снова.</span>
                </div>
            <?php endif; ?>

            <div class="glass-card">
                <form method="POST" class="application-form" id="applicationForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="name">
                                <i class="fas fa-user"></i> Полное имя *
                            </label>
                            <input type="text" id="name" name="name" class="form-input" placeholder="Иван Иванов" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">
                                <i class="fas fa-envelope"></i> Email *
                            </label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="ivan@example.com" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="telegram">
                                <i class="fab fa-telegram"></i> Telegram 
                            </label>
                            <input type="url" id="telegram" name="telegram" class="form-input" placeholder="https://t.me/username">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="experience">
                                <i class="fas fa-clock"></i> Опыт работы
                            </label>
                            <select id="experience" name="experience" class="form-select">
                                <option value="">Выберите опыт</option>
                                <option value="junior">Junior (до 2 лет)</option>
                                <option value="middle">Middle (2-5 лет)</option>
                                <option value="senior">Senior (5+ лет)</option>
                                <option value="lead">Team Lead/Architect</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="specialization">
                            <i class="fas fa-tools"></i> Специализация и навыки *
                        </label>
                        <textarea id="specialization" name="specialization" class="form-textarea" rows="4" 
                                  placeholder="Опишите вашу специализацию, основные навыки и технологии, с которыми работаете..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="portfolio">
                            <i class="fas fa-link"></i> Портфолио/GitHub
                        </label>
                        <input type="url" id="portfolio" name="portfolio" class="form-input" 
                               placeholder="https://github.com/username или ссылка на портфолио">
                    </div>

                    <button type="submit" class="submit-button" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Отправить заявку
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section" id="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Свяжитесь с нами</h2>
                <p class="section-subtitle">
                    Есть вопросы? Мы всегда готовы помочь и ответить на ваши вопросы
                </p>
            </div>

            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fab fa-telegram"></i>
                    </div>
                    <h3>Telegram</h3>
                    <p>Быстрая связь для срочных вопросов</p>
                    <a href="https://t.me/ibrprofile" target="_blank" class="contact-link">
                        <i class="fab fa-telegram"></i>
                        Написать в Telegram
                    </a>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3>Email</h3>
                    <p>Для официальных запросов и документов</p>
                    <a href="mailto:ibrprofile@bk.ru" class="contact-link">
                        <i class="fas fa-envelope"></i>
                        Отправить письмо
                    </a>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3>Веб-сайт</h3>
                    <p>Узнайте больше о наших проектах</p>
                    <a href="https://ibrprofile.ru" target="_blank" class="contact-link">
                        <i class="fas fa-external-link-alt"></i>
                        Посетить сайт
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animated counters
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const increment = target / 100;
                let current = 0;
                
                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.ceil(current);
                        setTimeout(updateCounter, 20);
                    } else {
                        counter.textContent = target;
                    }
                };
                
                updateCounter();
            });
        }

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('hero-stats')) {
                        animateCounters();
                    }
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe elements for animation
        document.querySelectorAll('.hero-stats, .glass-card, .specialization-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });

        // Tabs functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                
                // Remove active class from all buttons and contents
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked button and corresponding content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', function() {
                const faqItem = this.parentElement;
                const isActive = faqItem.classList.contains('active');
                
                // Close all FAQ items
                document.querySelectorAll('.faq-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Open clicked item if it wasn't active
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });

        // Form submission with AJAX
        document.getElementById('applicationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<div class="loading-spinner"></div> Отправляем...';
            submitBtn.disabled = true;
            
            const formData = new FormData(this);
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                // Parse response
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                
                // Remove existing messages
                document.querySelectorAll('.message').forEach(el => el.remove());
                
                // Check for success or error messages
                const successMessage = tempDiv.querySelector('.message.success');
                const errorMessage = tempDiv.querySelector('.message.error');
                
                if (successMessage) {
                    // Insert success message
                    this.parentNode.insertBefore(successMessage.cloneNode(true), this);
                    // Reset form
                    this.reset();
                } else if (errorMessage) {
                    // Insert error message
                    this.parentNode.insertBefore(errorMessage.cloneNode(true), this);
                } else {
                    // Show generic error
                    const genericError = document.createElement('div');
                    genericError.className = 'message error';
                    genericError.innerHTML = `
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Произошла ошибка при отправке. Пожалуйста, попробуйте позже или свяжитесь с нами через Telegram.</span>
                    `;
                    this.parentNode.insertBefore(genericError, this);
                }
                
                // Scroll to message
                window.scrollTo({
                    top: this.parentNode.offsetTop - 100,
                    behavior: 'smooth'
                });
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Remove existing messages
                document.querySelectorAll('.message').forEach(el => el.remove());
                
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'message error';
                errorDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Произошла ошибка при отправке. Пожалуйста, попробуйте позже или свяжитесь с нами через Telegram.</span>
                `;
                this.parentNode.insertBefore(errorDiv, this);
                
                // Scroll to message
                window.scrollTo({
                    top: this.parentNode.offsetTop - 100,
                    behavior: 'smooth'
                });
            })
            .finally(() => {
                // Restore button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        // Form validation
        document.querySelectorAll('.form-input, .form-textarea, .form-select').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.style.borderColor = 'var(--error-color)';
                } else {
                    this.style.borderColor = 'var(--success-color)';
                }
            });

            input.addEventListener('focus', function() {
                this.style.borderColor = 'var(--primary-color)';
            });
        });

        // Parallax effect for background
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const backgroundGrid = document.querySelector('.background-grid');
            const backgroundGradient = document.querySelector('.background-gradient');
            
            backgroundGrid.style.transform = `translateY(${scrolled * 0.3}px)`;
            backgroundGradient.style.transform = `translateY(${scrolled * 0.2}px)`;
        });

        // Initialize animations on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger hero animations
            setTimeout(() => {
                document.querySelector('.hero-content h1').style.opacity = '1';
                document.querySelector('.hero-content h1').style.transform = 'translateY(0)';
            }, 300);
        });
    </script>
</body>
</html>
