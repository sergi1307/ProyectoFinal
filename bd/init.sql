-- Active: 1769586985650@@127.0.0.1@3306@mysql
CREATE DATABASE IF NOT EXISTS nexus;
USE nexus;

-- 1. Usuarios
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Categorías
CREATE TABLE categories (
    id_category INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

-- 3. Productos (Sin category_id, ahora va por tabla pivote)
CREATE TABLE products (
    id_product INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    file_url VARCHAR(255) NOT NULL,
    image_url VARCHAR(255),
    active BOOLEAN DEFAULT TRUE,
    user_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id_user)
);

-- 3.1 Tabla Pivote Categoría-Producto
CREATE TABLE category_product (
    category_id INT,
    product_id INT,
    PRIMARY KEY (category_id, product_id),
    FOREIGN KEY (category_id) REFERENCES categories(id_category) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id_product) ON DELETE CASCADE
);

-- 4. Carritos
CREATE TABLE carts (
    id_cart INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id_user)
);

-- 5. Items del Carrito
CREATE TABLE cart_items (
    id_cart_item INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT,
    product_id INT,
    added_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id_cart) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id_product) ON DELETE CASCADE
);

-- 6. Pedidos
CREATE TABLE orders (
    id_order INT AUTO_INCREMENT PRIMARY KEY,
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    user_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id_user)
);

-- 7. Detalles del Pedido
CREATE TABLE details (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_order INT,
    id_product INT,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_order) REFERENCES orders(id_order) ON DELETE CASCADE,
    FOREIGN KEY (id_product) REFERENCES products(id_product)
);

-- 8. Reseñas
CREATE TABLE reviews (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    rating TINYINT NOT NULL,
    comment TEXT,
    user_id INT,
    product_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id_user),
    FOREIGN KEY (product_id) REFERENCES products(id_product)
);

-- 9. Favoritos
CREATE TABLE favorites (
    user_id INT,
    product_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id_product) ON DELETE CASCADE
);