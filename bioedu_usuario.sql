-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/09/2025 às 04:11
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bioedu_usuario`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `matricula` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `alunos`
--

INSERT INTO `alunos` (`id`, `nome`, `data_nascimento`, `matricula`) VALUES
(29, 'Ana Silva', '2005-03-15', '2025001'),
(30, 'João Santos', '2004-11-22', '2025002'),
(31, 'Maria Oliveira', '2006-01-30', '2025003'),
(32, 'Pedro Costa', '2005-07-19', '2025004'),
(33, 'Mariana Pereira', '2004-09-01', '2025005'),
(34, 'Lucas Ferreira', '2006-05-12', '2025006'),
(35, 'Camila Souza', '2005-02-28', '2025007'),
(36, 'Rafael Rodrigues', '2004-10-05', '2025008'),
(37, 'Fernanda Alves', '2006-08-17', '2025009'),
(38, 'Bruno Gonçalves', '2005-12-08', '2025010');

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno_turma`
--

CREATE TABLE `aluno_turma` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_aluno` int(10) UNSIGNED NOT NULL,
  `id_turma` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `aluno_turma`
--

INSERT INTO `aluno_turma` (`id`, `id_aluno`, `id_turma`) VALUES
(1, 29, 10),
(2, 30, 10),
(3, 31, 10),
(4, 32, 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `assinaturas`
--

CREATE TABLE `assinaturas` (
  `id_assinatura` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_plano` int(11) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `status` enum('ativa','expirada','cancelada','bloqueada') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `assinaturas`
--

