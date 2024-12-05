CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM(
        'technician',
        'supervisor',
        'administrator'
    ) NOT NULL,
    avatar VARCHAR(255)
);

CREATE TABLE Machine (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    model VARCHAR(100),
    manufacturer VARCHAR(100),
    serial_number VARCHAR(100) UNIQUE,
    installation_date DATE,
    location VARCHAR(255),
    photo VARCHAR(255),
    coordinates VARCHAR(100),
    assigned_technician_id INT,
    FOREIGN KEY (assigned_technician_id) REFERENCES User (id) ON DELETE SET NULL
);

CREATE TABLE Maintenance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scheduled_date DATE NOT NULL,
    frequency ENUM(
        'weekly',
        'monthly',
        'quarterly',
        'yearly'
    ),
    type ENUM('preventive', 'corrective'),
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    machine_id INT,
    description TEXT,
    FOREIGN KEY (machine_id) REFERENCES Machine (id) ON DELETE CASCADE
);

CREATE TABLE MaintenanceTechnician (
    maintenance_id INT,
    technician_id INT,
    PRIMARY KEY (maintenance_id, technician_id),
    FOREIGN KEY (maintenance_id) REFERENCES Maintenance (id) ON DELETE CASCADE,
    FOREIGN KEY (technician_id) REFERENCES User (id) ON DELETE CASCADE
);

CREATE TABLE Incident (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description TEXT NOT NULL,
    priority ENUM('high', 'medium', 'low') DEFAULT 'medium',
    status ENUM(
        'pending',
        'in_progress',
        'resolved'
    ) DEFAULT 'pending',
    registered_date DATE NOT NULL,
    resolved_date DATE,
    responsible_technician_id INT,
    machine_id INT,
    FOREIGN KEY (responsible_technician_id) REFERENCES User (id) ON DELETE SET NULL,
    FOREIGN KEY (machine_id) REFERENCES Machine (id) ON DELETE CASCADE
);

CREATE TABLE Maintenance_History (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    description TEXT,
    time_spent DECIMAL(5, 2),
    maintenance_id INT,
    technician_id INT,
    FOREIGN KEY (maintenance_id) REFERENCES Maintenance (id) ON DELETE CASCADE,
    FOREIGN KEY (technician_id) REFERENCES User (id) ON DELETE SET NULL
);