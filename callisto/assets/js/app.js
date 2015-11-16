var map, featureList, depictSearch = [], museumSearch = [], artworkSearch = [];

function isInt(value) {
 var x;
 return isNaN(value) ? !1 : (x = parseFloat(value), (0 | x) === x);
}

$(window).resize(function() {
  sizeLayerControl();
});

$(document).on("click", ".feature-row", function(e) {
  $(document).off("mouseout", ".feature-row", clearHighlight);
  sidebarClick(parseInt($(this).attr("id"), 10));
});

$(document).on("mouseover", ".feature-row", function(e) {
  highlight.clearLayers().addLayer(L.circleMarker([$(this).attr("lat"), $(this).attr("lng")], highlightStyle));
});

$(document).on("mouseout", ".feature-row", clearHighlight);

$("#about-btn").click(function() {
  $("#aboutModal").modal("show");
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#full-extent-btn").click(function() {
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#legend-btn").click(function() {
  $("#legendModal").modal("show");
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#login-btn").click(function() {
  $("#loginModal").modal("show");
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#list-btn").click(function() {
  $('#sidebar').toggle();
  map.invalidateSize();
  return false;
});

$("#nav-btn").click(function() {
  $(".navbar-collapse").collapse("toggle");
  return false;
});

$("#sidebar-toggle-btn").click(function() {
  $("#sidebar").toggle();
  map.invalidateSize();
  return false;
});

$("#sidebar-hide-btn").click(function() {
  $('#sidebar').hide();
  map.invalidateSize();
});

function sizeLayerControl() {
  $(".leaflet-control-layers").css("max-height", $("#map").height() - 50);
}

function clearHighlight() {
  highlight.clearLayers();
}

function sidebarClick(id) {
  var layer = markerClusters.getLayer(id);
  map.setView([layer.getLatLng().lat, layer.getLatLng().lng], 17);
  layer.fire("click");
  /* Hide sidebar and go to the map on small screens */
  if (document.body.clientWidth <= 767) {
    $("#sidebar").hide();
    map.invalidateSize();
  }
}

