SELECT id, titulo, estado, fecha_limite
FROM tareas
WHERE titulo ILIKE '%presentación%' 
ORDER BY fecha_creacion DESC
