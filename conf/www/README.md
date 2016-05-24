This angular.module('conFusion.controllers' [Ionic Framework]

## ngCordova
'$ionicPlatform', '$cordovaLocalNotification', '$cordovaToast',
### See services ngResource
angular.module('conFusion.services',['ngResource'])
.constant("baseURL","http://localhost:3000/")
### MenuController
 - $scope.addFavorite -> services
 
### DishDetailController needs add notif same as MeunCtr
- line 308 added 
- MenuController line 166 methord
- addFavorite $scope.addFavorite Controller 
- on dish-detail-popover.html -> favoriteFactory Line 304
- favoriteFactory.addToFavorites

### appCtrl()
- getGalery() line 53
- console.log('Image URI: ' + results[i]);
- $scope.registration.imgSrc = 'Image URI: ' + results[i];

## localStorage

- $scope favorites = $localStorage.getObject('id','{}');
above happens on line 16 controller.js and line 36 below
$localStorage.storeObject('userinfo',$scope.loginData);


## This has side menu 
* git log "commits"
Data on json serve local
- ionic serve --lab 
*Ionic --lib*. 


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