function syncSidebar() {
  /* Empty sidebar features */
  $("#feature-list tbody").empty();

  /* Loop through museums layer and add only features which are in the map bounds */
  depicts.eachLayer(function (layer) {
    if (map.hasLayer(depictLayer)) {
      if (map.getBounds().contains(layer.getLatLng())) {
		$("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="assets/img/depicts.png"></td><td>'+ layer.feature.properties.l + ' <span class="nb">(<span class="feature-name depicts">' + layer.feature.properties.n + '</span>)</span></td><td style="vertical-align: middle;"><a href="/crotos/?p180=' + layer.feature.properties.q + '"><img src="assets/img/crotos_ico.png" alt="" /></a></td></tr>');
      }
    }
  });
  museums.eachLayer(function (layer) {
    if (map.hasLayer(museumLayer)) {
      if (map.getBounds().contains(layer.getLatLng())) {
        $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="assets/img/museum.png"></td><td>'+ layer.feature.properties.l + ' <span class="nb">(<span class="feature-name">' + layer.feature.properties.n + '</span>)</span></td><td style="vertical-align: middle;"><a href="/crotos/?p195=' + layer.feature.properties.q + '"><img src="assets/img/crotos_ico.png" alt="" /></a></td></tr>');
      }
    }
  });
  artworks.eachLayer(function (layer) {
    if (map.hasLayer(artworkLayer)) {
      if (map.getBounds().contains(layer.getLatLng())) {
        $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="assets/img/artwork.png"></td><td class="feature-name">'+ layer.feature.properties.l + '</td><td style="vertical-align: middle;"><a href="http://tools.wmflabs.org/reasonator/?lang=' + l + '&q=' + layer.feature.properties.q + '"><img src="assets/img/reas_ico.png" alt="" /></a></td></tr>');
      }
    }
  });
  /* Update list.js featureList */
  featureList = new List("features", {
    valueNames:["feature-name"]
  });
  var toto=featureList.get("features")[0];
  var typedata;
  for(var key in toto) {
	 typedata=toto[key]["feature-name"];
	 break;
  }
  if (isInt(typedata)){
  featureList.sort("feature-name", {
    order: "desc"
  });
  }
  else{
  featureList.sort("feature-name", {
    order: "asc"
  });
  }
}

/* Basemap Layers */
var mapquestOSM = L.tileLayer("http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png", {
  maxZoom: 19,
  subdomains: ["otile1", "otile2", "otile3", "otile4"],
  attribution: 'Tiles courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png">. Map data (c) <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, CC-BY-SA.'
});


/* Overlay Layers */
var highlight = L.geoJson(null);
var highlightStyle = {
  stroke: false,
  fillColor: "#00FFFF",
  fillOpacity: 0.7,
  radius: 10
};



/* Single marker cluster layer to hold all clusters */
var markerClusters = new L.MarkerClusterGroup({
  spiderfyOnMaxZoom: true,
  showCoverageOnHover: false,
  zoomToBoundsOnClick: true,
  disableClusteringAtZoom: 9
});
/* Empty layer placeholder to add to layer control for listening when to add/remove museums to markerClusters layer */
var depictLayer = L.geoJson(null);
var depicts = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "assets/img/depicts.png",
        iconSize: [24, 28],
        iconAnchor: [12, 28],
        popupAnchor: [0, -25]
      }),
      title: feature.properties.l,
      riseOnHover: true
    });
  },
  onEachFeature: function (feature, layer) {
    if (feature.properties) {
      var content = "<table class='table table-striped table-bordered table-condensed snipet'>" + "<tr><td><a href=\"http://tools.wmflabs.org/reasonator/?lang=" + l + "&q=" + layer.feature.properties.q + "\">" + layer.feature.properties.l + "</a> <a href=\"http://tools.wmflabs.org/reasonator/?lang=" + l + "&q=" + layer.feature.properties.q + "\"><img src=\"assets/img/reas_ico.png\" alt=\"\" /></a></td></tr>";
	  if (feature.properties.t!=""){
	    content = content + "<tr><td><a href=\"/crotos/?p180=" + layer.feature.properties.q + "\"><img src=\"https://upload.wikimedia.org/wikipedia/commons/" + layer.feature.properties.t + "\" /></a>";
		content = content + " <a href=\"https://commons.wikimedia.org/wiki/File:" + layer.feature.properties.i + "\"><img src=\"assets/img/commons_ico.png\" class=\"cms_ico\"/></a></td></tr>";
		
	  }
      content = content + "<tr><td><a href=\"/crotos/?p180=" + layer.feature.properties.q + "\">" + feature.properties.n + " ";
	  if (feature.properties.n==1)
	    content = content + result;
	  else
	    content = content + results;
	  content = content + "</a> <a href=\"/crotos/?p180=" + layer.feature.properties.q + "\"><img src=\"assets/img/crotos_ico.png\" alt=\"\" /></a></td></tr>";
	  content = content + "<table>";
      layer.on({
        click: function (e) {
          $("#feature-title").html(feature.properties.l);
          $("#feature-info").html(content);
          $("#featureModal").modal("show");
          highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
        }
      });
	 $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="assets/img/depicts.png"></td><td>'+ layer.feature.properties.l + ' <span class="nb">(<span class="feature-name depicts">' + layer.feature.properties.n + '</span>)</span></td><td style="vertical-align: middle;"><a href="/crotos/?p180=' + layer.feature.properties.q + '"><img src="assets/img/crotos_ico.png" alt="" /></a></td></tr>');
      depictSearch.push({
        name: layer.feature.properties.l,
        source: depicts,
        id: L.stamp(layer),
        lat: layer.feature.geometry.coordinates[1],
        lng: layer.feature.geometry.coordinates[0]
      });
    }
  }
});
$.getJSON("data/depicts_fr.geojson", function (data) {
  depicts.addData(data);
  map.addLayer(depictLayer);
});

