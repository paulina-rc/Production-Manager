-- Reemplaza la recuperacion de contraseña por correo con restablecimiento por el administrador

ALTER TABLE users
    ADD COLUMN must_change_password TINYINT(1) NOT NULL DEFAULT 0;

DROP TABLE password_resets;
