 SET character_set_client = utf8mb4 ;
CREATE TABLE `configs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `us` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pw` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cliente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cookie` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proxy` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `configs` WRITE;
INSERT INTO `configs` VALUES (1,'cobraja','2525xpe','1521','1367','https://app.linceconsultadedados.com.br/','X19jZmR1aWQ9ZGE3YThmZjBmOTY0ODYzYzk0MzBjNGI2MTFhZmQ5NWU3MTU0ODEwNzYxMTsgbGFyYXZlbF9zZXNzaW9uPWV5SnBkaUk2SW5WV1ExbE9Ra2x3Y0VoUVEyRm5RVWxsWjFFMU5FRTlQU0lzSW5aaGJIVmxJam9pVjNoT1dHVmpOemxqVm5GaFpHaGFRbEp2TVUxMGJ6aFZaazl3UVZkQlZtNUNNWFV4WWpWbmR6UlJVMGQ0Y0ZaVWJ','aFZuOGo1QjJxZTYwTDNjT3RoczliTENseU1SR3J5OWhCdDhiaGc2bg==','191.252.186.96:3128','1',NULL,'2019-01-21 21:53:31');
UNLOCK TABLES;

 SET character_set_client = utf8mb4 ;
CREATE TABLE `doc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nascimento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mae` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doc_doc_index` (`doc`),
  KEY `doc_nome_index` (`nome`),
  KEY `doc_nascimento_index` (`nascimento`),
  KEY `doc_mae_index` (`mae`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

 SET character_set_client = utf8mb4 ;
CREATE TABLE `email` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_doc_index` (`doc`),
  KEY `email_nome_index` (`nome`),
  KEY `email_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


 SET character_set_client = utf8mb4 ;
CREATE TABLE `enderecos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logradouro` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `complemento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cep` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cidade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uf` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enderecos_doc_index` (`doc`),
  KEY `enderecos_logradouro_index` (`logradouro`),
  KEY `enderecos_cep_index` (`cep`),
  KEY `enderecos_cidade_index` (`cidade`),
  KEY `enderecos_uf_index` (`uf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


 SET character_set_client = utf8mb4 ;
CREATE TABLE `nomes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cidade` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uf` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cep` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nomes_doc_index` (`doc`),
  KEY `nomes_nome_index` (`nome`),
  KEY `nomes_cidade_index` (`cidade`),
  KEY `nomes_uf_index` (`uf`),
  KEY `nomes_cep_index` (`cep`)
) ENGINE=InnoDB AUTO_INCREMENT=47277 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


 SET character_set_client = utf8mb4 ;
CREATE TABLE `telefones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doc` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `telefones_doc_index` (`doc`),
  KEY `telefones_telefone_index` (`telefone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

