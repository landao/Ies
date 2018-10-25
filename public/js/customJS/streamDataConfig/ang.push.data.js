var StreamConfigClass = angular.module('StreamConfigApp', []);
StreamConfigClass.controller('StreamConfigCtrl', function($scope, $http) {
    $scope.sensors = [];
    $scope.input = {};
    $scope.streamingLogMode = 'File';
    $scope.setLogMode = function(mode, element) {
        $scope.streamingLogMode = mode;
        setTimeout(function() {
            clearCodeEditor();
            setCodeEditor();
        }, 150);
    }
    if (!String.prototype.trim) {
        String.prototype.trim = function() {
            return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
        };
    }

    function initSensors() {
        $scope.sensors = [];
        if ($scope.sensors.length == 0) {
            var _sensor = {
                id: 1,
                code: ''
            };
            $scope.sensors.push(_sensor);
        }
    }
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
            var _sensor = {};
            var _sensors = [];

            for (var i =0; i < $scope.sensors.length; i++){
                  _sensor = {};
                  _sensor.id = (i+1);
                  _sensor.code = $scope.sensors[i].code;
                  _sensors.push(_sensor);  
            }

            $scope.sensors = _sensors;

        }
        setTimeout(function() {
            clearCodeEditor();
            setCodeEditor();
        }, 150);
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
    String.prototype.replaceAll = function(search, replacement) {
        var target = this;
        return target.split(search).join(replacement);
    };
    $scope.searchForData = function(term) {
        if (term && term.length > 0) {
            var _sensor = {};
            var _code = '';
            $scope.sensors = [];
            for (var i = 0; i < term.length; i++) {
                _code = term[i].replace('{', '');
                _code = _code.replace('}', '');
                _code = _code.replaceAll(',', ',\n');
                _code = _code.replaceAll("\\/", '/');
                _code = _code.replaceAll("_", "-");
                _sensor = {
                    id: (i + 1),
                    code: _code
                };
                $scope.sensors.push(_sensor);
            }
            $scope.$digest();
        } else {
            initSensors();
            $scope.$digest();
        }
    }
    $scope.searchInitialize = function() {
        initSensors();
        $scope.$digest();
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
    $scope.getCookieSetting2 = function(name) {
        var searchFor = _getCookie(name);
        $scope.sensors = [];
        var _sensor = {};
        var _item = '';
        var _code = '';
        if (searchFor && searchFor.length > 0) {
            var formData = JSON.parse(searchFor);
            if (formData && formData.length > 0) {
                for (var i = 0; i < formData.length; i++) {
                    _item = formData[i].trim();
                    _sensor = {
                        id: (i + 1),
                        code: _item
                    };
                    $scope.sensors.push(_sensor);
                }
                $scope.$digest();
                return formData.length;
            }
        }
        $scope.$digest();
        return 0;
    }
    $scope.getCookieSetting1 = function(name) {
        var input = _getCookie(name);
        $scope.input = {};
        if (input && input.length > 0) {
            $scope.input = JSON.parse(input);            
        } else if (name == 'streamSetting1') {
            $scope.input.filterName = "DefaultName";
            $scope.input.hostName = ""
            $scope.input.port = "";
            $scope.input.batchTime = 30;
        }
        else if (name == 'batchSetting1'){
            $scope.input.filterName = "DefaultName";
            $scope.input.batchData = "/home/DATA/TestBatch/LogData";
            $scope.input.savePath = "/home/DATA/TestBatch/LogSave";
        }
        /*setTimeout(function(){
            var slider = jQuery("#batchtime").data("ionRangeSlider");
            batchTimeUpdate($scope.input.batchTime);
        },100);*/
        $scope.$digest();
    }
})