/* Empty layer placeholder to add to layer control for listening when to add/remove museums to markerClusters layer */
var museumLayer = L.geoJson(null);
var museums = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "assets/img/museum.png",
        iconSize: [24, 28],
        iconAnchor: [12, 28],
        popupAnchor: [0, -25]
      }),
      title: feature.properties.l,
      riseOnHover: true
    });
  },
  onEachFeature: function (feature, layer) {
    if (feature.properties) {
	  var content = "<table class='table table-striped table-bordered table-condensed snipet'>" + "<tr><td><a href=\"http://tools.wmflabs.org/reasonator/?lang=" + l + "&q=" + layer.feature.properties.q + "\">" + layer.feature.properties.l + "</a> <a href=\"http://tools.wmflabs.org/reasonator/?lang=" + l + "&q=" + layer.feature.properties.q + "\"><img src=\"assets/img/reas_ico.png\" alt=\"\" /></a></td></tr>";
	  if (feature.properties.t!=""){
	    content = content + "<tr><td><a href=\"/crotos/?p195=" + layer.feature.properties.q + "\"><img src=\"https://upload.wikimedia.org/wikipedia/commons/" + layer.feature.properties.t + "\" /></a>";
		content = content + " <a href=\"https://commons.wikimedia.org/wiki/File:" + layer.feature.properties.i + "\"><img src=\"assets/img/commons_ico.png\" class=\"cms_ico\"/></a></td></tr>";
		
	  }
      content = content + "<tr><td><a href=\"/crotos/?p195=" + layer.feature.properties.q + "\">" + feature.properties.n + " ";
	  if (feature.properties.n==1)
	    content = content + result;
	  else
	    content = content + results;
	  content = content + "</a> <a href=\"/crotos/?p195=" + layer.feature.properties.q + "\"><img src=\"assets/img/crotos_ico.png\" alt=\"\" /></a></td></tr>";
	  if (feature.properties.u!="")
	    content = content + "<tr><td><a class='url-break' href='" + feature.properties.u + "' target='_blank'>" + feature.properties.u + "</a></td></tr>";
	  content = content + "<table>";
      layer.on({
        click: function (e) {
          $("#feature-title").html(feature.properties.l);
          $("#feature-info").html(content);
          $("#featureModal").modal("show");
          highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
        }
      });
	 $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="assets/img/museum.png"></td><td>'+ layer.feature.properties.l + ' <span class="nb">(<span class="feature-name">' + layer.feature.properties.n + '</span>)</span></td><td style="vertical-align: middle;"><a href="/crotos/?p195=' + layer.feature.properties.q + '"><img src="assets/img/crotos_ico.png" alt="" /></a></td></tr>');
      museumSearch.push({
        name: layer.feature.properties.l,
        source: "Museums",
        id: L.stamp(layer),
        lat: layer.feature.geometry.coordinates[1],
        lng: layer.feature.geometry.coordinates[0]
      });
    }
  }
});
$.getJSON("data/museums_fr.geojson", function (data) {
  museums.addData(data);
});

/* Empty layer placeholder to add to layer control for listening when to add/remove museums to markerClusters layer */
var artworkLayer = L.geoJson(null);
var artworks = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "assets/img/artwork.png",
        iconSize: [24, 28],
        iconAnchor: [12, 28],
        popupAnchor: [0, -25]
      }),
      title: feature.properties.l,
      riseOnHover: true
    });
  },
  onEachFeature: function (feature, layer) {
    if (feature.properties) {
	  var content = "<table class='table table-striped table-bordered table-condensed snipet'>" + "<tr><td><a href=\"http://tools.wmflabs.org/reasonator/?lang=" + l + "&q=" + layer.feature.properties.q + "\">" + layer.feature.properties.l + "</a> <a href=\"http://tools.wmflabs.org/reasonator/?lang=" + l + "&q=" + layer.feature.properties.q + "\"><img src=\"assets/img/reas_ico.png\" alt=\"\" /></a></td></tr>";
	  if (feature.properties.t!=""){
	    content = content + "<tr><td><a href=\"http://tools.wmflabs.org/reasonator/?lang=" + l + "&q=" + layer.feature.properties.q + "\"><img src=\"https://upload.wikimedia.org/wikipedia/commons/" + layer.feature.properties.t + "\" /></a>";
		content = content + " <a href=\"https://commons.wikimedia.org/wiki/File:" + layer.feature.properties.i + "\"><img src=\"assets/img/commons_ico.png\" class=\"cms_ico\"/></a></td></tr>";
		
	  }
	  if (feature.properties.c!="")
        content = content + "<tr><td>" + feature.properties.c + "</td></tr>";
	  content = content + "<table>";
      layer.on({
        click: function (e) {
          $("#feature-title").html(feature.properties.l);
          $("#feature-info").html(content);
          $("#featureModal").modal("show");
          highlight.clearLayers().addLayer(L.circleMarker([feature.geometry.coordinates[1], feature.geometry.coordinates[0]], highlightStyle));
        }
      });
	 $("#feature-list tbody").append('<tr class="feature-row" id="' + L.stamp(layer) + '" lat="' + layer.getLatLng().lat + '" lng="' + layer.getLatLng().lng + '"><td style="vertical-align: middle;"><img width="16" height="18" src="assets/img/artwork.png"></td><td class="feature-name">'+ layer.feature.properties.l + '</td><td style="vertical-align: middle;"><a href="http://tools.wmflabs.org/reasonator/?lang=' + l + '&q=' + layer.feature.properties.q + '"><img src="assets/img/reas_ico.png" alt="" /></a></td></tr>');
      artworkSearch.push({
        name: layer.feature.properties.l,
        source: "Artworks",
        id: L.stamp(layer),
        lat: layer.feature.geometry.coordinates[1],
        lng: layer.feature.geometry.coordinates[0]
      });
    }
  }
});
$.getJSON("data/artworks_fr.geojson", function (data) {
  artworks.addData(data);
});


