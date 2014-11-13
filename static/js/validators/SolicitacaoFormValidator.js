/**
 * Validador dos formulários de adição e edição de Solicitação.
 */

function SolicitacaoFormValidator(form) {
    this.form = form;
    this.data = null;
    this.valid = false;
    if (form != null) {
        form.bootstrapValidator({
            submitButtons: 'button[type="submit"]',
            live: 'enabled',
            excluded: [':disabled', ':hidden'],
            fields: {
                titulo: {
                	trigger: 'blur',
                	validators: {
                		notEmpty: {
                			message: 'Título é um campo de preenchimento obrigatório.'
                		}
                	}
                },
                detalhamento: {
                	trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Descrição da Solicitação é um campo de preenchimento obrigatório'
                        }
                     }
                },
                observacoes: {

                },
                info_adicionais: {

                },                                  
                tipo_solicitacao_id: {
                    trigger: 'blur change',
                    group: '.col-sm-6',
                    validators: {
                        notEmpty: {
                            message: 'Tipo de Solicitação é um campo de preenchimento obrigatório.'
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

SolicitacaoFormValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    return valid;
};

SolicitacaoFormValidator.prototype.reset = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};