

    var express = require('express');
    var bodyParser = require('body-parser');
    var promoRouter = express.Router();
    
    promoRouter.use(bodyParser.json());
    promoRouter.route('/')
.all(function(req,res,next) {
      res.writeHead(200, { 'Content-Type': 'text/plain' });
      next();
})

.get(function(req,res,next){
        res.end('Will send all the promo to you!');
})

.post(function(req, res, next){
    res.end('Will add the promo: ' + req.body.name + ' with details: ' + req.body.description);    
})

.delete(function(req, res, next){
        res.end('Deleting all promotions');
});
// this is a chane of routes
promoRouter.route('/:promotionsId')
.all(function(req,res,next) {
      res.writeHead(200, { 'Content-Type': 'text/plain' });
      next();
})

.get(function(req,res,next){
        res.end('Will send details of the promotions: ' + req.params.promotionsId +' to you!');
})

.put(function(req, res, next){
        res.write('Updating the promotions: ' + req.params.promotionsId  + '\n');
    res.end('Will update the promotions: ' + req.body.name + 
            ' with details: ' + req.body.description);
})

.delete(function(req, res, next){
        res.end('Deleting promotions: ' + req.params.promotionsId);
});

module.exports = promoRouter;