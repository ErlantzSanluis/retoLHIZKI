<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../model/AccesoBD.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Irakasleen Panela - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 30px;
        }

        .header-section {
            background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%);
            border-radius: 16px;
            padding: 40px 50px;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.2);
        }

        .header-section h1 {
            color: white;
            font-size: 2rem;
            font-weight: 600;
            margin: 0 0 8px 0;
        }

        .header-section p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 1.1rem;
        }

        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 35px 30px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .stats-card.blue {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        }

        .stats-card.purple {
            background: linear-gradient(135deg, #e9d5ff 0%, #d8b4fe 100%);
        }

        .stats-card.green {
            background: linear-gradient(135deg, #ccfbf1 0%, #99f6e4 100%);
        }

        .stats-card.yellow {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        }

        .stats-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .stats-card.blue i {
            color: #2563eb;
        }

        .stats-card.purple i {
            color: #9333ea;
        }

        .stats-card.green i {
            color: #059669;
        }

        .stats-card.yellow i {
            color: #d97706;
        }

        .stats-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 10px 0 5px 0;
            color: #1f2937;
        }

        .stats-card .label {
            font-size: 1rem;
            color: #6b7280;
            font-weight: 500;
        }

        .content-section {
            background: white;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #6b7280;
        }

        .progress-item {
            margin-bottom: 25px;
        }

        .progress-item:last-child {
            margin-bottom: 0;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .progress-label .name {
            font-weight: 600;
            color: #374151;
        }

        .progress-label .percentage {
            font-weight: 700;
            color: #1f2937;
        }

        .progress {
            height: 14px;
            border-radius: 10px;
            background-color: #f3f4f6;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        .progress-bar.teal {
            background: linear-gradient(90deg, #14b8a6 0%, #0d9488 100%);
        }

        .progress-bar.blue {
            background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%);
        }

        .progress-bar.purple {
            background: linear-gradient(90deg, #a855f7 0%, #9333ea 100%);
        }

        .chart-container {
            padding: 30px 20px;
            min-height: 350px;
        }

        .chart-wrapper {
            position: relative;
            height: 280px;
        }

        .chart-line {
            position: absolute;
            bottom: 60px;
            left: 40px;
            right: 40px;
            height: 180px;
        }

        .chart-line svg {
            width: 100%;
            height: 100%;
        }

        .chart-labels {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            padding: 0 40px;
        }

        .chart-label {
            font-size: 0.95rem;
            color: #6b7280;
            font-weight: 500;
        }

        .chart-point {
            fill: #14b8a6;
            transition: r 0.2s;
        }

        .chart-point:hover {
            r: 8;
            fill: #0d9488;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <div class="header-section">
            <h1>Irakasleen Panela</h1>
            <p>Ikasleen jarraitzea</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card blue">
                    <i class="bi bi-people-fill"></i>
                    <div class="number">45</div>
                    <div class="label">Ikasleek</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card purple">
                    <i class="bi bi-bar-chart-fill"></i>
                    <div class="number">78%</div>
                    <div class="label">Parted.</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card green">
                    <i class="bi bi-graph-up-arrow"></i>
                    <div class="number">85%</div>
                    <div class="label">Batazb.</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card yellow">
                    <i class="bi bi-check-circle-fill"></i>
                    <div class="number">35</div>
                    <div class="label">Osatuta</div>
                </div>
            </div>
        </div>

        <!-- Progress Section -->
        <div class="content-section">
            <div class="section-title">
                <i class="bi bi-bar-chart-line"></i>
                Partaidetza taldeka
            </div>
            <div class="progress-item">
                <div class="progress-label">
                    <span class="name">DAM 1A</span>
                    <span class="percentage">92%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar teal" role="progressbar" style="width: 92%" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="progress-item">
                <div class="progress-label">
                    <span class="name">DAM 1B</span>
                    <span class="percentage">85%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar blue" role="progressbar" style="width: 85%" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="progress-item">
                <div class="progress-label">
                    <span class="name">DAM 2A</span>
                    <span class="percentage">78%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar purple" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="content-section">
            <div class="section-title">
                <i class="bi bi-graph-up"></i>
                Asteko bilakera
            </div>
            <div class="chart-container">
                <div class="chart-wrapper">
                    <div class="chart-line">
                        <svg viewBox="0 0 700 180" preserveAspectRatio="none">
                            <polyline
                                fill="none"
                                stroke="#14b8a6"
                                stroke-width="3"
                                points="0,140 100,100 200,110 300,60 400,70 500,50 600,80 700,40"
                            />
                            <circle class="chart-point" cx="0" cy="140" r="6" />
                            <circle class="chart-point" cx="100" cy="100" r="6" />
                            <circle class="chart-point" cx="200" cy="110" r="6" />
                            <circle class="chart-point" cx="300" cy="60" r="6" />
                            <circle class="chart-point" cx="400" cy="70" r="6" />
                            <circle class="chart-point" cx="500" cy="50" r="6" />
                            <circle class="chart-point" cx="600" cy="80" r="6" />
                            <circle class="chart-point" cx="700" cy="40" r="6" />
                        </svg>
                    </div>
                </div>
                <div class="chart-labels">
                    <span class="chart-label">Al</span>
                    <span class="chart-label">As</span>
                    <span class="chart-label">Az</span>
                    <span class="chart-label">Og</span>
                    <span class="chart-label">Or</span>
                    <span class="chart-label">Lr</span>
                    <span class="chart-label">Ig</span>
                </div>
            </div>
        </div>
        <!-- Lista de alumnos -->
        <div class="content-section">
            <div class="section-title">
                <i class="bi bi-people"></i>
                Zure ikasleak
            </div>
            <?php
            $id_centro = $_SESSION['id_centro'] ?? null;
            $alumnos = [];
            if ($id_centro) {
                $bd = new AccesoBD();
                $alumnos = $bd->obtenerAlumnosPorCentro($id_centro);
            } else {
                echo "<div class='alert alert-warning'>Ez da aurkitu zure zentroa.</div>";
            }
            ?>
            <?php if (count($alumnos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Izena</th>
                                <th>Abizena</th>
                                <th>Emaila</th>
                                <th>Akzioak</th> <!-- Nueva columna para acciones -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alumnos as $alumno): ?>
                                <tr>
                                    <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                                    <td><?= htmlspecialchars($alumno['apellidos']) ?></td>
                                    <td><?= htmlspecialchars($alumno['email']) ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Aukerak
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editarModal<?= md5($alumno['email']) ?>">
                                                        <i class="bi bi-pencil"></i> Editatu
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal<?= md5($alumno['email']) ?>">
                                                        <i class="bi bi-trash"></i> Ezabatu
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- Modal Editar -->
                                        <div class="modal fade" id="editarModal<?= md5($alumno['email']) ?>" tabindex="-1" aria-labelledby="editarLabel<?= md5($alumno['email']) ?>" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <div class="modal-content">
                                              <form method="post" action="">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="editarLabel<?= md5($alumno['email']) ?>">Editatu erabiltzailea</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  <input type="hidden" name="edit_email" value="<?= htmlspecialchars($alumno['email']) ?>">
                                                  <div class="mb-3">
                                                    <label class="form-label">Izena</label>
                                                    <input type="text" class="form-control" name="edit_nombre" value="<?= htmlspecialchars($alumno['nombre']) ?>" required>
                                                  </div>
                                                  <div class="mb-3">
                                                    <label class="form-label">Abizena</label>
                                                    <input type="text" class="form-control" name="edit_apellidos" value="<?= htmlspecialchars($alumno['apellidos']) ?>" required>
                                                  </div>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Itxi</button>
                                                  <button type="submit" class="btn btn-primary" name="editar_usuario">Gorde</button>
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- Modal Eliminar -->
                                        <div class="modal fade" id="eliminarModal<?= md5($alumno['email']) ?>" tabindex="-1" aria-labelledby="eliminarLabel<?= md5($alumno['email']) ?>" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <div class="modal-content">
                                              <form method="post" action="">
                                                <div class="modal-header">
                                                  <h5 class="modal-title" id="eliminarLabel<?= md5($alumno['email']) ?>">Ezabatu erabiltzailea</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  <input type="hidden" name="delete_email" value="<?= htmlspecialchars($alumno['email']) ?>">
                                                  Ziur zaude erabiltzailea ezabatu nahi duzula?
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Utzi</button>
                                                  <button type="submit" class="btn btn-danger" name="eliminar_usuario">Ezabatu</button>
                                                </div>
                                              </form>
                                            </div>
                                          </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif ($id_centro): ?>
                <div class="alert alert-info">Ez dago ikaslerik zure zentroan.</div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Procesar edición
if (isset($_POST['editar_usuario'])) {
    $edit_email = $_POST['edit_email'] ?? '';
    $edit_nombre = $_POST['edit_nombre'] ?? '';
    $edit_apellidos = $_POST['edit_apellidos'] ?? '';
    if ($edit_email && $edit_nombre && $edit_apellidos) {
        $bd = new AccesoBD();
        $sql = "UPDATE usuario SET nombre = '" . mysqli_real_escape_string($bd->conexion, $edit_nombre) . "', apellidos = '" . mysqli_real_escape_string($bd->conexion, $edit_apellidos) . "' WHERE email = '" . mysqli_real_escape_string($bd->conexion, $edit_email) . "'";
        $bd->lanzarSQL($sql);
        echo '<div class="alert alert-success">Erabiltzailea eguneratuta!</div>';
        // Refrescar la página para ver los cambios
        echo '<meta http-equiv="refresh" content="1">';
    }
}
// Procesar eliminación
if (isset($_POST['eliminar_usuario'])) {
    $delete_email = $_POST['delete_email'] ?? '';
    if ($delete_email) {
        $bd = new AccesoBD();
        $sql = "DELETE FROM usuario WHERE email = '" . mysqli_real_escape_string($bd->conexion, $delete_email) . "'";
        $bd->lanzarSQL($sql);
        echo '<div class="alert alert-danger">Erabiltzailea ezabatuta!</div>';
        // Refrescar la página para ver los cambios
        echo '<meta http-equiv="refresh" content="1">';
    }
}
?>