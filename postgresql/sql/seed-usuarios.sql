INSERT INTO
    usuarios (nombre, email, password, roles)
VALUES
    (
        'Ana Admin',
        'ana.admin@example.com',
        'changeme',
        '["ROLE_ADMIN"]'
    ),
    (
        'Luis Usuario',
        'luis.user@example.com',
        'changeme',
        '["ROLE_USER"]'
    ),
    (
        'María Lider',
        'maria.user@example.com',
        'changeme',
        '["ROLE_USER","ROLE_MANAGER"]'
    );