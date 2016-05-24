angular.module('conFusion.controllers', [])

.controller('AppCtrl', function ($scope,$ionicModal,$timeout, $localStorage,$ionicPlatform,$cordovaCamera,$cordovaImagePicker) {
    
     $scope.registration = {};
    // Create the registration modal that we will use later
    $ionicModal.fromTemplateUrl('templates/register.html', {
        scope: $scope
    }).then(function (modal) {
        $scope.registerform = modal;
    });

    // Triggered in the registration modal to close it
    $scope.closeRegister = function () {
        $scope.registerform.hide();
    };

    // Open the registration modal
    $scope.register = function () {
        $scope.registerform.show();
    };
    
    $ionicPlatform.ready(function() {
        var options = {
            quality: 50,
            destinationType: Camera.DestinationType.DATA_URL,
            sourceType: Camera.PictureSourceType.CAMERA,
            allowEdit: true,
            encodingType: Camera.EncodingType.JPEG,
            targetWidth: 100,
            targetHeight: 100,
            popoverOptions: CameraPopoverOptions,
            saveToPhotoAlbum: false
        };
         $scope.takePicture = function() {
            $cordovaCamera.getPicture(options).then(function(imageData) {
                $scope.registration.imgSrc = "data:image/jpeg;base64," + imageData;
            }, function(err) {
                console.log(err);
            });

            $scope.registerform.show();
             
        };//close takePicture
        
         var pickerOptions = {
               maximumImagesCount: 10,
               width: 800,
               height: 800,
               quality: 80
              };
        
        $scope.getGalery = function(){
            $cordovaImagePicker.getPictures(pickerOptions)
                .then(function (results) {
                  for (var i = 0; i < results.length; i++) {
                    $scope.registration.imgSrc = 'Image URI: ' + results[i];  
                    console.log('Image URI: ' + results[i]);
                  }
                }, function(error) {// error getting photos
            });
                  
           };//close to getGalery() -> register.html
    
    });//close to ready

    // Perform the registration action when the user submits the registration form
    $scope.doRegister = function () {
        // Simulate a registration delay. Remove this and replace with your registration
        // code if using a registration system
        $timeout(function () {
            $scope.closeRegister();
        }, 1000);
    };

  // With the new view caching in Ionic, Controllers are only called
  // when they are recreated or on app start, instead of every page change.
  // To listen for when this page is active (for example, to refresh data),
  // listen for the $ionicView.enter event:
  //$scope.$on('$ionicView.enter', function(e) {
  //});

  // Form data for the login modal
  $scope.loginData = $localStorage.getObject('userinfo','{}');

  // Create the login modal that we will use later
  $ionicModal.fromTemplateUrl('templates/login.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.modal = modal;
  });

  // Triggered in the login modal to close it
  $scope.closeLogin = function() {
    $scope.modal.hide();
  };

  // Open the login modal
  $scope.login = function() {
    $scope.modal.show();
  };

  // Perform the login action when the user submits the login form
  $scope.doLogin = function() {
    console.log('Doing login', $scope.loginData);
    $localStorage.storeObject('userinfo', $scope.loginData);

    // Simulate a login delay. Remove this and replace with your login
    // code if using a login system
    $timeout(function() {
      $scope.closeLogin();
    }, 1000);
  };
  
  $scope.reservation = {};
  
  // Create the Reserve Table modal that we will use later
  $ionicModal.fromTemplateUrl('templates/reserve.html', {
    scope: $scope
  }).then(function(modal) {
    $scope.reserveform = modal;
  });
  
  // Triggered the Reserve Table modal to close
  $scope.closeReserve = function() {
    $scope.reserveform.hide();
  };
  
  // Open the Reserve Table modal
  $scope.reserve = function() {
    $scope.reserveform.show();
  };
  
  // Perform the Reserve Table action when the user submits the Reserve form
  $scope.doReserve = function() {
    console.log('Doing reservation', $scope.reservation);

    // Simulate a delay. Remove this and replace with your 
    // code if using a server
    $timeout(function() {
      $scope.closeReserve();
    }, 1000);
  };
})

