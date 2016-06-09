// here rect callback function 
var rect = require('./rectangle-2');

function solveRect(l,b){
    
    console.log("Solving for rectangle with l ="
                + l + " and b = " + b);
// rect var line 1 = () the callback = rectangle that is is rectangle-2   
    rect(l,b,function(err,rectangle){
         if (err) {
	    console.log(err);
    }else {
	    console.log("The area of a rectangle of dimensions length = " + l + " and breadth = " + b + " is " + rectangle.area());
// rectangle concat's the perimeter property of the callback        
            console.log("The perimeter of a rectangle of dimensions length = "
                 + l + " and breadth = " + b + " is " + rectangle.perimeter());
	}
         });
    
};

solveRect(22,4);
solveRect(3,5);
solveRect(-3,5);