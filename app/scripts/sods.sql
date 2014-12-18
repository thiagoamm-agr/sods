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
create table lotacao (
    id int not null primary key auto_increment,
    nome varchar(67) not null unique,
    sigla varchar(10) not null unique,
    gerencia_id int
) character set utf8 collate utf8_general_ci;

create table solicitante (
    id int not null primary key auto_increment,
    nome varchar(255) not null, 
    lotacao_id int not null,
    funcao varchar(255) not null,
    telefone varchar(30),
    email varchar(100),
    login varchar(50) not null unique,
    senha varchar(255) not null,
    perfil char not null default 'P' comment 'A - Admin ou P - Padrão',
    status char not null default 'A' comment 'A - Ativo ou I - Inativo',
    data_criacao timestamp default current_timestamp,
    data_alteracao timestamp null,
    check (perfil in ('A', 'P')),
    check (status in ('A', 'I'))
) character set utf8 collate utf8_general_ci;

create table tipo_solicitacao (
    id int not null primary key auto_increment,
    nome varchar(80) not null unique,
    status char not null default 'A' comment 'A - Ativo ou I - Inativo',
    check (status in ('A', 'I'))
) character set utf8 collate utf8_general_ci;

create table solicitacao (
    id int not null primary key auto_increment,
    solicitante_id int not null,
    titulo varchar(100) not null,
    detalhamento text not null,
    info_adicionais text,
    observacoes text,
    status varchar(15) not null default 'CRIADA',
    observacoes_status text,
    data_criacao timestamp default current_timestamp,
    data_alteracao timestamp null,
    tipo_solicitacao_id int not null,
    prioridade varchar(7) not null default 'NORMAL' comment 'Prioridade de atendimento',
    check(status in ('CRIADA', 'EM ANÁLISE', 'DEFERIDA', 'INDEFERIDA', 'ATENDIDA', 'CANCELADA')),
    check(prioridade in ('MÍNIMA', 'BAIXA', 'NORMAL', 'ALTA', 'URGENTE'))
) character set utf8 collate utf8_general_ci;

create table historico_solicitacao (
    id int not null primary key auto_increment,
    solicitacao_id int not null,
    solicitante_id int not null,
    titulo varchar(100) not null,
    detalhamento text not null,
    info_adicionais text,
    observacoes text,
    status varchar(15) not null default 'CRIADA',
    observacoes_status text,
    data_criacao timestamp null,
    data_alteracao timestamp null,
    tipo_solicitacao_id int not null,
    prioridade varchar(7) not null default 'NORMAL' comment 'Prioridade de atendimento',
    check(status in ('CRIADA', 'EM ANÁLISE', 'DEFERIDA', 'INDEFERIDA', 'ATENDIDA', 'CANCELADA')),
    check(prioridade in ('MÍNIMA', 'BAIXA', 'NORMAL', 'ALTA', 'URGENTE'))
) character set utf8 collate utf8_general_ci;

-- Chaves estrangeiras

-- lotacao
alter table lotacao add constraint fk_gerencia_id 
    foreign key(gerencia_id) references lotacao(id);

-- solicitante
alter table solicitante add constraint fk_lotacao_id 
    foreign key(lotacao_id) references lotacao(id);

-- solicitacao
alter table solicitacao add constraint fk_solicitante_id 
    foreign key(solicitante_id) references solicitante(id);

alter table solicitacao add constraint fk_tipo_solicitacao_id 
    foreign key(tipo_solicitacao_id) references tipo_solicitacao(id);

-- historico_solicitacao
alter table historico_solicitacao add constraint fk_historico_solicitacao_id
    foreign key(solicitacao_id) references solicitacao(id);

alter table historico_solicitacao add constraint fk_historico_solicitante_id
    foreign key(solicitante_id) references solicitante(id);

alter table historico_solicitacao add constraint fk_historico_tipo_solicitacao_id
    foreign key(tipo_solicitacao_id) references tipo_solicitacao(id);

-- Inserção de dados

-- lotacao
insert into lotacao (nome, sigla, gerencia_id) values ('Gerência de Gestão e Planejamento', 'GEGPLAN', null);
insert into lotacao (nome, sigla, gerencia_id) values ('Coordenação de Informática', 'CI', 1);

-- solicitante
insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Guthierrez', 2, 'Colaborador AGR', '(62) 3226-6400', 'guthierrez-gs-agr@gmail.com' , 'guthierrez-gs', 
    md5('agr2014'), 'A', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Thiago', 2, 'Colaborador AGR', '(62) 3226-6400', 'thiago-amm-agr@gmail.com' , 'thiago-amm', 
    md5('agr2014'), 'A', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Manoel', 2, 'Colaborador AGR', '(62) 3226-6400', 'manoelanagr@gmail.com' , 'manoel-an', 
    md5('agr2014'), 'A', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Antonio', 2, 'Colaborador AGR', '(62) 3226-6400', 'antonio-evn@gmail.com' , 'antonio-evn', 
    md5('agr2014'), 'P', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Edward', 2, 'Colaborador AGR', '(62) 3226-6400', 'edward-arn@gmail.com' , 'edward-arn', 
    md5('agr2014'), 'P', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Francisco', 2, 'Colaborador AGR', '(62) 3226-6400', 'francisco-mg@gmail.com' , 'francisco-mg', 
    md5('agr2014'), 'P', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Gabriel', 2, 'Colaborador AGR', '(62) 3226-6400', 'gabriel-ba@gmail.com' , 'gabriel-ba', 
    md5('agr2014'), 'P', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Luciana', 2, 'Colaborador AGR', '(62) 3226-6400', 'luciana-dm@gmail.com' , 'luciana-dm', 
    md5('agr2014'), 'P', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Natan', 2, 'Colaborador AGR', '(62) 3226-6400', 'natan-mn@gmail.com' , 'natan-mn', 
    md5('agr2014'), 'P', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Renato', 2, 'Colaborador AGR', '(62) 3226-6400', 'renato-ps@gmail.com' , 'renato-ps', 
    md5('agr2014'), 'P', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Wander', 2, 'Colaborador AGR', '(62) 3226-6400', 'wander-co@gmail.com' , 'wander-co', 
    md5('agr2014'), 'P', 'A', '2014-12-01', null
);

insert into solicitante (
    nome, lotacao_id, funcao, telefone, email, login, 
    senha, perfil, status, data_criacao, data_alteracao
) values (
    'Willian', 2, 'Colaborador AGR', '(62) 3226-6400', 'willian-ad@gmail.com' , 'willian-ad', 
    md5('agr2014'), 'P', 'A', '2014-12-01', null
);

-- tipo de solicitacao
insert into tipo_solicitacao (nome) values ('Desenvolvimento');
insert into tipo_solicitacao (nome) values ('Manutenção');
insert into tipo_solicitacao (nome) values ('Teste');