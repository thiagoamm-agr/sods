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
            excluded: [':disabled'],
            submitButtons: 'button[type="submit"]',
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
        }).on('success.form.bv', function(event) {
            // Evita a submissão padrão do formulário.
            event.preventDefault();
            // Obtém o alvo do evento (formulário).
            var f = $(event.target);
            // Obtem o id da modal.
            var modal = $(f).attr('id').replace('form', '#modal');
            // Esconde a modal.
            $(modal).modal('hide');
            console.log('Formulário validado com sucesso!')
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
    this.valid = valid;
    return valid;
};

TipoSolicitacaoFormValidator.prototype.reset = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};