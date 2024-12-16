-- Verificar si la tabla existe
SELECT COUNT(*) 
FROM information_schema.tables 
WHERE table_schema = 'totmaquina' 
AND table_name = 'Maintenance';

-- Ver la estructura actual
SHOW CREATE TABLE Maintenance; 