map = L.map("map", {
  zoom: 3,
  center: [20.0, 0.0],
  layers: [mapquestOSM, markerClusters, highlight],
  zoomControl: false,
  attributionControl: false
});

/* Layer control listeners that allow for a single markerClusters layer */
map.on("overlayadd", function(e) {
  if (e.layer === depictLayer) {
    markerClusters.addLayer(depicts);
    syncSidebar();
  }	
  if (e.layer === museumLayer) {
    markerClusters.addLayer(museums);
    syncSidebar();
  }
  if (e.layer === artworkLayer) {
    markerClusters.addLayer(artworks);
    syncSidebar();
  }
});

map.on("overlayremove", function(e) {
  if (e.layer === depictLayer) {
    markerClusters.removeLayer(depicts);
    syncSidebar();
  }
  if (e.layer === museumLayer) {
    markerClusters.removeLayer(museums);
    syncSidebar();
  }
  if (e.layer === artworkLayer) {
    markerClusters.removeLayer(artworks);
    syncSidebar();
  }
});

/* Filter sidebar feature list to only show features in current map bounds */
map.on("moveend", function (e) {
  syncSidebar();
});

/* Clear feature highlight when map is clicked */
map.on("click", function(e) {
  highlight.clearLayers();
});

/* Attribution control */
function updateAttribution(e) {
  $.each(map._layers, function(index, layer) {
    if (layer.getAttribution) {
      $("#attribution").html((layer.getAttribution()));
    }
  });
}
map.on("layeradd", updateAttribution);
map.on("layerremove", updateAttribution);

var attributionControl = L.control({
  position: "bottomright"
});
attributionControl.onAdd = function (map) {
  var div = L.DomUtil.create("div", "leaflet-control-attribution");
  div.innerHTML = "<a href='#' onclick='$(\"#attributionModal\").modal(\"show\"); return false;'>Blah</a>";
  return div;
};
map.addControl(attributionControl);

var zoomControl = L.control.zoom({
  position: "bottomright"
}).addTo(map);

/* GPS enabled geolocation control set to follow the user's location */
var locateControl = L.control.locate({
  position: "bottomright",
  drawCircle: true,
  follow: true,
  setView: true,
  keepCurrentZoomLevel: true,
  markerStyle: {
    weight: 1,
    opacity: 0.8,
    fillOpacity: 0.8
  },
  circleStyle: {
    weight: 1,
    clickable: false
  },
  icon: "fa fa-location-arrow",
  metric: false,
  strings: {
    title: "My location",
    popup: "You are within {distance} {unit} from this point",
    outsideMapBoundsMsg: "You seem located outside the boundaries of the map"
  },
  locateOptions: {
    maxZoom: 18,
    watch: true,
    enableHighAccuracy: true,
    maximumAge: 10000,
    timeout: 10000
  }
}).addTo(map);

/* Larger screens get expanded layer control and visible sidebar */
if (document.body.clientWidth <= 767) {
  var isCollapsed = true;
} else {
  var isCollapsed = false;
}

