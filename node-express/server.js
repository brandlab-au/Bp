var express = require('express')
	, morgan = require('morgan');
	
var hostname = 'localhost';
var port = 3000;

var app = express();
app.use(morgan('dev'));

app.use('/dishes', require('./dishRouter'));
app.use('/promotions', require('./promoRouter'));
app.use('/leadership', require('./leaderRouter'));

app.use(express.static(__dirname + '/public'));

app.listen(port, hostname, function() {
	console.log(`Running server at http://${hostname}:${port}`);
});