/**
 * Validador do formulário de informações pessoais 
 * do usuário logado no sistema.
 */

function InformacoesPessoaisFormValidator(form) {
	this.form = form;
    this.data = null;
    this.valid = false;
    if (form != null) {
        form.bootstrapValidator({
            submitButtons: 'button[type="submit"]',
            excluded: [':disabled', ':hidden'],
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
                lotacao_id: {
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
                            message: 'Função é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                telefone: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'Telefone é um campo de preenchimento obrigatório.'
                        }
                    }
                },
                email: {
                    trigger: 'blur',
                    validators: {
                        notEmpty: {
                            message: 'E-mail é um campo de preenchimento obrigatório.'
                        },
                        emailAddress: {
                            message: 'Informe um endereço de e-mail válido,'
                        }
                    }
                },
                login: {
                    excluded: 'true',
                },
                senha: {
                    trigger: 'blur',
                    validators: {
                        regexp: {
                            regexp: /\w{8,}/i,
                            message: 'A senha deve conter no mínimo 8 caracteres entre letras e números.'
                        },
                        different: {
                            field: 'login',
                            message: 'O login não pode ser igual a senha.'
                        },
                        notEmpty: {
                            message: 'Senha é um campo de preenchimento obrigatório.'
                        },
                        identical: {
                            field: 'confirma_senha',
                            message: 'As senhas não correspondem.'
                        }
                    }
                },
                confirma_senha: {
                    trigger: 'blur',
                    validators: {
                        identical: {
                            field: 'senha',
                            message: 'As senhas não correspondem'
                        },
                        notEmpty: {
                            message: 'Confirme a senha é um campo de preenchimento obrigatório.'
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
            //$(f).data('bootstrapValidator').resetForm(false);
        })
        this.data = this.form.data('bootstrapValidator');
    }
}

InformacoesPessoaisFormValidator.prototype.validate = function() {
    var valid = false;
    if (this.form != null) {
        this.data.validate();
        valid = this.data.isValid();
    }
    this.valid = valid;
    return valid;
};

InformacoesPessoaisFormValidator.prototype.reset = function() {
    if (this.data != null) {
        this.data.resetForm(true);
    }
};