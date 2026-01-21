<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema Universitario</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --light-bg: #f8fafc;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --border-color: #e2e8f0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--light-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        .login-container {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
        }
        
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.12);
        }
        
        .login-header {
            background: var(--primary-gradient);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.3;
            animation: float 20s linear infinite;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-30px, -30px) rotate(360deg); }
        }
        
        .university-logo {
            width: 64px;
            height: 64px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .university-logo i {
            font-size: 2rem;
            color: white;
        }
        
        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .login-header p {
            font-size: 0.9375rem;
            opacity: 0.9;
            font-weight: 400;
        }
        
        .login-body {
            padding: 40px 35px;
            background: white;
        }
        
        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            margin-right: 8px;
            opacity: 0.8;
        }
        
        .input-group {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }
        
        .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .input-group-text {
            background: var(--light-bg);
            border: none;
            padding: 0 16px;
            color: var(--text-light);
            font-size: 1.1rem;
        }
        
        .form-control {
            border: none;
            padding: 14px 16px;
            font-size: 0.9375rem;
            background: white;
            color: var(--text-dark);
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            box-shadow: none;
            background: white;
        }
        
        .form-control::placeholder {
            color: #94a3b8;
            font-size: 0.9375rem;
        }
        
        .form-control-lg {
            padding: 14px 16px;
        }
        
        .invalid-feedback {
            font-size: 0.8125rem;
            margin-top: 6px;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 14px 16px;
            font-size: 0.875rem;
            margin-bottom: 24px;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left: 4px solid #22c55e;
            color: #166534;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            margin: 0;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid #cbd5e1;
            margin-right: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .form-check-label {
            font-size: 0.875rem;
            color: var(--text-light);
            cursor: pointer;
        }
        
        .btn-login {
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            padding: 16px;
            font-size: 0.9375rem;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            letter-spacing: 0.3px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
            color: white;
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .additional-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            text-align: center;
        }
        
        .security-info {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8125rem;
            color: var(--text-light);
            background: var(--light-bg);
            padding: 8px 16px;
            border-radius: 20px;
        }
        
        .security-info i {
            color: #22c55e;
            font-size: 0.875rem;
        }
        
        .page-footer {
            margin-top: 30px;
            text-align: center;
        }
        
        .copyright {
            font-size: 0.8125rem;
            color: var(--text-light);
            margin-bottom: 8px;
        }
        
        .version {
            font-size: 0.75rem;
            color: #94a3b8;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }
        
        .features {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .feature {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            max-width: 100px;
        }
        
        .feature-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .feature-icon i {
            font-size: 1.25rem;
            color: var(--primary-color);
        }
        
        .feature-text {
            font-size: 0.75rem;
            color: var(--text-light);
            line-height: 1.4;
        }
        
        /* Responsive Design */
        @media (max-width: 576px) {
            .login-container {
                padding: 10px;
            }
            
            .login-header {
                padding: 30px 20px;
            }
            
            .login-body {
                padding: 30px 20px;
            }
            
            .login-header h1 {
                font-size: 1.5rem;
            }
            
            .features {
                gap: 20px;
            }
        }
        
        @media (max-width: 400px) {
            .features {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
        }
        
        /* Animation for form elements */
        .form-group {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        
        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Loading state for button */
        .btn-login.loading {
            position: relative;
            color: transparent;
        }
        
        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Password toggle */
        .password-toggle {
            background: transparent;
            border: none;
            color: var(--text-light);
            padding: 0 16px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Encabezado -->
            <div class="login-header">
                <div class="university-logo">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <h1>Sistema Universitario</h1>
                <p>Portal de Acceso Académico</p>
            </div>
            
            
            <!-- Cuerpo del formulario -->
            <div class="login-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>{{ session('status') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <!-- Campo Email -->
                    <div class="form-group mb-4">
                        <label for="correo" class="form-label">
                            <i class="bi bi-envelope"></i>Correo 
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-at"></i>
                            </span>
                            <input 
                                type="email" 
                                class="form-control @error('correo') is-invalid @enderror" 
                                id="correo" 
                                name="correo"
                                value="{{ old('correo') }}"
                                placeholder="nombre@universidad.edu"
                                required 
                                autocomplete="email"
                                autofocus>
                            @error('correo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <small class="text-muted d-block mt-1 ms-1" style="font-size: 0.75rem;">
                            Usa tu correo institucional
                        </small>
                    </div>

                    <!-- Campo Contraseña -->
                    <div class="form-group mb-4">
                        <label for="password" class="form-label">
                            <i class="bi bi-shield-lock"></i>Contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-key"></i>
                            </span>
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password">
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Opciones -->
                    <div class="form-group mb-4 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Recordar en este dispositivo
                            </label>
                        </div>
                    </div>

                    <!-- Botón de Login -->
                    <div class="form-group">
                        <button type="submit" class="btn-login" id="loginButton">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Acceder al Sistema</span>
                        </button>
                    </div>
                </form>
                
                <!-- Información adicional -->
                <div class="additional-info">
                    <div class="security-info">
                        <i class="bi bi-shield-check"></i>
                        <span>Conexión segura mediante SSL/TLS</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="page-footer">
            <p class="copyright">&copy; {{ date('Y') }} Universidad Bolivariana de Venezuela.</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts después de 5 segundos
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    const icon = this.querySelector('i');
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                });
            }
            
            // Form submission with loading state
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            
            if (loginForm && loginButton) {
                loginForm.addEventListener('submit', function(e) {
                    // Basic client-side validation
                    const email = document.getElementById('correo').value;
                    const password = document.getElementById('password').value;
                    
                    if (!email || !password) {
                        e.preventDefault();
                        return;
                    }
                    
                    // Show loading state
                    loginButton.classList.add('loading');
                    loginButton.setAttribute('disabled', 'disabled');
                    
                    // Simulate processing delay
                    setTimeout(() => {
                        if (!loginForm.checkValidity()) {
                            loginButton.classList.remove('loading');
                            loginButton.removeAttribute('disabled');
                        }
                    }, 2000);
                });
            }
            
            // Auto-capitalize first letter of email local part
            const emailInput = document.getElementById('correo');
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const email = this.value;
                    const atIndex = email.indexOf('@');
                    
                    if (atIndex > 0) {
                        const localPart = email.substring(0, atIndex);
                        const domain = email.substring(atIndex);
                        
                        // Capitalize first letter of local part
                        const capitalizedLocal = localPart.charAt(0).toUpperCase() + localPart.slice(1).toLowerCase();
                        
                        // Keep domain in lowercase
                        this.value = capitalizedLocal + domain.toLowerCase();
                    }
                });
            }
            
            // Add focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });
            
            // Preload animation
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                group.style.animationDelay = `${(index + 1) * 0.1}s`;
            });
        });
    </script>
</body>
</html>