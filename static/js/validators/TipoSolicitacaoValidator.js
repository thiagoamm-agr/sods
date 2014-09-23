/**
 * 
 */

/**
 * Validador dos formulários de adição e edição de Tipo de Solicitacao.
 */

function TipoSolicitacaoValidator(form) {
    this.form = form;
    this.data = null;
    if (form != null) {
        form.bootstrapValidator({
            live: 'enabled',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                nome: {
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

TipoSolicitacaoValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    return valid;
}

TipoSolicitacaoValidator.prototype.resetForm = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};