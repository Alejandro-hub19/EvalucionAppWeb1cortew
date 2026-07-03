INSERT INTO usuarios (username, password_hash, rol)
VALUES (
    'admin',
    '$2y$10$8fYQdVGvp5lS5k5MSqKSOu8r9lxYHgbnghLkC6CeQH7gkx0hDzJey',
    'ADMIN'
)
ON CONFLICT (username) DO NOTHING;