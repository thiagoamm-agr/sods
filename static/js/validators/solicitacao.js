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
						message: ' '
					}
				}
			},
			titulo: {
				validators: {
					notEmpty: {
						message: ' '
					}
				}
			},
			desc: {
				validators: {
					notEmpty: {
						message: ' '
					}
				}
			},
			infoAdc: {
				validators: {
					notEmpty: {
						message: ' '
					}
				}
			},
			obs: {
				validators: {
					notEmpty: {
						message: ' '
					}
				}
			}
		}
	});	
}