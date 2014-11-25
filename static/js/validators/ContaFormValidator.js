/**
 * Validador dos formulários de adição e edição de lotação.
 */

function ContaFormValidator(form) {
	this.form = form;
    this.data = null;
    this.valid = false;
    if (form != null) {
        form.bootstrapValidator({
            live: 'enabled',
            submitButtons: 'button[type="submit"]',
            fields: {
                nome: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Nome é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                lotacao: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Lotação é um campo de preenchimento obrigatório'
                        }
                     }
                },
                funcao: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Cargo é um campo de preenchimento obrigatório'
                        }
                    }
                },
                telefone: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Fone é um campo de preenchimento obrigatório'
                        }
                    }
                },
                email: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'e-mail é um campo de preenchimento obrigatório'
                        },
                        emailAddress: {
                            message: 'Preencha um endereço de e-mail válido'
                        }
                    }
                },
                login: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Login é um campo de preenchimento obrigatório'
                        }
                    }
                },
                senha: {
                    trigger: 'blur',
                    validators: {
                        regexp: {
                            regexp: /\w{8,}/i,
                            message: 'A senha deve conter no mínimo 8 caracteres entre letras e números'
                        },
                        identical: {
                            field: 'confirma_senha',
                            message: 'As senhas não correspondem'
                        },
                        different: {
                            field: 'login',
                            message: 'A senha não pode ser igual ao login'
                        }
                    }
                },
                confirma_senha: {
                    validators: {
                        identical: {
                            field: 'senha',
                            message: 'As senhas não correspondem'
                        },
                        different: {
                            field: 'login',
                            message: 'A senha não pode ser igual ao login'
                        }
                    }
                }
            }
        }).on('success.form.bv', function(event) {
            // Validação bem sucedida
            event.preventDefault();
            // Obtem o formulário (o alvo da ação)
            var f = $(event.target);
            // Limpa o formulário
            $(f).data('bootstrapValidator').resetForm(false);
        })
        this.data = this.form.data('bootstrapValidator');
    }
}

ContaFormValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    this.valid = valid;
    return valid;
};

ContaFormValidator.prototype.reset = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};