INSERT INTO `assinaturas` (`id_assinatura`, `id_usuario`, `id_plano`, `data_inicio`, `data_fim`, `status`) VALUES
(1, 1, 2, '2025-06-23', '2026-06-23', ''),
(4, 8, 2, '2025-06-25', '2026-06-25', 'expirada'),
(5, 9, 1, '2025-06-26', '2025-07-26', 'cancelada'),
(6, 11, 1, '2025-06-26', '2025-07-26', 'ativa'),
(7, 1, 1, '2025-06-26', '2025-07-26', 'cancelada'),
(8, 13, 1, '2025-08-26', '2025-09-25', 'cancelada'),
(9, 14, 1, '2025-09-16', '2025-10-16', 'ativa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(6, '0001_01_01_000001_create_cache_table', 1),
(7, '0001_01_01_000002_create_jobs_table', 1),
(8, '2025_09_12_223358_create_personal_access_tokens_table', 1),
(9, '2025_09_13_003746_create_professors_table', 1),
(10, '2025_09_22_224020_create_sessions_table', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 14, 'meu-token-app', 'bbcd9a5e5f261e8cf05eea5799c2ceefa29f52a7f656e5e70795870ecde754c4', '[\"*\"]', '2025-09-22 16:13:01', NULL, '2025-09-20 03:23:59', '2025-09-22 16:13:01'),
(2, 'App\\Models\\User', 1, 'token-curl-teste', '9763ca8fbf708028e0e84797c6def099d53042538dc7ecd9fc1984f14a283ceb', '[\"*\"]', '2025-09-23 01:54:00', NULL, '2025-09-23 01:52:18', '2025-09-23 01:54:00'),
(3, 'App\\Models\\User', 1, 'meu-token-teste', '86f1b804df80e79236ab92db0887665759a0f317ba6a0fb3995b454ea05bf235', '[\"*\"]', '2025-09-30 05:04:49', NULL, '2025-09-24 01:39:27', '2025-09-30 05:04:49');

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos`
--

CREATE TABLE `planos` (
  `id_plano` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `duracao_dias` int(11) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `planos`
--

INSERT INTO `planos` (`id_plano`, `nome`, `preco`, `duracao_dias`, `descricao`) VALUES
(1, 'Plano Mensal', 29.90, 30, 'Acesso completo aos recursos da plataforma por 30 dias.'),
(2, 'Plano Anual', 299.90, 365, 'Acesso completo por 1 ano com um valor promocional.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores`
--

CREATE TABLE `professores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL COMMENT 'Armazenar sempre como hash (ex: bcrypt)',
  `disciplina` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `professores`
--

INSERT INTO `professores` (`id`, `nome`, `email`, `senha`, `disciplina`) VALUES
(4, 'Sonia Braga', 'sonia.braga@escola.com', '$2y$12$wPJnB2CWO9/GOoM49jXj0uX223vSYsJsGVgPRgw6/utmFORoBfvLW', 'Português'),
(5, 'Marcos Rocha', 'marcos.rocha@escola.com', '$2y$12$OnUkbvbTjj9HWrsWBqgvXerTaBi7d8jo86Uzqxxomk8n2SC1swJTO', 'História'),
(6, 'Juliana Paes', 'juliana.paes@escola.com', '$2y$12$2OFNY2HZRPfuDvNpBb.3x.KOXORsgY6xxsaR4OfTF59kU6/8QxQVS', 'Biologia');

-- --------------------------------------------------------

--
-- Estrutura para tabela `professors`
--

CREATE TABLE `professors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `ano_letivo` year(4) DEFAULT NULL,
  `id_professor` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `turmas`
--

INSERT INTO `turmas` (`id`, `nome`, `ano_letivo`, `id_professor`) VALUES
(9, '1º Ano A - Matemática', '2025', 4),
(10, '2º Ano B - Português', '2025', 5),
(11, '3º Ano A - História', '2025', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `data_nascimento` date NOT NULL,
  `usuario` varchar(250) NOT NULL,
  `senha` varchar(250) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `tipo_usuario` enum('comum','admin') NOT NULL DEFAULT 'comum',
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nome`, `email`, `data_nascimento`, `usuario`, `senha`, `foto_perfil`, `tipo_usuario`, `data_cadastro`) VALUES
(1, 'Beatriz Silva', 'beatriz.silva223@fatec.sp.gov.br', '2002-04-26', 'b3bea', '$2y$10$huvS8tcIedWl/0nxVOpcOe0YxatMcnbiLyK0EXnfF23WsyM43v3CS', '../uploads/1_1750517354.jpg', 'comum', '2025-06-24 11:16:55'),
(2, 'Kelven Silva', 'kelven.21@gmail.com', '1999-02-10', 'kelven01', '$2y$10$z5QXxGHOJwZMWFjMmTFz.OlhOj57a0aaEgvAU3AwFV/03heiI0Nce', '../uploads/2_1750520058.png', 'comum', '2025-06-24 11:16:55'),
(4, 'Nicolas Delphino', 'nicolas@gmail.com', '2001-10-31', 'nick', '$2y$10$qYE1apWHFGqNfLsao4z3q.iUNn7ZfENXsZn8OuKMJjx2dXy/TWCo6', '../uploads/4_1750527315.png', 'comum', '2025-06-24 11:16:55'),
(5, 'João Lucas Silva', 'joao.lucas21@gmail.com', '1997-08-25', 'JohnLucas', '$2y$10$uKqiiCUSA8WlQyl8nckoher4YKwj1jRpPoI4jZ.OUkY7sBiW2d82m', '../uploads/5_1750687215.png', 'comum', '2025-06-24 11:16:55'),
(7, 'Admin Bioedu', 'admin.bioedu@gmail.com', '2002-04-26', 'adminBioedu', '$2y$10$HcVX6F2KgGVZBQbL9LOioOSypRWCnZ0NH3q9ISp3MQWv5eA4DYUG2', NULL, 'admin', '2025-06-24 12:14:13'),
(8, 'Leonardo Maria', 'leoleo@gmail.com', '2000-09-06', 'leleo', '$2y$10$YrtWnb0PqUFtP6Ho0GGwju1Y2ArQ9MuCEZr0O3JZiesJObkyKWShi', '../uploads/8_1750862449.png', 'comum', '2025-06-25 14:39:53'),
(9, 'Bruno Fugimoto', 'brunokf@gmail.com', '1998-04-22', 'B.K.F.', '$2y$10$abPflAW.dKus0dqRlPGV4u5ns6K1bxexa6kcADdc1fB86P8dTFyDy', '../uploads/9_1750938870.png', 'comum', '2025-06-26 11:52:12'),
(10, 'Carlos Castro ', 'carlos@gmail.com', '1998-10-25', 'carlos', '$2y$10$BK9ztenPGx.v2HO8xdnI/u2R565ZwgKZZ7GDtbgoE08b.jJsGKygW', NULL, 'comum', '2025-06-26 12:17:02'),
(11, 'victoria', 'vic@gmail.com', '2005-01-11', 'vts', '$2y$10$eRvzooAxGcqlX4z/rWOM4eLz/IzWnufkDY8p1OyqP9hT3IPjOPi3i', '../uploads/11_1750942578.png', 'comum', '2025-06-26 12:54:50'),
(12, 'admin', 'admin@gmail.com', '2002-04-26', 'admin', '$2y$10$tVFEzebGAq8SkZ9atfc06etAfZF1QrRko63aSNj4LMpK1kPScLl56', NULL, 'admin', '2025-08-26 12:43:49'),
(13, 'Stefani Straccini', 'stefani.straccini01@gmail.com', '1993-03-01', 'Stefani', '$2y$10$jCAYfBsL8czvt84ki3o..ej60gdWYgc5F2yFSGqIzAxjiqquWMrmi', '../uploads/13_1756212838.png', 'comum', '2025-08-26 12:48:23'),
(14, 'Beatriz Ferreira', 'bea@ferreira.com', '2001-08-25', 'biaFerreira', '$2y$10$Ou60aNagzUgl0NRMX.HK8ON/hk2c/qO8jgRq0iZO93XI8lchYDfbi', '../uploads/14_1758024967.png', 'comum', '2025-09-16 12:08:59');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `aluno_turma`
--
ALTER TABLE `aluno_turma`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aluno_turma_unique` (`id_aluno`,`id_turma`),
  ADD KEY `aluno_turma_id_aluno_foreign` (`id_aluno`),
  ADD KEY `aluno_turma_id_turma_foreign` (`id_turma`);

--
-- Índices de tabela `assinaturas`
--
ALTER TABLE `assinaturas`
  ADD PRIMARY KEY (`id_assinatura`),
  ADD KEY `fk_assinatura_usuario` (`id_usuario`),
  ADD KEY `fk_assinatura_plano` (`id_plano`);

--
-- Índices de tabela `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Índices de tabela `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Índices de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices de tabela `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Índices de tabela `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Índices de tabela `planos`
--
ALTER TABLE `planos`
  ADD PRIMARY KEY (`id_plano`);

--
-- Índices de tabela `professores`
--
ALTER TABLE `professores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- Índices de tabela `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turmas_id_professor_foreign` (`id_professor`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alunos`
--
ALTER TABLE `alunos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de tabela `aluno_turma`
--
ALTER TABLE `aluno_turma`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `assinaturas`
--
ALTER TABLE `assinaturas`
  MODIFY `id_assinatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `planos`
--
ALTER TABLE `planos`
  MODIFY `id_plano` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `professores`
--
ALTER TABLE `professores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `professors`
--
ALTER TABLE `professors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `aluno_turma`
--
ALTER TABLE `aluno_turma`
  ADD CONSTRAINT `aluno_turma_id_aluno_foreign` FOREIGN KEY (`id_aluno`) REFERENCES `alunos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `aluno_turma_id_turma_foreign` FOREIGN KEY (`id_turma`) REFERENCES `turmas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `assinaturas`
--
ALTER TABLE `assinaturas`
  ADD CONSTRAINT `fk_assinatura_plano` FOREIGN KEY (`id_plano`) REFERENCES `planos` (`id_plano`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_assinatura_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `turmas`
--
ALTER TABLE `turmas`
  ADD CONSTRAINT `turmas_id_professor_foreign` FOREIGN KEY (`id_professor`) REFERENCES `professores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
