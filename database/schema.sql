CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    full_name VARCHAR(100) NOT NULL,

    email VARCHAR(150) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    role_id INT NOT NULL,

    status BOOLEAN NOT NULL DEFAULT TRUE,

    last_login DATETIME NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_users_role
        FOREIGN KEY (role_id)
        REFERENCES roles(id)
);

CREATE TABLE sections (
    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(20) NOT NULL UNIQUE,

    active BOOLEAN NOT NULL DEFAULT TRUE,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL UNIQUE,

    active BOOLEAN NOT NULL DEFAULT TRUE,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE productions (
    id INT AUTO_INCREMENT PRIMARY KEY,

    production_date DATE NOT NULL,

    product_id INT NOT NULL,

    raw_materials TEXT NOT NULL,

    processed_by INT NOT NULL,

    section_id INT NOT NULL,

    quantity DECIMAL(10,2) NOT NULL,

    unit VARCHAR(50) NOT NULL,

    custom_unit VARCHAR(50) NULL,

    created_by INT NOT NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    updated_at DATETIME NULL DEFAULT NULL,

    deleted_at DATETIME NULL DEFAULT NULL,

    CONSTRAINT fk_productions_product
        FOREIGN KEY (product_id)
        REFERENCES products(id),

    CONSTRAINT fk_productions_processed_by
        FOREIGN KEY (processed_by)
        REFERENCES users(id),

    CONSTRAINT fk_productions_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id),

    CONSTRAINT fk_productions_section
        FOREIGN KEY (section_id)
        REFERENCES sections(id)
);

CREATE TABLE password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    token VARCHAR(255) NOT NULL,

    expires_at DATETIME NOT NULL,

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_password_resets_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
);