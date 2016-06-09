// here we taking module an install functionality
module.exports = function (x,y,callback){
    try{
        if(x<0 || y<0){
            throw new Error ("not good "+ x +"need to be biger"+" or Y need tod to be bigger "+ y);
        }
        else {
            callback(null,{
    // here is area the functions            
                perimeter: function(){return (2*(x+y));},
                area: function(){return (x*y);}
            });
        }//close to else
            
    }
    catch (error){callback(Error,null);}    
    }

