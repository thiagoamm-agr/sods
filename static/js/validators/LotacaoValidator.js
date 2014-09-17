/**
 * Validador dos formulários de adição e edição de lotação.
 */

function LotacaoValidator(form) {
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
                },
                sigla: {
                    validators: {
                        notEmpty: {
                            message: 'Sigla é um campo de preenchimento obrigatório.'
                        }
                     }
                },
                gerencia: {
                    validators: {
                        notEmpty: {
                            message: 'Gerência é um campo de preenchimento obrigatório.'
                        }
                    }
                }
            }
        });
        this.data = this.form.data('bootstrapValidator')
    }
}

LotacaoValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    return valid;
}

LotacaoValidator.prototype.resetForm = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};