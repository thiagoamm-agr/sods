function Usuario() {
	this.id = null;
	this.nome = "";
	this.lotacao_id = "";
	this.cargo = "";
	this.telefone = "";
	this.email = "";
	this.login = "";
	this.senha = "";
	this.tipo_usuario = "";
	this.status = "";
	this.data_criacao = "";
	this.data_alteracao = "";
}

Usuario.prototype.toJSON = function() {
	return '{' +
		'"id": ' + '"' + this.id + '", ' +
    	'"nome": ' + '"' + this.nome + '", ' +
    	'"lotacao_id": ' + this.lotacao_id + ', ' +
    	'"cargo": ' + '"' + this.cargo + '", ' +
    	'"telefone": ' + '"' + this.telefone + '", ' +
    	'"email": ' + '"' + this.email + '", ' +
    	'"login": ' + '"' + this.login + '", ' +
    	'"senha": ' + '"' + this.senha + '", ' +
    	'"tipo_usuario": ' + '"' + this.tipo_usuario + '", ' +
    	'"status": ' + '"' + this.status + '", ' +
    	'"data_criacao": ' + '"' + this.data_criacao + '", ' +
    	'"data_alteracao": ' + '"' + this.data_alteracao + '"' +
    '}';
}