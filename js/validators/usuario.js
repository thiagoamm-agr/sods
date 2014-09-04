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
                    	message: ' '
                    }
                }
            },
            login: {
	            validators: {
		            notEmpty: {
			            message: ' '
		            }
	            }
            },
            nome: {
	            validators: {
		            notEmpty: {
			            message: ' '
		            }
	            }
            },
            cargo: {
	            validators: {
		            notEmpty: {
			            message: ' '
		            }
	            }
            },
            fone: {
	            validators: {
		            notEmpty: {
			            message: ' '
		            }
	            }
            }
        },
    });
	
}
