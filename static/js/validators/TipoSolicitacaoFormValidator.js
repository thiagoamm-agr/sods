/**
 * Validador dos formulários de adição e edição de Tipo de Solicitacao.
 */

function TipoSolicitacaoFormValidator(form) {
    this.form = form;
    this.data = null;
    this.valid = false;
    if (form != null) {
        form.bootstrapValidator({
            live: 'enabled',
            fields: {
                nome: {
                	trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Nome é um campo de preenchimento obrigatório.'
                        }
                    }
                }
            }
        });
        this.data = this.form.data('bootstrapValidator');
    }
}

TipoSolicitacaoFormValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    return valid;
}

TipoSolicitacaoFormValidator.prototype.resetForm = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};