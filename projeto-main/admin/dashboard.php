<?php 
include_once("../conf/conexao.php");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PowerPC Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #3a7bd5;
            --primary-light: #e3f2fd;
            --secondary: #00d2ff;
            --success: #28a745;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
            --light-gray: #e9ecef;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        
        .main-content {
            padding: 2rem;
            margin-left: 0;
            transition: all 0.4s;
        }
        
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-header h1 {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .last-update {
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        /* Cards de Estatísticas */
        .stats-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 1.5rem;
            overflow: hidden;
            background: white;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stats-card .card-body {
            padding: 1.5rem;
            position: relative;
        }
        
        .stats-card .card-icon {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 2.5rem;
            opacity: 0.15;
        }
        
        .stats-card .card-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: var(--gray);
            margin-bottom: 0.5rem;
        }
        
        .stats-card .card-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .stats-card .card-change {
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .stats-card .card-change.positive {
            color: var(--success);
        }
        
        .stats-card .card-change.negative {
            color: var(--danger);
        }
        
        .stats-card .card-change.neutral {
            color: var(--gray);
        }
        
        /* Cores específicas para cada card */
        .card-products {
            border-left: 4px solid var(--primary);
        }
        
        .card-products .card-icon {
            color: var(--primary);
        }
        
        .card-users {
            border-left: 4px solid var(--success);
        }
        
        .card-users .card-icon {
            color: var(--success);
        }
        
        .card-orders {
            border-left: 4px solid var(--warning);
        }
        
        .card-orders .card-icon {
            color: var(--warning);
        }
        
        .card-sales {
            border-left: 4px solid var(--danger);
        }
        
        .card-sales .card-icon {
            color: var(--danger);
        }
        
        /* Cards principais */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            background: white;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--light-gray);
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            border-radius: 10px 10px 0 0 !important;
        }
        
        /* Botões */
        .btn-action {
            background-color: var(--primary);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.3s;
        }
        
        .btn-action:hover {
            background-color: #2c68c4;
            color: white;
            transform: translateY(-2px);
        }
        
        /* Gráficos */
        .chart-container {
            position: relative;
            height: 300px;
            padding: 1rem;
        }
        
        /* Status dos pedidos */
        .empty-state {
            text-align: center;
            padding: 2rem 0;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--light-gray);
            margin-bottom: 1rem;
        }
        
        .empty-state p {
            color: var(--gray);
            margin-bottom: 1.5rem;
        }
        
        /* Atividades recentes */
        .activity-item {
            padding: 1rem;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 1rem;
        }
        
        /* Responsividade */
        @media (max-width: 992px) {
            .main-content {
                padding: 1rem;
            }
            
            .stats-card .card-value {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container-fluid px-4">
            <div class="page-header">
                <h1>Dashboard</h1>
                <div class="last-update">Última atualização: <?php echo date('d/m/Y H:i'); ?></div>
            </div>
            
            <div class="row">
                <!-- Card Produtos -->
                <div class="col-xl-3 col-md-6">
                    <div class="stats-card card-products">
                        <div class="card-body">
                            <i class="fas fa-boxes card-icon"></i>
                            <div class="card-title">Produtos</div>
                            <div class="card-value">0</div>
                            <div class="card-change neutral">
                                <i class="fas fa-equals"></i> 70% este mês
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Usuários -->
                <div class="col-xl-3 col-md-6">
                    <div class="stats-card card-users">
                        <div class="card-body">
                            <i class="fas fa-users card-icon"></i>
                            <div class="card-title">Usuários</div>
                            <div class="card-value">8</div>
                            <div class="card-change positive">
                                <i class="fas fa-arrow-up"></i> 12% este mês
                            </div>
                            <div class="text-center mt-3">
                                <a href="../public/cadastro_new_V2.php" class="btn btn-action btn-sm">
                                    <i class="fas fa-user-plus me-1"></i> Novo Usuário
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Pedidos -->
                <div class="col-xl-3 col-md-6">
                    <div class="stats-card card-orders">
                        <div class="card-body">
                            <i class="fas fa-shopping-cart card-icon"></i>
                            <div class="card-title">Pedidos</div>
                            <div class="card-value">0</div>
                            <div class="card-change negative">
                                <i class="fas fa-arrow-down"></i> 0% este mês
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Vendas -->
                <div class="col-xl-3 col-md-6">
                    <div class="stats-card card-sales">
                        <div class="card-body">
                            <i class="fas fa-chart-line card-icon"></i>
                            <div class="card-title">Vendas (R$)</div>
                            <div class="card-value">R$ 0,00</div>
                            <div class="card-change neutral">
                                <i class="fas fa-equals"></i> Sem variação
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos e informações -->
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Vendas Mensais</span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                    Este ano
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Este ano</a></li>
                                    <li><a class="dropdown-item" href="#">Últimos 6 meses</a></li>
                                    <li><a class="dropdown-item" href="#">Últimos 3 meses</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="vendasMensaisChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status dos pedidos -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            Status dos Pedidos
                        </div>
                        <div class="card-body">
                            <div class="empty-state">
                                <i class="fas fa-shopping-bag"></i>
                                <p>Nenhum pedido registrado.</p>
                                <a href="#" class="btn btn-action">
                                    <i class="fas fa-plus me-1"></i> Novo Pedido
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Últimas atividades -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Últimas Atividades
                        </div>
                        <div class="card-body p-0">
                            <div class="activity-item d-flex align-items-center">
                                <div class="activity-icon bg-primary bg-opacity-10 text-primary">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Novo usuário registrado</h6>
                                    <p class="text-muted mb-0">Administrador adicionou um novo usuário</p>
                                    <small class="text-muted">Hoje, 10:45 AM</small>
                                </div>
                            </div>
                            <div class="activity-item d-flex align-items-center">
                                <div class="activity-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Atualização necessária</h6>
                                    <p class="text-muted mb-0">Sistema requer atualização para a versão 2.5</p>
                                    <small class="text-muted">Ontem, 3:30 PM</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script do gráfico -->
    <script>
    const ctx = document.getElementById('vendasMensaisChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(58, 123, 213, 0.8)');
    gradient.addColorStop(1, 'rgba(58, 123, 213, 0.1)');
    
    const vendasChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
            datasets: [{
                label: 'Vendas Mensais (R$)',
                data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], // Valores zerados conforme seu dashboard
                backgroundColor: gradient,
                borderColor: 'rgba(58, 123, 213, 1)',
                borderWidth: 0,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#2c3e50',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'R$ ' + context.raw.toFixed(2).replace('.', ',');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value;
                        }
                    }
                }
            }
        }
    });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>