function Usuario() {
    this.id = null;
    this.nome = "";
    this.lotacao_id = "";
    this.funcao = "";
    this.telefone = "";
    this.email = "";
    this.login = "";
    this.senha = "";
    this.perfil = "";
    this.status = "";
    this.data_criacao = "";
    this.data_alteracao = "";
}

Usuario.prototype.toJSON = function() {
    return '{' +
        '"id": ' + this.id + ', ' +
        '"nome": ' + '"' + this.nome + '", ' +
        '"lotacao_id": ' + this.lotacao_id + ', ' +
        '"funcao": ' + '"' + this.funcao + '", ' +
        '"telefone": ' + '"' + this.telefone + '", ' +
        '"email": ' + '"' + this.email + '", ' +
        '"login": ' + '"' + this.login + '", ' +
        '"senha": ' + '"' + this.senha + '", ' +
        '"perfil": ' + '"' + this.perfil + '", ' +
        '"status": ' + '"' + this.status + '", ' +
        '"data_criacao": ' + '"' + this.data_criacao + '", ' +
        '"data_alteracao": ' + '"' + this.data_alteracao + '"' +
    '}';
}