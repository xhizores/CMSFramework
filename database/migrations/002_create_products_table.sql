CREATE TABLE IF NOT EXISTS products (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title      VARCHAR(150)  NOT NULL,
    artist     VARCHAR(150)  NOT NULL,
    year       YEAR          NOT NULL,
    genre      VARCHAR(80)   NOT NULL,
    price      DECIMAL(8,2)  NOT NULL,
    stock      INT UNSIGNED  NOT NULL DEFAULT 0,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_product (
    user_id    INT UNSIGNED NOT NULL,
    product_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

INSERT INTO products (title, artist, year, genre, price, stock) VALUES
    ('Led Zeppelin IV',             'Led Zeppelin',  1971, 'Hard Rock',        29.99, 12),
    ('Paranoid',                    'Black Sabbath',  1970, 'Heavy Metal',      27.99,  8),
    ('Back in Black',               'AC/DC',          1980, 'Hard Rock',        26.99, 15),
    ('Master of Puppets',           'Metallica',      1986, 'Thrash Metal',     31.99, 10),
    ('The Dark Side of the Moon',   'Pink Floyd',     1973, 'Progressive Rock', 34.99,  6),
    ('Machine Head',                'Deep Purple',    1972, 'Hard Rock',        25.99,  9),
    ('The Number of the Beast',     'Iron Maiden',    1982, 'Heavy Metal',      28.99, 11),
    ('Appetite for Destruction',    'Guns N\'\'Roses', 1987, 'Hard Rock',       27.99, 14),
    ('British Steel',               'Judas Priest',   1980, 'Heavy Metal',      24.99,  7),
    ('A Night at the Opera',        'Queen',          1975, 'Classic Rock',     32.99,  5);
