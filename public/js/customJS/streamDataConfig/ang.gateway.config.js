var gatewayConfigClass = angular.module('GatewayConfigApp', []);
gatewayConfigClass.controller('GatewayConfigCtrl', function($scope, $http) {
    $scope.sensors = [];
    $scope.streamingLogMode = 'File';
    $scope.input = {
        fileLog: "",
        dateLog: ""
    };
    $scope.setLogMode = function(mode, element) {
        $scope.streamingLogMode = mode;        
        setTimeout(function() {
            clearCodeEditor();
            setCodeEditor();
            saveFormData();
        }, 150);
    }

    
    if (!String.prototype.trim) {
        String.prototype.trim = function() {
            return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
        };
    }

    function initSensors() {
        if ($scope.sensors.length == 0) {
            var _sensor = {
                id: 1,
                code: ''
            };
            $scope.sensors.push(_sensor);
        }
    }
    initSensors();
    $scope.addSensor = function() {
        var item = {};
        var id = $scope.sensors.length + 1;
        item.id = id;
        item.code = '';
        $scope.sensors.push(item);
        setTimeout(function() {
            clearCodeEditor();
            setCodeEditor();
        }, 150);
    }
    $scope.delSensor = function(item) {
        if (item != null && $scope.sensors.length > 0) {
            var index = $scope.sensors.indexOf(item);
            $scope.sensors.splice(index, 1);
        }
    }
    $scope.getSensorData = function() {
        if ($scope.sensors != null && $scope.sensors.length > 0) {
            var result = new Array();
            for (var i = 0; i < $scope.sensors.length; i++) {
                result.push($scope.sensors[i].id);
            }
            jQuery("#sensorList").val(result.join());
        }
    }

    function _getCookie(c_name) {
        if (document.cookie.length > 0) {
            c_start = document.cookie.indexOf(c_name + "=");
            if (c_start != -1) {
                c_start = c_start + c_name.length + 1;
                c_end = document.cookie.indexOf(";", c_start);
                if (c_end == -1) {
                    c_end = document.cookie.length;
                }
                return unescape(document.cookie.substring(c_start, c_end));
            }
        }
        return "";
    }
    $scope.getCookieSetting = function(name) {
        var input = _getCookie(name);
        $scope.input = {};
        $scope.input.fileLog = "";
        $scope.input.dateLog = "";
        if (input && input.length > 0 ) {
            $scope.input = JSON.parse(input);
            $scope.input.fileLog = $scope.input.fileLog.trim();
            $scope.input.dateLog = $scope.input.dateLog.trim();
        }
        
        $scope.$digest();
    }

    function _setCookie(cname, cvalue, minutes) {
        deleteCookie(cname);
        var d = new Date();
        d.setTime(d.getTime() + (minutes * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function _deleteCookie(cname) {
        var d = new Date(); //Create an date object
        d.setTime(d.getTime() - (1000 * 60 * 60 * 24 * 2)); //Set the time to the past. 1000 milliseonds = 1 second
        var expires = "expires=" + d.toGMTString(); //Compose the expirartion date
        window.document.cookie = cname + "=" + "; " + expires; //Set the cookie with name and the expiration date
    }
})