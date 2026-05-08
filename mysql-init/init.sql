-- --------------------------------------------------------
-- Shelfie MySQL - Schema e Seed
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_usuario VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS livros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(150) NOT NULL,
    editora VARCHAR(150) DEFAULT NULL,
    categoria VARCHAR(100) DEFAULT NULL,
    andamento ENUM('Quero Ler', 'Lendo', 'Lido', 'Abandonei') NOT NULL DEFAULT 'Quero Ler',
    nota TINYINT DEFAULT NULL,
    resenha TEXT DEFAULT NULL,
    favorito TINYINT(1) NOT NULL DEFAULT 0,
    data_adicao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Seed: usuário de teste
-- senha: 123456
-- --------------------------------------------------------
INSERT INTO usuarios (nome_usuario, email, senha) VALUES
('isaque', 'isaque@shelfie.com', 'isaque123');

-- --------------------------------------------------------
-- Seed: livros de teste
-- --------------------------------------------------------
INSERT INTO livros (id_usuario, titulo, autor, editora, categoria, andamento, nota, resenha, favorito) VALUES
(1, 'O Guia do Mochileiro das Galáxias', 'Douglas Adams', 'Arqueiro', 'Ficção Científica', 'Lido', 5, 'Clássico absoluto. Leitura obrigatória.', 1),
(1, 'Clean Code', 'Robert C. Martin', 'Alta Books', 'Tecnologia', 'Lendo', NULL, NULL, 0),
(1, 'O Poder do Hábito', 'Charles Duhigg', 'Objetiva', 'Autoajuda', 'Quero Ler', NULL, NULL, 0);