
--
-- Estrutura da tabela `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `userid` int(10) UNSIGNED NOT NULL COMMENT 'ID de usuario que tentou logar',
  `datetime` datetime NOT NULL COMMENT 'Data e hora da tentativa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Registros de tentativas fracassadas de login';

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL COMMENT 'Usuario utilizado para efetuar o login',
  `pass` varchar(128) NOT NULL COMMENT 'Senha do usuario',
  `salt` varchar(128) NOT NULL COMMENT 'Dados aleatorios usados para aumentar a seguranca e personaizar a senha',
  `email` varchar(50) NOT NULL COMMENT 'Email do usuario',
  `create_time` datetime NOT NULL COMMENT 'Data e hora UTC que a conta foi criada',
  `atributos` smallint(5) UNSIGNED DEFAULT '0' COMMENT 'Tipos de permissoes (atributos) que o usuario possui',
  `pNome` varchar(25) NOT NULL COMMENT 'Primeiro nome',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relaciona todos os usuarios autorizados a logar no sistema';

-- --------------------------------------------------------

