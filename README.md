Pagos Nequi con Notificación Push (PHP)
Este proyecto proporciona una implementación base en PHP para integrar cobros mediante Notificaciones Push de Nequi. Permite a los comercios solicitar pagos directamente al celular del cliente, consultar el estado de la transacción, cancelarla o revertirla si es necesario.

Descripción General
El sistema utiliza el flujo de pagos no registrados (Unregistered Payment) de Nequi. El comercio inicia la solicitud, lo que dispara una notificación push en la aplicación móvil del usuario para que este autorice el movimiento con su clave o biometría.

Propósito del Sistema
Facilitar una estructura técnica profesional y organizada para desarrolladores que necesiten implementar la API de Nequi en aplicaciones basadas en PHP.

Alcance del Flujo
Autenticación: Generación automática de tokens OAuth2.

Registro de Pago: Envío de solicitud de cobro al cliente.

Seguimiento: Consulta de estados en tiempo real.

Gestión de Excepciones: Funcionalidades para cancelar solicitudes pendientes o revertir transacciones completadas.

Requisitos Previos
PHP: Versión 7.4 o superior (Compatible con PHP 8.x).

Composer: Para la gestión de dependencias.

Credenciales de Nequi: Debes contactar a Conecta Nequi para obtener las llaves de acceso (API Key y Auth Básica).

Tecnologías Utilizadas
vlucas/phpdotenv: Para la carga segura de variables de entorno.

cURL: Para la ejecución de peticiones HTTP a los servicios de Nequi.

Instalación
Clonar el repositorio:

Bash
git clone https://github.com/tu-usuario/pagos-nequi-push-php.git
cd pagos-nequi-push-php
Instalar dependencias:

Bash
composer install
Configurar el entorno:
Crea un archivo .env en la raíz del proyecto (el sistema ya incluye un .gitignore para protegerlo).

Configuración (Variables de Entorno)
Define las siguientes variables en tu archivo .env para que la clase ClienteNequi pueda cargarlas:

Fragmento de código
NEQUI_AUTH_BASICA=Tu_Token_Basico
NEQUI_API_KEY=Tu_Api_Key
URL_AUTH_NEQUI=https://api.nequi.com/auth/token
URL_API_NEQUI=https://api.nequi.com/
NEQUI_NIT_NEGOCIO=Nit_De_Tu_Comercio

Estructura del Proyecto
src/Configuracion/ClienteNequi.php: Gestiona la autenticación, generación de encabezados y ejecución de peticiones cURL.

src/Servicios/ServicioNequi.php: Contiene la lógica de negocio para registrar, consultar, cancelar y revertir pagos.

public_html/index.php: Ejemplo práctico de implementación del flujo.

.env: Archivo de configuración de credenciales (debe crearse manualmente).

Flujo de Pagos Detallado
Para un control correcto de la transacción, es obligatorio gestionar los siguientes identificadores:

Registrar Pago: Al ejecutar registrarPago(), se genera un MessageID único en el encabezado y Nequi devuelve un transactionId. Debes guardar ambos en tu base de datos.

Consultar Estado: Utiliza el transactionId para verificar si el usuario aprobó el pago.

Cancelar Pago: Si el usuario no responde, usa el phoneNumber y el transactionId para anular la solicitud.

Revertir Pago: Si necesitas devolver el dinero, usa el phoneNumber, el monto y el MessageID original de la transacción.

Ejemplo de Uso (index.php)
PHP
require_once __DIR__ . '/../vendor/autoload.php';

use App\Servicios\ServicioNequi;

$servicio = new ServicioNequi();

// Datos del cliente y pago
$telefono = "3001234567";
$valor = "5000";
$referencia = "ORDEN-101";

// Iniciar el cobro (Push Notification)
$respuesta = $servicio->registrarPago($telefono, $valor, $referencia);

echo "<pre>";
print_r($respuesta);
echo "</pre>";
Respuestas Simuladas
Éxito (Pendiente de aprobación): Devuelve un code: "0" y el transactionId.

Error: Devuelve códigos específicos de Nequi si el número no existe o el monto es inválido.

Pruebas y Buenas Prácticas
Pruebas Manuales: Utiliza el archivo index.php para enviar notificaciones push a números de prueba autorizados en el sandbox de Nequi.

Idempotencia: El sistema usa uniqid() para generar el MessageID Pero se debe utilizar otro método para garantizar que no se repita en ningún momento.

Seguridad: Nunca expongas las variables del archivo .env en archivos públicos.