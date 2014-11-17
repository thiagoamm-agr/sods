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
                filtro: {
                    trigger: 'blur change',
                    validators: {
                        notEmpty: {
                            message: 'Filtro é um campo de preenchimento obrigatório.'
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