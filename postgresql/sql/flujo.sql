-- GET: consultas base
SELECT id, titulo, estado, fecha_limite
FROM tareas
ORDER BY fecha_creacion DESC
LIMIT 10 OFFSET 0;

SELECT id, titulo, estado
FROM tareas
WHERE usuario_id = 5;

SELECT id, titulo
FROM tareas
WHERE titulo ILIKE '%daily%';

-- POST: crear usuario y tarea
INSERT INTO usuarios (nombre, email, password, roles)
VALUES ('Ángel García', 'angelgmg7@gmail.com', 'temporal', '["ROLE_USER"]')
RETURNING id, nombre, email, fecha_registro;
-- ID = 4

INSERT INTO tareas (titulo, descripcion, estado, usuario_id, fecha_limite)
VALUES ('Sesión daily', 'Revisión inicial del plan de trabajo', 'pendiente', 5, CURRENT_DATE + INTERVAL '4 days')
RETURNING id, titulo, usuario_id;
-- ID = 5

-- PATCH: actualizar solo campos concretos
UPDATE tareas
SET estado = 'en_progreso'
WHERE id = 6
RETURNING id, titulo, estado;

UPDATE tareas
SET fecha_limite = CURRENT_DATE + INTERVAL '6 days'
WHERE id = 6
RETURNING id, titulo, fecha_limite;

-- PUT: actualizar información principal del usuario
UPDATE usuarios
SET nombre = 'Ángel García (Team Lead)',
    email = 'angelgmg7@gmail.com',
    roles = '["ROLE_USER","ROLE_MANAGER"]'
WHERE id = 5
RETURNING id, nombre, email, roles;

-- DELETE: eliminar una tarea
DELETE FROM tareas
WHERE id = 6
RETURNING id, titulo;

-- GET final para comprobar resultados
SELECT *
FROM vw_tareas_con_propietario
WHERE usuario_id = 5;
