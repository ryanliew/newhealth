import moment from "moment";

Vue.filter('date', function(value){
	if(moment(value).isValid())
		return moment(value).format('YYYY-MM-DD');
	return 'N/A';
});

Vue.filter('trans', (...args) => {
	return lang.get(...args);
});