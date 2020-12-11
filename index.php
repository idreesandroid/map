<?php

require_once('Medoo.php');

use Medoo\Medoo;

$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'milkman',
    'server' => 'localhost',
    'username' => 'root',
    'password' => ''
]);

?>


<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>  
    <script src="script.js"></script>  
	  <script async defer src="https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing,places&key=AIzaSyA4d_ChkEg7E_k9rU7zPt09FVPGKpL1aAE&v=3&callback=initializeMap"></script>
    <style type="text/css">
    html, body {
      padding: 0;
      margin: 0;
      height: 100%;
    }
    .map{
      height:90%;
      width: 100%;  
    }
    </style>
    <script>
    function initializeMap(){
        map = new google.maps.Map(document.getElementById('mapIn'), 
            { zoom: 12, 
              center: new google.maps.LatLng(30.3753, 69.3451)
            }),        
        shapes = [],
        selected_shape  = null,
        drawingManager = new google.maps.drawing.DrawingManager({map:map}),
        byId = function(elementIdAttribute){return document.getElementById(elementIdAttribute)},
        clearSelection  = function(){
                            if(selected_shape){
                              selected_shape.set((selected_shape.type === google.maps.drawing.OverlayType.MARKER
                                                 )?'draggable':'editable',false);
                              selected_shape = null;
                            }
                          },
        setSelection = function(shape){
                            clearSelection();
                            selected_shape=shape;
      selected_shape.set((selected_shape.type === google.maps.drawing.OverlayType.MARKER)?'draggable':'editable',true);
                          },
        clearShapes = function(){
                            for(var i=0;i<shapes.length;++i){
                              shapes[i].setMap(null);
                            }
                            shapes=[];
                          };    

    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        var shape  = e.overlay;
        shape.type = e.type;
        google.maps.event.addListener(shape, 'click', function() {
          setSelection(this);
        });
        setSelection(shape);
        shapes.push(shape);
      });

    google.maps.event.addListener(map, 'click',clearSelection);
    google.maps.event.addDomListener(byId('clear_shapes'), 'click', clearShapes);
  
    google.maps.event.addDomListener(byId('save_raw_map'), 'click', function(e){

    var data=MapFactory.InputMap(shapes,false);
    byId('data').value=JSON.stringify(data);
    var mapData = byId('data').value;
    var preventRunDefault = false;    
    jQuery.ajax({
            url: 'process.php',  
            data: {CompleteMapData: JSON.stringify(mapData) },
            type: 'post',
            dataType: "json"            
        });		  
  	});
    google.maps.event.addDomListener(byId('restore'), 'click', function(){
      if(this.shapes){
        for(var i=0;i<this.shapes.length;++i){
              this.shapes[i].setMap(null);
        }
      }
      this.shapes=MapFactory.OutputMap(JSON.parse(byId('data').value),map);
    });    
}


