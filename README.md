# Pagos Nequi con Notificación Push (PHP)

Este proyecto proporciona una implementación base en PHP para integrar cobros mediante **Notificaciones Push de Nequi**. Permite a los comercios solicitar pagos directamente al celular del cliente, consultar el estado de la transacción, cancelarla o revertirla si es necesario.

## Descripción General

El sistema utiliza el flujo de pagos no registrados (**Unregistered Payment**) de Nequi. El comercio inicia la solicitud, lo que dispara una notificación push en la aplicación móvil del usuario para que este autorice el movimiento.

## Alcance del Flujo

- **Autenticación**: Generación automática de tokens OAuth2.
- **Registro de Pago**: Envío de solicitud de cobro al cliente.
- **Seguimiento**: Consulta de estados en tiempo real.
- **Gestión de Excepciones**: Funcionalidades para cancelar solicitudes pendientes o revertir transacciones completadas.

## Requisitos Previos

- **PHP**: Versión 7.4 o superior (compatible con PHP 8.x).
- **Composer**: Para la gestión de dependencias.
- **Credenciales de Nequi**: Debes contactar a Conecta Nequi para obtener las llaves de acceso.

## Tecnologías Utilizadas

- **vlucas/phpdotenv**: Para la carga segura de variables de entorno.
- **cURL**: Para la ejecución de peticiones HTTP a los servicios de Nequi.

## Instalación

### Clonar el repositorio

```bash
git clone https://github.com/soyalexandercl/pagos-nequi-con-notificacion-push-php.git
cd pagos-nequi-push-php
```

### Instalar dependencias

```bash
composer install
```

### Configurar el entorno

Crea un archivo `.env` en la raíz del proyecto

## Configuración (Variables de Entorno)

Define las siguientes variables en el archivo `.env` para que la clase `ClienteNequi` pueda cargarlas:

```env
NEQUI_AUTH_BASICA=Token
NEQUI_API_KEY=Api_Key
URL_AUTH_NEQUI=https://api.nequi.com/auth/token
URL_API_NEQUI=https://api.nequi.com/
NEQUI_NIT_NEGOCIO=Nit_De_Tu_Comercio
```

## Estructura del Proyecto

- `src/Configuracion/ClienteNequi.php`: Gestiona la autenticación, generación de encabezados y ejecución de peticiones cURL.
- `src/Servicios/ServicioNequi.php`: Contiene la lógica de negocio para registrar, consultar, cancelar y revertir pagos.
- `public_html/index.php`: Ejemplo práctico de implementación del flujo.
- `.env`: Archivo de configuración de credenciales (debe crearse manualmente).

## Flujo de Pagos Detallado

Para un control correcto de la transacción, es obligatorio gestionar los siguientes identificadores:

- **Registrar Pago**:  
  Al ejecutar `registrarPago()`, se genera un `MessageID` único en el encabezado y Nequi devuelve un `transactionId`. Debes guardar ambos en tu base de datos.

- **Consultar Estado**:  
  Utiliza el `transactionId` para verificar si el usuario aprobó el pago.

- **Cancelar Pago**:  
  Si el usuario no responde, usa el `phoneNumber` y el `transactionId` para anular la solicitud.

- **Revertir Pago**:  
  Si necesitas devolver el dinero, usa el `phoneNumber`, el monto y el `MessageID` original de la transacción.

## Pruebas

- **Pruebas Manuales**: Utiliza el archivo `index.php` para enviar notificaciones push a números de prueba autorizados en el sandbox de Nequi.
- **Idempotencia**: El sistema usa `uniqid()` para generar el `MessageID`, pero se debe utilizar otro método que garantice que no se repita en ningún momento.