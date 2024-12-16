ALTER TABLE Incident
ADD COLUMN resolution_date DATETIME NULL
COMMENT 'Fecha de resolución de la incidencia'
AFTER registered_date; 