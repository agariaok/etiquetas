
Requisitos
- PHP 7.1.9
- PDO + driver (pdo_mysql o sqlite)
- Servidor apuntando a la carpeta public/

Instalación
1) Crear la base 'clients_app' en MySQL y ejecutar database/schema.sql
2) Editar credenciales en app/Config/config.php
3) Iniciar servidor local:
   php -S localhost:8000 -t public
4) Abrir:
   http://localhost:8000/index.php?action=index

Funciones
- Listado con filtros (q, localidad, cp, transporte)
- Crear, Editar, Eliminar clientes
- Generar Etiqueta de envío (REMITENTE fijo + DESTINATARIO del cliente)
