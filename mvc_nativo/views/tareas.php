<?php
// Aseguramos que la sesión esté activa para leer los datos del usuario logueado
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Tareas - DataAudit Labs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand font-weight-bold text-primary" href="#">DataAudit Labs</a>
            <div class="d-flex align-items-center">
                <span class="navbar-text text-white me-3">
                    Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></strong>
                </span>
                <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row g-4">
            
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3 text-secondary">Nueva Tarea</h5>
                        
                        <form action="index.php?action=crear_tarea" method="POST">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" name="titulo" id="titulo" class="form-control" required placeholder="Ej: Auditar logs de acceso">
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="Detalles de la actividad..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Guardar Actividad</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4 text-secondary">Listado de Actividades</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tarea</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($listadoTareas)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">No tienes tareas registradas actualmente.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($listadoTareas as $t): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($t['titulo']); ?></strong></td>
                                                <td class="text-muted"><?php echo htmlspecialchars($t['descripcion'] ?: 'Sin descripción'); ?></td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input switch-estado" type="checkbox" 
                                                               id="tarea_<?php echo $t['id']; ?>" 
                                                               data-id="<?php echo $t['id']; ?>"
                                                               <?php echo $t['estado'] == 'completada' ? 'checked' : ''; ?>>
                                                        <label class="form-check-label small" for="tarea_<?php echo $t['id']; ?>" id="label_<?php echo $t['id']; ?>">
                                                            <?php echo ucfirst($t['estado']); ?>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="index.php?action=eliminar_tarea&id=<?php echo $t['id']; ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('¿Seguro de eliminar esta actividad?');">
                                                       Eliminar
                                                   </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Escuchamos el evento de cambio en todos los interruptores de estado
        document.querySelectorAll('.switch-estado').forEach(function(element) {
            element.addEventListener('change', function() {
                const tareaId = this.getAttribute('data-id');
                const nuevoEstado = this.checked ? 'completada' : 'pendiente';
                const labelText = document.getElementById('label_' + tareaId);

                // Creamos los parámetros para enviarle al backend
                const formData = new FormData();
                formData.append('id', tareaId);
                formData.append('estado', nuevoEstado);

                // Petición AJAX asíncrona mediante fetch()
                fetch('index.php?action=actualizar_estado_ajax', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Modificamos el texto visual al instante sin refrescar la pantalla
                        labelText.textContent = nuevoEstado.charAt(0).toUpperCase() + nuevoEstado.slice(1);
                        console.log("Estado actualizado con éxito mediante AJAX.");
                    } else {
                        alert("Error al actualizar: " + data.message);
                        this.checked = !this.checked; // Revertir el switch si falla
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Ocurrió un error de comunicación.");
                    this.checked = !this.checked; // Revertir el switch si falla
                });
            });
        });
    });
    </script>
</body>
</html>