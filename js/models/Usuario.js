function Usuario() {
	this.id = "";
	this.nome = "";
	this.secaoId = "";
	this.cargo = "";
	this.telefoneRamal = "";
	this.email = "";
	this.login = "";
	this.senha = "";
	this.tipoUsuario = "";
	this.status = "";
	this.dataCriacao = "";
	this.dataAlteracao = "";
}

Usuario.prototype.toJSON = function() {
	return '{' +
		'"id": ' + '"' + this.id + '", ' +
    	'"nome": ' + '"' + this.nome + '", ' +
    	'"secaoId": ' + '"' + this.secaoId + '", ' +
    	'"cargo": ' + '"' + this.cargo + '", ' +
    	'"telefoneRamal": ' + '"' + this.telefoneRamal + '", ' +
    	'"email": ' + '"' + this.email + '", ' +
    	'"login": ' + '"' + this.login + '", ' +
    	'"senha": ' + '"' + this.senha + '", ' +
    	'"tipoUsuario": ' + '"' + this.tipoUsuario + '", ' +
    	'"status": ' + '"' + this.status + '", ' +
    	'"dataCriacao": ' + '"' + this.dataCriacao + '", ' +
    	'"dataAlteracao": ' + '"' + this.dataAlteracao + '"' +
    '}';
}