.controller('MenuController', ['$scope', 'dishes', 'favoriteFactory', 'baseURL', '$ionicListDelegate', '$ionicPlatform', '$cordovaLocalNotification', '$cordovaToast', function ($scope, dishes, favoriteFactory, baseURL, $ionicListDelegate, $ionicPlatform, $cordovaLocalNotification, $cordovaToast) {

            
            $scope.baseURL = baseURL;
            $scope.tab = 1;
            $scope.filtText = '';
            $scope.showDetails = false;
                              
            $scope.dishes = dishes;
      
            $scope.select = function(setTab) {
                $scope.tab = setTab;
                
                if (setTab === 2) {
                    $scope.filtText = "appetizer";
                }
                else if (setTab === 3) {
                    $scope.filtText = "mains";
                }
                else if (setTab === 4) {
                    $scope.filtText = "dessert";
                }
                else {
                    $scope.filtText = "";
                }
            };

            $scope.isSelected = function (checkTab) {
                return ($scope.tab === checkTab);
            };
    
            $scope.toggleDetails = function() {
                $scope.showDetails = !$scope.showDetails;
            };
            
            $scope.addFavorite = function(index) {
                
                favoriteFactory.addToFavorites(index);
                $ionicListDelegate.closeOptionButtons();
                // ionicPlatform works with ngCordova
                $ionicPlatform.ready(function () {
                $cordovaLocalNotification.schedule({
                // jsObj here input into schedula methord    
                    id: 1,
                    title: "Added Favorite",
                    text: $scope.dishes[index].name
                }).then(function () {
                    console.log('Added Favorite '+$scope.dishes[index].name+);
                },
                function () {
                    console.log('Failed to add Notification ');
                });
                // toast function show methord
                $cordovaToast
                  .show('Added Favorite '+$scope.dishes[index].name, 'long', 'center')
                  .then(function (success) {
                      // success
                  }, function (error) {
                      // error
                  });
        });//close to $ionicPlatform ready
                
            };//close to addFavorite on click muenu.html
            
        }])//close to MenuController
        
.controller('FavoritesController', ['$scope', 'dishes', 'favorites', 'favoriteFactory', 'baseURL', '$ionicPopup', 
                            function($scope, dishes, favorites, favoriteFactory, baseURL, $ionicPopup) {
            
            $scope.baseURL = baseURL;
                      
            $scope.favorites = favorites;
            $scope.dishes = dishes;
            
            $scope.shouldShowDelete = false;
                                   
            $scope.toggleDelete = function() {
                $scope.shouldShowDelete = !$scope.shouldShowDelete;
            };
                       
            $scope.deleteFavorite = function(index) {
            
                var confirmPopup = $ionicPopup.confirm({
                    title: 'Confirm Delete',
                    template: 'Are you sure you want to delete this item?'
                });
                
                confirmPopup.then(function(res) {
                    if (res) {
                        console.log('Ok to delete');
                        favoriteFactory.deleteFromFavorites(index);
                    } else {
                        console.log('Canceled delete');
                    }
                });
                $scope.shouldShowDelete = false;
            };
        }])

.controller('ContactController', ['$scope', function($scope) {

            $scope.feedback = {mychannel:"", firstName:"", lastName:"", agree:false, email:"" };
            
            var channels = [{value:"tel", label:"Tel."}, {value:"Email",label:"Email"}];
            
            $scope.channels = channels;
            $scope.invalidChannelSelection = false;
                        
        }])

.controller('FeedbackController', ['$scope', 'feedbackFactory', function($scope,feedbackFactory) {
            
            $scope.sendFeedback = function() {
                
                console.log($scope.feedback);
                
                if ($scope.feedback.agree && ($scope.feedback.mychannel == "")) {
                    $scope.invalidChannelSelection = true;
                    console.log('incorrect');
                }
                else {
                    $scope.invalidChannelSelection = false;
                    feedbackFactory.save($scope.feedback);
                    $scope.feedback = {mychannel:"", firstName:"", lastName:"", agree:false, email:"" };
                    $scope.feedback.mychannel="";
                    $scope.feedbackForm.$setPristine();
                    console.log($scope.feedback);
                }
            };
        }])

