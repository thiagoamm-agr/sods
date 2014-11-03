// Valida os dados informados no formulário de pesquisa de lotações.

function PesquisaLotacaoFormValidator(form) {
    this.form = form;
    this.data = null;
    this.valid = false;
    if (form != null) {
        form.bootstrapValidator({
            live: 'enabled',
            submitButtons: 'button[type="submit"]',
            fields: {
                atributo: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Atributo é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                criterio: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Critério é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                valor: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Valor é um campo de preenchimento obrigatório.'
                        }
                    }
                }
            }
        });
        this.data = this.form.data('bootstrapValidator');
    }
}

PesquisaLotacaoFormValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    this.valid = valid;
    return valid;
};

PesquisaLotacaoFormValidator.prototype.reset = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};