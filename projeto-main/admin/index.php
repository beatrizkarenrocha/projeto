<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-size: 14px;
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
        }
        .nav-link {
            color: #ddd;
            padding: 8px 12px;
        }
        .nav-link:hover {
            background-color: #495057;
            color: #fff;
        }
        .nav-link.active {
            background-color: #343a40;
            font-weight: bold;
            color: #fff !important;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <?php include ('../includes/siderbar.php'); ?>

    <!-- Conteúdo -->
    <div class="flex-grow-1 content" id="content">
       <a href="dashboard.php"></a>
    </div>
</div>

<script>
    document.querySelectorAll('.nav-link[data-page]').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const page = this.getAttribute('data-page');

            fetch(`${page}.php`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Página não encontrada");
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('content').innerHTML = html;

                    // Remove classe ativa anterior
                    document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));

                    // Adiciona classe ativa ao link clicado
                    this.classList.add('active');
                })
                .catch(error => {
                    document.getElementById('content').innerHTML = `<p class="text-danger">Erro: ${error.message}</p>`;
                });
        });
    });
</script>
</body>
</html>