var baseLayers = {
  "Street Map": mapquestOSM
};

var groupedOverlays = {
  " ": {
	"<img src='assets/img/depicts.png' width='24' height='28'>&nbsp;Depicts": depictLayer,
    "<img src='assets/img/museum.png' width='24' height='28'>&nbsp;Museums": museumLayer,
    "<img src='assets/img/artwork.png' width='24' height='28'>&nbsp;Artworks": artworkLayer
  }
};

var layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
  collapsed: isCollapsed
}).addTo(map);

/* Highlight search box text on click */
$("#searchbox").click(function () {
  $(this).select();
});

/* Prevent hitting enter from refreshing the page */
$("#searchbox").keypress(function (e) {
  if (e.which == 13) {
    e.preventDefault();
  }
});

$("#featureModal").on("hidden.bs.modal", function (e) {
  $(document).on("mouseout", ".feature-row", clearHighlight);
});

/* Typeahead search functionality */
$(document).one("ajaxStop", function () {
  $("#loading").hide();
  sizeLayerControl();
  featureList = new List("features", {valueNames:["depicts"]});
  featureList.sort("depicts", {order:"desc"});
  
  var depictsBH = new Bloodhound({
    name: depicts,
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: depictSearch,
    limit: 10
  });
  	
  var museumsBH = new Bloodhound({
    name: "Museums",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: museumSearch,
    limit: 10
  });
  
  var artworksBH = new Bloodhound({
    name: "Artworks",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: artworkSearch,
    limit: 10
  });

  depictsBH.initialize();
  museumsBH.initialize();
  artworksBH.initialize();
  
  /* instantiate the typeahead UI */
  $("#searchbox").typeahead({
    minLength: 3,
    highlight: true,
    hint: false
  }, {
    name: depicts,
    displayKey: "name",
    source: depictsBH.ttAdapter()
  }, {
    name: "Museums",
    displayKey: "name",
    source: museumsBH.ttAdapter(),
    templates: {
      header: "<h4 class='typeahead-header'><img src='assets/img/museum.png' width='24' height='28'>&nbsp;Museums</h4>",
      suggestion: Handlebars.compile(["{{name}}<br>&nbsp;<small>{{address}}</small>"].join(""))
    }
  }, {
    name: "Artworks",
    displayKey: "name",
    source: artworksBH.ttAdapter()
  }).on("typeahead:selected", function (obj, datum) {
    if (datum.source === depicts) {
      if (!map.hasLayer(depictLayer)) {
        map.addLayer(depictLayer);
      }
      map.setView([datum.lat, datum.lng], 17);
      if (map._layers[datum.id]) {
        map._layers[datum.id].fire("click");
      }
    }
	
	if (datum.source === "Museums") {
      if (!map.hasLayer(museumLayer)) {
        map.addLayer(museumLayer);
      }
      map.setView([datum.lat, datum.lng], 17);
      if (map._layers[datum.id]) {
        map._layers[datum.id].fire("click");
      }
    }
	
	if (datum.source === "Artworks") {
      if (!map.hasLayer(artworkLayer)) {
        map.addLayer(artworkLayer);
      }
      map.setView([datum.lat, datum.lng], 17);
      if (map._layers[datum.id]) {
        map._layers[datum.id].fire("click");
      }
    }
	
    if ($(".navbar-collapse").height() > 50) {
      $(".navbar-collapse").collapse("hide");
    }
  }).on("typeahead:opened", function () {
    $(".navbar-collapse.in").css("max-height", $(document).height() - $(".navbar-header").height());
    $(".navbar-collapse.in").css("height", $(document).height() - $(".navbar-header").height());
  }).on("typeahead:closed", function () {
    $(".navbar-collapse.in").css("max-height", "");
    $(".navbar-collapse.in").css("height", "");
  });
  $(".twitter-typeahead").css("position", "static");
  $(".twitter-typeahead").css("display", "block");
});

// Leaflet patch to make layer control scrollable on touch browsers
var container = $(".leaflet-control-layers")[0];
if (!L.Browser.touch) {
  L.DomEvent
  .disableClickPropagation(container)
  .disableScrollPropagation(container);
} else {
  L.DomEvent.disableClickPropagation(container);
}
