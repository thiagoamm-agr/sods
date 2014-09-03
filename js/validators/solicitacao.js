/**
 * Validadores de modais da solicitação 
 */

function solicitacao($form) {
	$($form).bootstrapValidator({
		message: 'Esse valor não é válido',
		fields: {
			nome: {
				validators: {
					notEmpty: {
						message: 'Este campo precisa ser preenchido'
					}
				}
			},
			titulo: {
				validators: {
					notEmpty: {
						message: 'Este campo precisa ser preenchido'
					}
				}
			},
			desc: {
				validators: {
					notEmpty: {
						message: 'Este campo precisa ser preenchido'
					}
				}
			},
			infoAdc: {
				validators: {
					notEmpty: {
						message: 'Este campo precisa ser preenchido'
					}
				}
			},
			obs: {
				validators: {
					notEmpty: {
						message: 'Este campo precisa ser preenchido'
					}
				}
			}
		}
	});	
}