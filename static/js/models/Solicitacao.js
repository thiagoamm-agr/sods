function Solicitacao() {
    this.id = null;
    this.solicitante_id = null;
    this.titulo = "";
    this.detalhamento = "";
    this.info_adicionais = "";
    this.observacoes = "";
    this.status = "";
    this.observacoes_status = "";
    this.tipo_solicitacao_id = null;
}

Solicitacao.prototype.toJSON = function() {
    return '{' +
        '"id": ' + this.id + ', ' +
        '"solicitante_id": ' + this.solicitante_id + ', ' +
        '"titulo": ' + '"' + this.titulo + '", ' +
        '"detalhamento": ' + '"' + this.detalhamento + '", ' +
        '"info_adicionais": ' + '"' + this.info_adicionais + '", ' +
        '"observacoes": ' + '"' + this.observacoes + '", ' +
        '"status": ' + '"' + this.status + '", ' +
        '"observacoes_status": ' + '"' + this.observacoes_status + '", ' +
        '"tipo_solicitacao_id": ' + this.tipo_solicitacao_id +
    '}';
};