.controller('DishDetailController', ['$scope', '$stateParams', 'dish', '$ionicPopover', '$ionicModal', 'menuFactory', 'favoriteFactory', 'baseURL','$ionicPopover','$ionicPlatform', '$cordovaLocalNotification', '$cordovaToast',
                             function($scope, $stateParams, dish, $ionicPopover, $ionicModal, menuFactory, favoriteFactory, baseURL,$ionicPopover,$ionicPlatform,$cordovaLocalNotification, $cordovaToast) {
           
        // ion modal
            $ionicModal.fromTemplateUrl('templates/dish-comment.html', {
            scope: $scope,
            animation: 'slide-in-up'
          }).then(function(modal) {
            $scope.modal = modal;
          });
          $scope.openModal = function() {
            $scope.modal.show();
          };
          $scope.closeModal = function() {
            $scope.modal.hide();
          };// ion modal close                         
                                 
        //popover open  
    
         $ionicPopover.fromTemplateUrl('templates/dish-detail-popover.html', {
            scope: $scope
          }).then(function(popover) {
            $scope.popover = popover;
          });

          $scope.openPopover = function($event) {
            $scope.popover.show($event);
          };
          $scope.closePopover = function() {
            $scope.popover.hide();
          };
        //popover close 
                             
            
            $scope.baseURL = baseURL;
            
            $scope.dish = dish;
                     
          
            
            $scope.showPopOver = function($event) {
                
                $scope.popover.show($event);
            };    
            
            $scope.addFavorite = function() {
                
                favoriteFactory.addToFavorites($scope.dish.id);
                
                $scope.popover.hide();
                
                $ionicPlatform.ready(function () {
                $cordovaLocalNotification.schedule({
                // jsObj here input into schedula methord    
                    id: 1,
                    title: "Added Favorite",
                    text: $scope.dish.name
                }).then(function () {
                    console.log('Added Favorite '+$scope.dish.name);
                },
                function () {
                    console.log('Failed to add Notification ');
                });
                // toast function show methord
                $cordovaToast
                  .show('Added Favorite '+$scope.dish.name, 'long', 'center')
                  .then(function (success) {
                      // success
                  }, function (error) {
                      // error
                  });
        });//close to $ionicPlatform ready
            };//close to addtofav
            
                            
                                 
            // Create the Dish Comment modal that we will use later
            $ionicModal.fromTemplateUrl('templates/dish-comment.html', {
                    scope: $scope
                }).then(function(modal) {
                    $scope.commentForm = modal;
            });
            
            // Triggered the Comment modal to close
            $scope.closeComment = function() {
                $scope.commentForm.hide();
            };
  
            // Open the Comment modal
            $scope.addComment = function() {
            
                $scope.popover.hide();
                
                $scope.commentForm.show();
            };
            
            $scope.mycomment = {rating:"", comment:"", author:"", date:""};
            
            // Add the comment to database when the user submits the form
            $scope.doComment = function() {
                               
                $scope.mycomment.date = new Date().toISOString();
                                
                $scope.dish.comments.push($scope.mycomment);
                
                menuFactory.update({id:$scope.dish.id},$scope.dish);
                
                $scope.mycomment = {rating:"", comment:"", author:"", date:""};
                $scope.commentForm.hide();
                $scope.closeModal();
                $scope.closePopover();
             };
        }])

.controller('DishCommentController', ['$scope', 'menuFactory', function($scope,menuFactory) {
            
            $scope.mycomment = {rating:5, comment:"", author:"", date:""};
            
            $scope.submitComment = function () {
                
                $scope.mycomment.date = new Date().toISOString();
                console.log($scope.mycomment);
                
                $scope.dish.comments.push($scope.mycomment);
                menuFactory.update({id:$scope.dish.id},$scope.dish);
                
                $scope.commentForm.$setPristine();
                
                $scope.mycomment = {rating:5, comment:"", author:"", date:""};
            }
        }])

.controller('IndexController', ['$scope', 'dish', 'promotion', 'leader', 'baseURL', function($scope, dish, promotion, leader, baseURL) {
                        
            $scope.baseURL = baseURL;
                        
            $scope.dish = dish;
            $scope.promotion = promotion;
            $scope.leader = leader;

        }])

.controller('AboutController', ['$scope', 'leaders', 'baseURL', function($scope, leaders, baseURL) {
            
            $scope.baseURL = baseURL;
                    
            $scope.leaders = leaders;
            
        }])

.filter('favoriteFilter', function(){

        return function (dishes, favorites) {
            var out = [];
            for (var i = 0; i < favorites.length; i++) {
                for (var j = 0; j < dishes.length; j++) {
                    if (dishes[j].id === favorites[i].id) {
                        out.push(dishes[j]);
                        break;
                    }
                }
            }
            return out;
        }
    })

;