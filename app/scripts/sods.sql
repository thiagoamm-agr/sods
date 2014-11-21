-- #################################################################
-- Script: sods.sql
-- Descrição: Script de criação do banco de dados do sistema SODS.
-- Autores: Thiago Monteiro e Edward Neto.
-- #################################################################

-- Bancos de dados.
create database sods_development character set utf8 collate utf8_general_ci;
create database sods_test character set utf8 collate utf8_general_ci;
create database sods_production character set utf8 collate utf8_general_ci;

-- Usuário e privilégios.
grant all on sods_development.* to 'sods'@'%' identified by 'dev123';
grant all on sods_test.* to 'sods'@'%' identified by 'dev123';
grant all on sods_production.* to 'sods'@'%' identified by 'dev123';

-- Consolidação dos privilégios.
flush privileges;

-- Definindo o banco de dados a ser usado.
use sods_development;

-- Tabelas.
create table lotacao(
    id int not null primary key auto_increment,
    nome varchar(67) not null unique,
    sigla varchar(10) not null unique,
    gerencia_id int
) character set utf8 collate utf8_general_ci;

create table solicitante(
    id int not null primary key auto_increment,
    nome varchar(255) not null, 
    lotacao_id int not null,
    cargo varchar(255) not null,
    telefone varchar(30),
    email varchar(100),
    login varchar(50) not null unique,
    senha varchar(255) not null,
    tipo_usuario char not null default 'U' comment 'U - Usuário ou A - Administrador',
    status char not null default 'A' comment 'A - Ativo ou I - Inativo',
    data_criacao timestamp not null default current_timestamp,
    data_alteracao timestamp,
    check (tipo_usuario in ('U', 'A')),
    check (status in ('A', 'I'))
) character set utf8 collate utf8_general_ci;

create table tipo_solicitacao(
    id int not null primary key auto_increment,
    nome varchar(80) not null unique,
    status char not null default 'A' comment 'A - Ativo ou I - Inativo',
    check (status in ('A', 'I'))
) character set utf8 collate utf8_general_ci;

create table solicitacao(
    id int not null primary key auto_increment,
    solicitante_id int not null,
    titulo varchar(100) not null,
    detalhamento text not null,
    info_adicionais text,
    observacoes text,
    status varchar(15) not null default 'CRIADA',
    observacoes_status text,
    data_abertura timestamp not null default current_timestamp,
    data_alteracao datetime,
    tipo_solicitacao_id int not null,
    check(status in ('CRIADA', 'EM ANÁLISE', 'DEFERIDA', 'INDEFERIDA', 'ATENDIDA', 'CANCELADA'))
) character set utf8 collate utf8_general_ci;

-- Chaves estrangeiras

-- lotacao
alter table lotacao add constraint fk_gerencia_id 
    foreign key(gerencia_id) references lotacao(id);

-- solicitante
alter table solicitante add constraint fk_lotacao_id 
    foreign key(lotacao_id) references lotacao(id);
alter table solicitacao add constraint fk_solicitante_id 
    foreign key(solicitante_id) references solicitante(id);
alter table solicitacao add constraint fk_tipo_solicitacao_id 
    foreign key(tipo_solicitacao_id) references tipo_solicitacao(id);

-- Inserção de dados

-- lotacao
insert into lotacao(nome, sigla, gerencia_id) values ('Gerência de Gestão e Planejamento', 'GEGPLAN', null);
insert into lotacao(nome, sigla, gerencia_id) values('Coordenação de Informática', 'CI', 1);

-- solicitante
insert into solicitante(nome, lotacao_id, cargo, telefone, email, login, senha) 
    values('Bruno', 1, 'Gerente', '(62) 4545-4554', 'bruno@agr.go.gov.br', 'bruno', md5('bruno'));
insert into solicitante(nome, lotacao_id, cargo, telefone, email, login, senha, tipo_usuario) 
    values('Thiago', 1, 'Programador', '(62) 4545-4554', 'thiago.amm.agr@gmail.com', 'thiago', md5('thiago'), 'A');
insert into solicitante(nome, lotacao_id, cargo, telefone, email, login, senha, tipo_usuario) 
    values('Neto', 1, 'Programador', '(62) 4545-4554', 'edward-arn@agr.go.gov.br', 'neto', md5('neto'), 'A');
insert into solicitante(nome, lotacao_id, cargo, telefone, email, login, senha, tipo_usuario) 
    values('Guthierrez', 1, 'Estágiario', '(62) 4545-4554', 'guthierrez.gs.agr@gmail.com', 'guthierrez', 
    md5('guthi123'), 'A');