/**
 * 
 */

function TipoSolicitacao() {
	this.id = null;
	this.nome = "";
}

TipoSolicitacao.prototype.toJSON = function() {
	return '{' +
		'"id": ' + this.id + ', ' +
		'"nome": ' + '"' + this.nome + '", ' +
	'}';
};