<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
$env = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$env->load();

use App\Servicios\ServicioNequi;
$servicio = new ServicioNequi();

$resultado = null;
$accionRealizada = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    try {
        switch ($accion) {
            case 'registrar':
                $accionRealizada = "Registro de Pago";
                $resultado = $servicio->registrarPago($_POST['telefono'], $_POST['monto'], $_POST['referencia']);
                break;
            case 'consultar':
                $accionRealizada = "Consulta de Estado";
                $resultado = $servicio->consultarPago($_POST['id_transaccion']);
                break;
            case 'cancelar':
                $accionRealizada = "Cancelación de Pago";
                $resultado = $servicio->cancelarPago($_POST['telefono'], $_POST['id_transaccion']);
                break;
            case 'revertir':
                $accionRealizada = "Reversión de Pago";
                $resultado = $servicio->revertirPago($_POST['telefono'], $_POST['monto'], $_POST['id_mensaje']);
                break;
        }
    } catch (\Exception $e) {
        $resultado = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Pagos Nequi</title>
</head>
<body>
    <h1>Gestión de Pagos Nequi</h1>

    <fieldset>
        <legend>Registrar Nuevo Pago</legend>
        <form method="POST">
            <input type="hidden" name="accion" value="registrar">
            <input type="text" name="telefono" placeholder="Número Celular" required>
            <input type="number" name="monto" placeholder="Monto" required>
            <input type="text" name="referencia" placeholder="Referencia (opcional)">
            <button type="submit">Enviar Notificación Push</button>
        </form>
    </fieldset>

    <br>

    <fieldset>
        <legend>Consultar Estado de Pago</legend>
        <form method="POST">
            <input type="hidden" name="accion" value="consultar">
            <input type="text" name="id_transaccion" placeholder="ID de Transacción" required>
            <button type="submit">Consultar Estado</button>
        </form>
    </fieldset>

    <br>

    <fieldset>
        <legend>Cancelar Pago</legend>
        <form method="POST">
            <input type="hidden" name="accion" value="cancelar">
            <input type="text" name="telefono" placeholder="Número Celular" required>
            <input type="text" name="id_transaccion" placeholder="ID de Transacción" required>
            <button type="submit">Cancelar Pago</button>
        </form>
    </fieldset>

    <br>

    <fieldset>
        <legend>Revertir Pago</legend>
        <form method="POST">
            <input type="hidden" name="accion" value="revertir">
            <input type="text" name="telefono" placeholder="Número Celular" required>
            <input type="number" name="monto" placeholder="Monto Original" required>
            <input type="text" name="id_mensaje" placeholder="MessageID Original" required>
            <button type="submit">Revertir Pago</button>
        </form>
    </fieldset>

    <hr>

    <?php if ($resultado): ?>
        <h3>Resultado de: <?php echo $accionRealizada; ?></h3>
        <pre style="background: #f4f4f4; padding: 10px; border: 1px solid #ccc;">
            <?php print_r($resultado); ?>
        </pre>
    <?php endif; ?>

</body>
</html>