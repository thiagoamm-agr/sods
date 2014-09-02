/**
 * Validadores de formulário
 */

function usuario($form) {
	$($form).bootstrapValidator({
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            email: {
                validators: {
                    emailAddress: {
                        message: 'Digite um endereço de e-mail válido'
                    },
                    notEmpty: {
                    	message: 'Este campo precisa ser preenchido'
                    }
                }
            },
            login: {
	            validators: {
		            notEmpty: {
			            message: 'Este campo precisa ser preenchido'
		            }
	            }
            },
            nome: {
	            validators: {
		            notEmpty: {
			            message: 'Este campo precisa ser preenchido'
		            }
	            }
            },
            cargo: {
	            validators: {
		            notEmpty: {
			            message: 'Este campo precisa ser preenchido'
		            }
	            }
            },
            fone: {
	            validators: {
		            notEmpty: {
			            message: 'Este campo precisa ser preenchido'
		            }
	            }
            }
        }
    });
}