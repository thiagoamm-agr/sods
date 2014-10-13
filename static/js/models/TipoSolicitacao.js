/**
 * TipoSolicitacao.js 
 */

function TipoSolicitacao() {
    this.id = null;
    this.nome = "";
    this.status = "";
}

TipoSolicitacao.prototype.toJSON = function() {
    return '{' +
        '"id": ' + this.id + ', ' +
        '"nome": "' + this.nome + '" ,' +
        '"status": "' + this.status + '"' +
    '}';
};