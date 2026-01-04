<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Chat Universitario</title>
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
        }
        .hero-content {
            text-align: center;
        }
        .btn-hero {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            color: white;
            padding: 12px 30px;
            margin: 10px;
            transition: all 0.3s;
        }
        .btn-hero:hover {
            background: white;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 hero-content">
                    <i class="bi bi-chat-dots display-1 mb-4"></i>
                    <h1 class="display-4 fw-bold mb-4">Sistema de Chat Universitario</h1>
                    <p class="lead mb-5">Plataforma de comunicación para estudiantes, profesores y administradores</p>
                    
                    <div class="d-flex justify-content-center flex-wrap">
                        <a href="{{ route('login') }}" class="btn btn-hero btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </a>
                    </div>
                    
                    <div class="row mt-5">
                        <div class="col-md-4">
                            <i class="bi bi-people fs-1 mb-3"></i>
                            <h5>Comunidad</h5>
                            <p class="small">Conecta con toda la comunidad universitaria</p>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-chat-left-text fs-1 mb-3"></i>
                            <h5>Chat en Tiempo Real</h5>
                            <p class="small">Comunícate instantáneamente</p>
                        </div>
                        <div class="col-md-4">
                            <i class="bi bi-shield-check fs-1 mb-3"></i>
                            <h5>Seguro</h5>
                            <p class="small">Plataforma segura y confiable</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>