var MapFactory = {
  //returns array with storable google.maps.Overlay-definitions
  InputMap:function(arr,//array with google.maps.Overlays
              encoded//boolean indicating whether pathes should be stored encoded
              ){
      var shapes = [], shape, tmp;
      
      for(var i = 0; i < arr.length; i++)
      {   
        shape=arr[i];
        tmp={
          type:this.getShape(shape.type),
          id:shape.id||null
        };        
        
        switch(tmp.type){
           case 'CIRCLE':
              tmp.radius=shape.getRadius();
              tmp.geometry=this.getSeprateLatLng(shape.getCenter());
            break;
           case 'MARKER': 
              tmp.geometry=this.getSeprateLatLng(shape.getPosition());   
            break;  
           case 'RECTANGLE': 
              tmp.geometry=this.getSeprateLatLngBound(shape.getBounds()); 
             break;   
           case 'POLYLINE': 
              tmp.geometry=this.createPolylineParametersFromPathsIfEncoded(shape.getPath(),encoded);
             break;   
           case 'POLYGON': 
              tmp.geometry=this.createPolygonParametersFromPathsIfEncoded(shape.getPaths(),encoded);
              
             break;   
       }
       shapes.push(tmp);
    }

    return shapes;
  },
  //returns array with google.maps.Overlays
  OutputMap:function(arr,//array containg the stored shape-definitions
               map//map where to draw the shapes
               ){
      var shapes  = [], map=map||null, shape,tmp;
      
      for(var i = 0; i < arr.length; i++)
      {   
        shape=arr[i];       
        
        switch(shape.type){
           case 'CIRCLE':
              tmp=new google.maps.Circle({radius:Number(shape.radius),center:this.LatLngObj.apply(this,shape.geometry)});
            break;
           case 'MARKER': 
              tmp=new google.maps.Marker({
                position:this.LatLngObj.apply(this,shape.geometry)
              });
            break;  
           case 'RECTANGLE': 
              tmp=new google.maps.Rectangle({bounds:this.LatLngBoundsObj.apply(this,shape.geometry)});
             break;   
           case 'POLYLINE': 
              tmp=new google.maps.Polyline({path:this.createPolylineParametersFromPaths(shape.geometry)});
             break;   
           case 'POLYGON': 
              tmp=new google.maps.Polygon({paths:this.createPolygonParametersFromPaths(shape.geometry)});              
             break;   
       }
       tmp.setValues({map:map,id:shape.id})
       shapes.push(tmp);
    }
    return shapes;
  },
  createPolylineParametersFromPathsIfEncoded:function(path,e){
    path=(path.getArray)?path.getArray():path;
    if(e){
      return google.maps.geometry.encoding.encodePath(path);
    }else{
      var parameters=[];
      for(var i=0;i<path.length;++i){
        parameters.push(this.getSeprateLatLng(path[i]));
      }
      return parameters;
    }
  },
  createPolylineParametersFromPaths:function(path){
    if(typeof path==='string'){
      return google.maps.geometry.encoding.decodePath(path);
    }
    else{
      var parameters=[];
      for(var i=0;i<path.length;++i){
        parameters.push(this.LatLngObj.apply(this,path[i]));
      }
      return parameters;
    }
  },

  createPolygonParametersFromPathsIfEncoded:function(paths,e){
    var parameters=[];
    paths=(paths.getArray)?paths.getArray():paths;
    for(var i=0;i<paths.length;++i){
        parameters.push(this.createPolylineParametersFromPathsIfEncoded(paths[i],e));
      }
     return parameters;
  },
  createPolygonParametersFromPaths:function(paths){
    var parameters=[];
    for(var i=0;i<paths.length;++i){
        parameters.push(this.createPolylineParametersFromPaths.call(this,paths[i]));
        
      }
     return parameters;
  },
  getSeprateLatLng:function(latLng){
    return([latLng.lat(),latLng.lng()]);
  },
  LatLngObj:function(lat,lng){
    return new google.maps.LatLng(lat,lng);
  },
  getSeprateLatLngBound:function(bounds){
    return([this.getSeprateLatLng(bounds.getSouthWest()), this.getSeprateLatLng(bounds.getNorthEast())]);
  },
  LatLngBoundsObj:function(southWest,northEast){
    return new google.maps.LatLngBounds(this.LatLngObj.apply(this,southWest), this.LatLngObj.apply(this,northEast));
  },
  getShape:function(s){
    var allShapes=['CIRCLE','MARKER','RECTANGLE','POLYLINE','POLYGON'];
    for(var i=0;i<allShapes.length;++i){
       if(s===google.maps.drawing.OverlayType[allShapes[i]]){
         return allShapes[i];
       }
    }
  }
  
}

function getMap(){
	var id = jQuery("#getMap").val();
	jQuery.ajax({
      url: 'process.php',  
      data: {id : id, type : 'getMap' },
      type: 'post',
      async: false,
      success: function (msg) {
      	$('#data').val('');
          $('#data').val(msg);
      }           
  });
}
    </script>
  </head>
  <body>
<div class="map" id="mapIn"></div>
<div style="text-align:center">
  <!-- <a href="http://jsfiddle.net/doktormolle/EdZk4/">[source]</a> -->
  <input id="clear_shapes" value="clear shapes" type="button" />
  <!-- <input id="save_encoded" value="save encoded(IO.IN(shapes,true))" type="button" /> -->
  <!-- <input id="save_raw_map" value="save raw(IO.IN(shapes,false))" type="button" /> -->
  <input type="button" id="save_raw_map" value="save">
  <input id="data" value="" style="width:100%" readonly />
  <!-- <input id="restore" value="restore(IO.OUT(array,map))" type="button" /> -->
  <input id="restore" value="restore" type="button" />
<?php


$data = $database->select('users', [
    'name',
    'email',
    'id'
],['id' > 5]);

?>
  <select id="getMap" name="mapId" onchange="getMap()">
  	<?php foreach($data as $item) { $count++;?>  		
  	
  	<option value="<?= $item['id'];?>"><?= $item['name'];?></option>
  	<?php } ?>
  </select>
</div>
  </body>
</html>
