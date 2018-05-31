import moment from "moment";

Vue.filter('date', function(value){
	if(moment(value).isValid())
		return moment(value).format('YYYY-MM-DD');
	return 'N/A';
});

Vue.filter('trans', (...args) => {
	return lang.get(...args);
});

Vue.filter('trans_choice', (...args) => {
	return lang.choice(...args);
});

Vue.filter('currency', function(value){
	var formatter = new Intl.NumberFormat('en', { style: 'currency', currency:'MYR' });
	return formatter.format(value);
});

Vue.filter('currency_std', function(value){
	var formatter = new Intl.NumberFormat('en', { style: 'currency', currency:'USD' });
	return formatter.format(value);
});

Vue.filter('formatPurchaseStatus', function(value){
	let color = 'badge-success';
	let text = 	lang.get('purchase.' + value);
	switch(value) {
		case 'pending':
			color = 'badge-warning';
			break;
		case 'pending_verification':
			color = 'badge-info';
			break;
		case 'complete':
			color = 'badge-success';
			break;
	}

	return '<span class="badge ' + color + '">' + text + '</span>';
});