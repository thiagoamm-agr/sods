/**
 * Validador dos formulários de adição e edição de lotação.
 */

function LotacaoFormValidator(form) {
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
                },
                sigla: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Sigla é um campo de preenchimento obrigatório.'
                        }
                     }
                },
                gerencia: {
                    trigger: 'blur change',
                    validators: {
                        notEmpty: {
                            message: 'Gerência é um campo de preenchimento obrigatório.'
                        }
                    }
                }
            }
        })
        this.data = this.form.data('bootstrapValidator');
    }
}

LotacaoFormValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    this.valid = valid;
    return valid;
}

LotacaoFormValidator.prototype.reset = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};