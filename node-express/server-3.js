var express = require('express');
var morgan = require('morgan');
var bodyParser = require('body-parser');

var hostname = 'localhost';
var port = 3000;
// server-4 you can use router the same as express
var app = express();

app.use(morgan('dev'));
app.use(bodyParser.json());

app.all('/dishes', function(req,res,next) {
      res.writeHead(200, { 'Content-Type': 'text/plain' });
      next();
});

app.get('/dishes', function(req,res,next){
        res.end('this is get\s all the dishes to you!');
});
// so body-paser . app POST . JsObj -> req.body
app.post('/dishes', function(req, res, next){
     res.end('Creat new dish: ' + req.body.name + ' with details: ' + req.body.description);
});

app.delete('/dishes', function(req, res, next){
        res.end('Deleting all dishes not just on as no Perams');
});
//req.params.dishId will have access to id peramiter 
app.get('/dishes/:dishId', function(req,res,next){
        res.end('Details of the dish: ' + req.params.dishId +' for you!');
});

app.put('/dishes/:dishId', function(req, res, next){
    res.write('Updating with put dish: ' + req.params.dishId + '\n');
    res.end('Will update the dish: ' + req.body.name + 
            ' with details: ' + req.body.description);
});

app.delete('/dishes/:dishId', function(req, res, next){
        res.end('Deleting dish: ' + req.params.dishId);
});
// for html file in public folder
app.use(express.static(__dirname + '/public'));

app.listen(port, hostname, function(){
  console.log(`Server running at http://${hostname}:${port}/`);
});