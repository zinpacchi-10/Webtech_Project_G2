<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Housekeeping Dashboard - Hotel Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateX(5px);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .card-stats {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .card-stats:hover {
            transform: translateY(-3px);
        }
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-available { background: #d4edda; color: #155724; }
        .status-occupied { background: #fff3cd; color: #856404; }
        .status-dirty { background: #f8d7da; color: #721c24; }
        .status-cleaning { background: #cce5ff; color: #004085; }
        .status-maintenance { background: #e2e3e5; color: #383d41; }
        .priority-emergency { background: #dc3545; color: white; }
        .priority-urgent { background: #fd7e14; color: white; }
        .priority-normal { background: #28a745; color: white; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3">
                    <h4 class="text-center mb-4">🏨 Hotel HMS</h4>
                    <hr class="bg-light">
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="index.php?controller=housekeeping&action=dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a class="nav-link" href="index.php?controller=housekeeping&action=roomStatus">
                        <i class="fas fa-bed"></i> Room Status
                    </a>
                    <a class="nav-link" href="index.php?controller=housekeeping&action=createTask">
                        <i class="fas fa-plus-circle"></i> Create Task
                    </a>
                    <a class="nav-link" href="index.php?controller=housekeeping&action=manageTasks">
                        <i class="fas fa-tasks"></i> Manage Tasks
                    </a>
                    <a class="nav-link" href="index.php?controller=housekeeping&action=maintenance">
                        <i class="fas fa-tools"></i> Maintenance
                    </a>
                    <a class="nav-link" href="index.php?controller=housekeeping&action=checkouts">
                        <i class="fas fa-sign-out-alt"></i> Upcoming Check-outs
                    </a>
                    <a class="nav-link" href="index.php?controller=housekeeping&action=checkins">
                        <i class="fas fa-sign-in-alt"></i> Upcoming Check-ins
                    </a>
                    <a class="nav-link" href="index.php?controller=housekeeping&action=report">
                        <i class="fas fa-chart-line"></i> Daily Report
                    </a>
                    <a class="nav-link" href="index.php?controller=auth&action=profile">
                        <i class="fas fa-user-circle"></i> My Profile
                    </a>
                    <a class="nav-link text-danger" href="index.php?controller=auth&action=logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <span class="navbar-brand">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Housekeeping'); ?></span>
                        <div class="ms-auto">
                            <span class="badge bg-success p-2">
                                <i class="fas fa-clock"></i> 
                                <?php echo date('l, F j, Y'); ?>
                            </span>
                        </div>
                    </div>
                </nav>
                
                <div class="p-4">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Operation completed successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/housekeeping.js"></script>
</body>
</html>