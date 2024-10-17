<?php
require 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
    $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : null;
    $genero = isset($_POST['genero']) ? $_POST['genero'] : null;
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : null;
    $anos_cumplidos = isset($_POST['anos_cumplidos']) ? $_POST['anos_cumplidos'] : null;
    $cui = isset($_POST['cui']) ? $_POST['cui'] : null;
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : null;

    $nombre_padre = isset($_POST['nombre_padre']) ? $_POST['nombre_padre'] : null;
    $telefono_padre = isset($_POST['telefono_padre']) ? $_POST['telefono_padre'] : null;
    $email_padre = isset($_POST['email_padre']) ? $_POST['email_padre'] : null;

    $nombre_madre = isset($_POST['nombre_madre']) ? $_POST['nombre_madre'] : null;
    $telefono_madre = isset($_POST['telefono_madre']) ? $_POST['telefono_madre'] : null;
    $email_madre = isset($_POST['email_madre']) ? $_POST['email_madre'] : null;

    try {
        // Obtener el último código correlativo
        $sql_last_code = "SELECT codigo_usuario FROM usuarios ORDER BY id DESC LIMIT 1";
        $stmt_last_code = $conn->prepare($sql_last_code);
        $stmt_last_code->execute();
        $last_code = $stmt_last_code->fetch(PDO::FETCH_ASSOC);

        // Generar nuevo código correlativo
        if ($last_code) {
            $last_number = intval(substr($last_code['codigo_usuario'], 4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }

        $codigo_usuario = 'OBS-' . str_pad($new_number, 5, '0', STR_PAD_LEFT);
        $fecha_registro = date('Y-m-d');

        // Insertar datos
        $sql = "INSERT INTO usuarios (codigo_usuario, nombre, apellidos, genero, fecha_nacimiento, anos_cumplidos, cui, telefono, email, direccion, nombre_padre, telefono_padre, email_padre, nombre_madre, telefono_madre, email_madre) 
                VALUES (:codigo_usuario, :nombre, :apellidos, :genero, :fecha_nacimiento, :anos_cumplidos, :cui, :telefono, :email, :direccion, :nombre_padre, :telefono_padre, :email_padre, :nombre_madre, :telefono_madre, :email_madre)";
        
        $stmt = $conn->prepare($sql);

        // Enlazar parámetros
        $stmt->bindParam(':codigo_usuario', $codigo_usuario);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos', $apellidos);
        $stmt->bindParam(':genero', $genero);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':anos_cumplidos', $anos_cumplidos);
        $stmt->bindParam(':cui', $cui);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':nombre_padre', $nombre_padre);
        $stmt->bindParam(':telefono_padre', $telefono_padre);
        $stmt->bindParam(':email_padre', $email_padre);
        $stmt->bindParam(':nombre_madre', $nombre_madre);
        $stmt->bindParam(':telefono_madre', $telefono_madre);
        $stmt->bindParam(':email_madre', $email_madre);

        if ($stmt->execute()) {

            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('codigo_usuario').innerText = '$codigo_usuario';
                        document.getElementById('nombre_usuario').innerText = '$nombre $apellidos';
                        document.getElementById('fecha_registro').innerText = '$fecha_registro';
                        var modal = new bootstrap.Modal(document.getElementById('successModal'));
                        modal.show();
                    });
                  </script>";
        } else {
            echo "Error al guardar los datos.";
        }

    } catch (PDOException $e) {
        echo "Error al guardar los datos: " . $e->getMessage();
    }
}
?>


<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Registro Exitoso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Los datos del usuario han sido guardados exitosamente.</p>
        <p><strong>Código del Usuario:</strong> <span id="codigo_usuario"></span></p>
        <p><strong>Nombre del Usuario:</strong> <span id="nombre_usuario"></span></p>
        <p><strong>Fecha de Registro:</strong> <span id="fecha_registro"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="descargarPDF()">Descargar PDF</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
function descargarPDF() {
    var { jsPDF } = window.jspdf;
    var doc = new jsPDF();

    var codigoUsuario = document.getElementById('codigo_usuario').innerText;
    var nombreUsuario = document.getElementById('nombre_usuario').innerText;
    var fechaRegistro = document.getElementById('fecha_registro').innerText;

    doc.text('Datos del Estudiante', 20, 20);
    doc.text('Código del Usuario: ' + codigoUsuario, 20, 30);
    doc.text('Nombre del Usuario: ' + nombreUsuario, 20, 40);
    doc.text('Fecha de Registro: ' + fechaRegistro, 20, 50);

    doc.save('datos_estudiante_' + codigoUsuario + '.pdf');


    setTimeout(function(){
        window.location.href = 'index.html';
    }, 1000); 
}


document.getElementById('successModal').addEventListener('hidden.bs.modal', function () {
    window.location.href = 'index.html';
});
</script>






<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
