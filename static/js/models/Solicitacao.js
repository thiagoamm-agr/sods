function Solicitacao() {
    this.id = null;
    this.solicitanteId = null;
    this.titulo = "";
    this.detalhamento = "";
    this.infoAdicionais = "";
    this.observacoes = "";
    this.status = "";
    this.observacoesStatus = "";
    this.tipoSolicitacaoId = null;
}

Solicitacao.prototype.toJSON = function() {
    return '{' +
        '"id": ' + this.id + ', ' +
        '"solicitante_id": ' + this.solicitanteId + ', ' +
        '"titulo": ' + '"' + this.titulo + '", ' +
        '"detalhamento": ' + '"' + this.detalhamento + '", ' +
        '"info_adicionais": ' + '"' + this.infoAdicionais + '", ' +
        '"observacoes": ' + '"' + this.observacoes + '", ' +
        '"status": ' + '"' + this.status + '", ' +
        '"observacoes_status": ' + '"' + this.observacoesStatus + '", ' +
        '"tipo_solicitacao_id": ' + this.tipoSolicitacaoId +
    '}';
};