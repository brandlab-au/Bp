This angular.module('conFusion.controllers' [Ionic Framework]


## localStorage
- line 113 menuController addFavorite ->  favoriteFactory.addToFavorites(index);

- this is being called form app.js
- line 136 = return favoriteFactory.getFavorites();

- services addToFavorites
line 34 
favFac.addToFavorites = function (index)

- line 116 controller
- $localStorage.storeObject('id','{index}');
- line 51 services

- $scope favorites = $localStorage.getObject('id','{}');
above happens on line 16 controller.js and line 36 below
$localStorage.storeObject('userinfo',$scope.loginData);

- line 280 controller something funny happening



## This has side menu 
* git log "commits"
Data on json serve local
- ionic serve --lab 
*Ionic --lib*. 

### See services ngResource
angular.module('conFusion.services',['ngResource'])
.constant("baseURL","http://localhost:3000/")



### With the Ionic tool:

Take the name after `ionic-starter-`, and that is the name of the template to be used when using the `ionic start` command below:

```bash
$ sudo npm install -g ionic cordova
$ ionic start myApp sidemenu
```

Then, to run it, cd into `conF` and run:

```bash
$ ionic platform add ios
$ ionic build ios
$ ionic emulate ios
```


## Demo
http://plnkr.co/edit/0RXSDB?p=preview

## Issues
