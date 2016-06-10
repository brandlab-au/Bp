// 1 task need to take the code for dish router -> dishRouter.js then inported the node into here.

// 2 promotion and Leaders routers need to be inported here.

var express = require('express');
var morgan = require('morgan');
var bodyParser = require('body-parser');
var dishRouter = require('./dishRouter.js');


var hostname = 'localhost';
var port = 3000;

var app = express();

app.use(morgan('dev'));
// here we use the Router object surrported by exprss
// dishRouter will surrport GET POST PUT USE 
// most important route
var dishRouter = express.Router();
// Router uses body-parser
dishRouter.use(bodyParser.json());
// url is '/'

// this says that when /dishes use var dishRouter
app.use('/dishes',dishRouter);

app.use(express.static(__dirname + '/public'));

app.listen(port, hostname, function(){
  console.log(`Server running at http://${hostname}:${port}/`);
});
