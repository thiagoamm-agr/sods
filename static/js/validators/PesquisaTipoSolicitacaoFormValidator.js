// Valida os dados informados no formulário de pesquisa de lotações.

function PesquisaTipoSolicitacaoFormValidator(form) {
    this.form = form;
    this.data = null;
    this.valid = false;
    if (form != null) {
        form.bootstrapValidator({
            live: 'enabled',
            submitButtons: 'button[type="submit"]',
            fields: {
                filtro: {},
                valor: {}
            }
        }).on('success.form.bv', function(event) {
            // Validação bem sucedida
            event.preventDefault();
            // Obtem o formulário (o alvo da ação)
            var f = $(event.target);
            // Obtem o id da modal
            var modal = $(f).attr('id').replace('form', '#modal');
            // Esconde a modal
            $(modal).modal('hide');
        });
        this.data = this.form.data('bootstrapValidator');
    }
}

PesquisaTipoSolicitacaoFormValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    this.valid = valid;
    return valid;
};

PesquisaTipoSolicitacaoFormValidator.prototype.reset = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};