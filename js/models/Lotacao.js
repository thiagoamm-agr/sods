function Lotacao() {
	this.id = "";
	this.nome = "";
	this.sigla = "";
	this.gerencia_id = "";
}

Lotacao.prototype.toJSON = function() {
	return "{" +
		'"id": ' + this.id + ', ' +
		'"nome": ' + this.nome + ', ' +
		'"sigla": ' + this.sigla + ', ' +
		'"gerencia_id": ' + this.gerencia_id +
	"}";
};