/**
 * Validadores de formulário
 */

function UsuarioValidator() {}

UsuarioValidator.validate = function(form){
	form.bootstrapValidator({
		 live: "enabled",
		 feedbackIcons: {
		        valid: 'glyphicon glyphicon-ok',
		    invalid: 'glyphicon glyphicon-remove',
		    validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			nome: {
				validators: {
					notEmpty: {
		            	message: 'Este campo deve ser preenchido'
		            }
	            }
			},
			cargo: {
				validators: {
					notEmpty: {
		            	message: 'Este campo deve ser preenchido'
		            }
	            }
			},
			fone: {
				validators: {
					notEmpty: {
		            	message: 'Este campo deve ser preenchido'
		            }
	            }
			},
			email: {
				validators: {
					emailAdress: {
						message: 'Digite um endereço de e-mail válido'
					},
					notEmpty: {
		            	message: 'Este campo deve ser preenchido'
		            }
	            }
			},
			login: {
				validators: {
					notEmpty: {
		            	message: 'Este campo deve ser preenchido'
		            }
	            }
			},
			tipoUsuario: {
				validators: {
					notEmpty: {
						message: 'Este campo deve ser preenchido'
					}
				}
			}
		}
	});
}
