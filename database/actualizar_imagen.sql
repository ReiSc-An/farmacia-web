-- Ejecutar UNA sola vez si ya tenías la base 'farmacia' creada
USE farmacia;
ALTER TABLE productos ADD COLUMN imagen VARCHAR(255) NULL;
