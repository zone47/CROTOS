var app = angular.module('artworkApp', ['ui.bootstrap']);

app.factory('api', function($http) {
  var API_ENDPOINT = 'https://www.wikidata.org/w/api.php';

  return {
    entity : function(id) {
      var params = {
        'action'    : 'wbgetentities',
        'ids'       : id,
        'format'    : 'json',
        'callback'  : 'JSON_CALLBACK',
        //'languages' : 'en|fr',
        'props'     : 'labels|descriptions|aliases|claims'
      };
      return $http.jsonp(API_ENDPOINT, { params : params });
    },
    search : function(q, lang) {
      var params = {
        'action' : 'wbsearchentities',
        'search' : q,
        'format' : 'json',
        'language' : lang,
		'uselang' : lang,
        'type' : 'item',
        'callback' : 'JSON_CALLBACK'
      };
      return $http.jsonp(API_ENDPOINT, { params : params });
    }
  };
});

app.controller('artworkController', function($scope, api, $http) {
	function lang_textModel() {
		this.lang  = lg;
    this.text  = '';
    this.index = 0;
	}
	
	function itemModel() {
		this.text        = '';
    	this.description = '';
	    this.wikidata    = '';
		this.wd          = '';
	    this.index       = 0;
  }
  
  function textModel() {
  	this.text  = '';
  	this.index = 0;
  }
  
  function dateModel() {
  	this.text  = '';
  	this.index = 0;
  }

  $scope.dataModel = {
   	label:       [ new lang_textModel() ],
   	description: [ new lang_textModel() ],
   	alias:       [ new lang_textModel() ],
   	instance:    [ new itemModel() ],
   	inception:   [ new dateModel() ],
   	creator:     [ new itemModel() ],
   	mat1:        [
   								{text:'canvas',    value:false, surface:true,  wikidata:'Q4259259', index:0 },
   								{text:'ink',       value:false, surface:false, wikidata:'Q127418',  index:1 },
   								{text:'oil paint', value:false, surface:false, wikidata:'Q296955',  index:2 },
   								{text:'paper',     value:false, surface:false, wikidata:'Q11472',   index:3 },
   								{text:'wood',      value:false, surface:false, wikidata:'Q287',     index:4 },
   							 ],
   	mat2:        [ new itemModel() ],
   	collection:  [ new itemModel() ],
   	inventory:   [ new textModel() ],
   	genre:  		 [ new itemModel() ],
   	subject:     [ new itemModel() ],
   	commons:     [ new textModel() ],
   	image:       [ new textModel() ]
	};

	//$scope.inventory[0].collection = true;

	$scope.qsTextAreaModel = {
	  text: ''
	};
    
	$scope.add_item = function (type, property) {
		angular.forEach($scope.dataModel[type], function (type) {
			if (type.wikidata)
				$scope.qsTextAreaModel.text += "LAST\t" + property + "\t" + type.wikidata + "\n";
		});
	}

	$scope.add_date = function (v, property) {
		angular.forEach($scope.dataModel[v], function (date) {
			value = date.value;
			if (value) {
				if (!isNaN(value)) {
					if (!date.precision || date.precision==11)
						date.precision = 9;
					while (value.length < 4)
						value = '0' + value;
					$scope.qsTextAreaModel.text += "LAST\t" + property + "\t+0000000" + value + "-01-01T00:00:00Z/" + date.precision + "\n";
				} else {
					d = value.split("-");
					if(!isNaN(d[0]) && !isNaN(d[1]) && !isNaN(d[2]) && d[1]>=1 && d[1]<=12 && d[2]>=1 && d[2]<31) {
		  	  	if (!date.precision || date.precision != 11)
	  	  	  	date.precision = 11;
						$scope.qsTextAreaModel.text += "LAST\t" + property + "\t+0000000" + value + "T00:00:00Z/" + date.precision + "\n";
					}
				}
			}
		});
	}
	
	$scope.add_text_lang = function (text, property) {
		angular.forEach($scope.dataModel[text], function (label) {
			if(label.text)
				$scope.qsTextAreaModel.text += "LAST\t" + property + label.lang + "\t\"" + label.text + "\"\n";
		});
	}
	
	$scope.add_text = function (text, property) {
		angular.forEach($scope.dataModel[text], function (label) {
			if(label.text)
				$scope.qsTextAreaModel.text += "LAST\t" + property + "\t\"" + label.text + "\"\n";
		});
	}
	
	$scope.add_materials = function () {
		angular.forEach($scope.dataModel.mat1, function (material) {
			if(material.value) {
				$scope.qsTextAreaModel.text += "LAST\tP186\t" + material.wikidata;
				if (material.surface)
					$scope.qsTextAreaModel.text += "\tP518\tQ861259";
				$scope.qsTextAreaModel.text += "\n";
			}
		});
		angular.forEach($scope.dataModel.mat2, function (material) {
			if(material.wikidata) {
				$scope.qsTextAreaModel.text += "LAST\tP186\t" + material.wikidata;
				if (material.surface)
					$scope.qsTextAreaModel.text += "\tP518\tQ861259";
				$scope.qsTextAreaModel.text += "\n";
			}
		});
	}
	
	$scope.add_collection = function () {
		angular.forEach($scope.dataModel.collection, function (collection) {
			if(collection.wikidata) {
				checks="";
				var crits=[0,31,1,2,170,571,186,195,217,276,179,3,973,727,347,1212,214,350,18,373];
				for (var i=0; i<crits.length; i++) {
					if ($('#c'+crits[i]).prop('checked'))
						checks+="&c"+crits[i]+"=1";
				}
				$("#btn_search").prop('disabled', true);
				document.location.href="?q="+collection.wikidata+"&p="+$("#props").val()+"&l="+$("#lg").val()+checks;
			}
		});
	}

	$scope.add_inventory = function () {
		angular.forEach($scope.dataModel.inventory, function (inventory) {
			if (inventory.text) {
				$scope.qsTextAreaModel.text += "LAST\tP217\t\"" + inventory.text + "\"";
				if (inventory.collection)
					angular.forEach($scope.dataModel.collection, function (collection) {
						if(collection.wikidata)
							$scope.qsTextAreaModel.text += "\tP195\t" + collection.wikidata;
				});
				$scope.qsTextAreaModel.text += "\n";
			}
		});
	}
	
	$scope.add_instance = function (instance) {
		if (instance.option) {
			switch(instance.option) {
				case "drawing":
					$scope.qsTextAreaModel.text += "LAST\tP31\tQ93184\n";
      	 	break;
        case "painting":
        	$scope.qsTextAreaModel.text += "LAST\tP31\tQ3305213\n";
	       	break;
        case "sculpture":
        	$scope.qsTextAreaModel.text += "LAST\tP31\tQ860861\n";
	       	break;
  	    default:
    	  	$scope.qsTextAreaModel.text += "LAST\tP31\t" + instance.wikidata+ "\n";
			}
		}
	}
	

	$scope.change = function () {
		$init_cmd = 'LAST';
		$scope.qsTextAreaModel.text = "CREATE\n";
	
	  $scope.add_text_lang ("label",       "L");
	  $scope.add_text_lang ("description", "D");
	  $scope.add_text_lang ("alias",       "A");
	  
	  //$scope.add_instance  ($scope.dataModel.instance[0]);
	  $scope.add_item      ("creator", 		 "P170");
	  $scope.add_date			 ("inception", 	 "P571");
	  $scope.add_materials ();
	  $scope.add_collection();
	  $scope.add_inventory ();
	  $scope.add_item      ("genre",       "P136");
	  $scope.add_item      ("subject",     "P180");
	  $scope.add_text      ("commons",     "P373");
	  $scope.add_text      ("image",       "P18");
	}
	
	$scope.suggestWikidata = function(val, i) {
	  $scope.loading = true;
	   return api.search(val, lg).then(function(res){
	    var labels = [];
	    angular.forEach(res.data.search, function(item){
	      if (item.description === undefined)
	        item.description = '<i>aucune description</i>';
	      labels.push({
	        label:       item.label,
	        id:          item.id,
	        description: item.description,
	        display:     item.label + "<br>" + item.description,
	        index:       i
	        });
	    });
	    $scope.loading = false;
	    return labels;
	  });
	};

  $scope.onSelectLine = function($type, $item) {
     $item.display = $item.label;
     angular.forEach($scope.dataModel[$type], function(data) {
      if (data.index == $item.index) {
      	if ($item.description === "<i>aucune description</i>")
	        $item.description = "aucune description";
        $scope.dataModel[$type][$item.index].text        = $item.label;
        $scope.dataModel[$type][$item.index].description = $item.description;
        $scope.dataModel[$type][$item.index].wikidata    = $item.id;
		$scope.dataModel[$type][$item.index].wd          = $item.id.replace("Q","");
		$scope.dataModel[$type][$item.index].option      = "other";
       }
     });
    $scope.change();
  }
  
  $scope.addLine = function($type) {
  	var newSubject = {
  	  text:     '',
  	  index:    $scope.dataModel[$type].length
  	};
  	$scope.dataModel[$type].push(newSubject);
  	$scope.change();
  }

	$scope.removeLine = function($type, $index) {
  	$scope.dataModel[$type].splice($index,1);
  	var i=0;
    for (var i=0; i<$scope.dataModel[$type].length; i++)
      $scope.dataModel[$type][i].index = i;
    $scope.change();
  }
});
