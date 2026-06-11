CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE raw_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE productions (
    id INT AUTO_INCREMENT PRIMARY KEY,

    production_date DATE NOT NULL,

    product_id INT NOT NULL,

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

CREATE TABLE production_raw_materials (
    production_id INT NOT NULL,
    raw_material_id INT NOT NULL,

    PRIMARY KEY (production_id, raw_material_id),

    CONSTRAINT fk_prm_production
        FOREIGN KEY (production_id)
        REFERENCES productions(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_prm_raw_material
        FOREIGN KEY (raw_material_id)
        REFERENCES raw_materials(id)
);