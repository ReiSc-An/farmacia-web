# Farmacia Salud (tienda web)

Tienda web de una farmacia hecha con **PHP + MySQL**: catálogo, buscador, carrito, compras y contacto.

## Estructura

```
farmacia-web/
├── config/
│   ├── database.example.php   # plantilla de conexión (se sube a GitHub)
│   └── database.php           # tus datos reales (ignorado por git)
├── database/
│   └── schema.sql             # tablas + datos de ejemplo
├── includes/                  # código compartido
│   ├── funciones.php          # conexión, sesión y carrito
│   ├── header.php / footer.php
│   └── tarjeta_producto.php
└── public/                    # páginas visibles
    ├── index.php              # inicio
    ├── catalogo.php           # catálogo y búsqueda
    ├── carrito.php            # carrito de compras
    ├── checkout.php           # datos de entrega y compra
    ├── pedido_confirmado.php
    ├── contacto.php
    └── assets/css/style.css
```

## Cómo ejecutarlo

1. Instala XAMPP y enciende Apache y MySQL.
2. En phpMyAdmin, importa `database/schema.sql`.
3. Copia `config/database.example.php` como `config/database.php` y ajusta usuario y contraseña.
4. Pon la carpeta en `C:\xampp\htdocs\` y abre `http://localhost/farmacia-web/public/`.

## Panel de administrador

Entra a `/public/admin/` (usuario y clave en `config/database.php`). Desde ahí agregas, editas y eliminas productos y subes su imagen.
Si ya tenías la base creada, ejecuta una vez `database/actualizar_imagen.sql`.

## Base de datos

`categorias` → `productos` · `pedidos` → `detalle_pedido` → `productos` · `mensajes`

## Subir a GitHub

```
git init
git add .
git commit -m "Tienda web de farmacia"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/farmacia-web.git
git push -u origin main
```
