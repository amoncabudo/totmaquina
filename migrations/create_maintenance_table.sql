-- Primero, eliminar la tabla si existe
DROP TABLE IF EXISTS Maintenance;

-- Luego crear la tabla con la estructura correcta
CREATE TABLE Maintenance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    technician_id INT,
    description TEXT NOT NULL,
    maintenance_date DATETIME NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (machine_id) REFERENCES Machine(id) ON DELETE CASCADE,
    FOREIGN KEY (technician_id) REFERENCES User(id) ON DELETE SET NULL
);

-- Verificar la estructura
DESCRIBE Maintenance; 