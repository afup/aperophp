/**
 * Init Datepicker.
 */
function init_datepicker(inputDP, altInputDP) 
{
	inputDP.datepicker({ 
		dateFormat: 'dd/mm/yy', 
		altField: altInputDP, 
		altFormat: 'yy-mm-dd',
		dayNamesMin: ['Di','Lu','Ma','Me','Je','Ve','Sa'],
		monthNames: ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'],
		prevText: 'Mois précédent',
		nextText: 'Mois suivant',
	});
}