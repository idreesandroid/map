<?php
$servername = "localhost";
$username = "root";
$password = "";
$conn = new PDO("mysql:host=$servername;dbname=milkman", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


?>


<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
   <!--  <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false&key=AIzaSyA4d_ChkEg7E_k9rU7zPt09FVPGKpL1aAE&callback=initialize&v=3.exp"
      defer
    ></script> -->

<!-- <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false&key=AIzaSyA4d_ChkEg7E_k9rU7zPt09FVPGKpL1aAE&callback=initialize&v=3.exp">
</script> -->

	<script async defer src="https://maps.googleapis.com/maps/api/js?libraries=geometry,drawing&key=AIzaSyA4d_ChkEg7E_k9rU7zPt09FVPGKpL1aAE&v=3&callback=initialize"></script>


    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=drawing"></script>
    <style type="text/css">
      html, body
  {
      padding: 0;
      margin: 0;
      height: 100%;
  }
  .map{height:40%;}
    </style>
    <script>
    function initialize()
{   
        map_in          = new google.maps.Map(document.getElementById('map_in'), 
                                      { zoom: 12, 
                                        center: new google.maps.LatLng(30.3753, 69.3451)
                                      }),
        map_out         = new google.maps.Map(document.getElementById('map_out'), 
                                      { zoom: 12, 
                                        center: new google.maps.LatLng(30.3753, 69.3451)
                                      }),
        shapes          = [],
        selected_shape  = null,
        drawman         = new google.maps.drawing.DrawingManager({map:map_in}),
        byId            = function(s){return document.getElementById(s)},
        clearSelection  = function(){
                            if(selected_shape){
                              selected_shape.set((selected_shape.type === google.maps.drawing.OverlayType.MARKER
                                                 )?'draggable':'editable',false);
                              selected_shape = null;
                            }
                          },
        setSelection    = function(shape){
                            clearSelection();
                            selected_shape=shape;
                            
                              selected_shape.set((selected_shape.type
                                                  ===
                                                  google.maps.drawing.OverlayType.MARKER
                                                 )?'draggable':'editable',true);
                              
                          },
        clearShapes     = function(){
                            for(var i=0;i<shapes.length;++i){
                              shapes[i].setMap(null);
                            }
                            shapes=[];
                          };
    map_in.bindTo('center',map_out,'center');
    map_in.bindTo('zoom',map_out,'zoom');

    google.maps.event.addListener(drawman, 'overlaycomplete', function(e) {
        var shape   = e.overlay;
        shape.type  = e.type;
        google.maps.event.addListener(shape, 'click', function() {
          setSelection(this);
        });
        setSelection(shape);
        shapes.push(shape);
      });

    google.maps.event.addListener(map_in, 'click',clearSelection);
    google.maps.event.addDomListener(byId('clear_shapes'), 'click', clearShapes);
    google.maps.event.addDomListener(byId('save_encoded'), 'click', function(){
    var data=IO.IN(shapes,true);byId('data').value=JSON.stringify(data);});

    google.maps.event.addDomListener(byId('save_raw_map'), 'click', function(){

    var data=IO.IN(shapes,false);
    byId('data').value=JSON.stringify(data);
    var mapData = byId('data').value;
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
      this.shapes=IO.OUT(JSON.parse(byId('data').value),map_out);
    });
    
}


var IO={
  //returns array with storable google.maps.Overlay-definitions
  IN:function(arr,//array with google.maps.Overlays
              encoded//boolean indicating whether pathes should be stored encoded
              ){
      var shapes     = [],
          goo=google.maps,
          shape,tmp;
      
      for(var i = 0; i < arr.length; i++)
      {   
        shape=arr[i];
        tmp={type:this.t_(shape.type),id:shape.id||null};
        
        
        switch(tmp.type){
           case 'CIRCLE':
              tmp.radius=shape.getRadius();
              tmp.geometry=this.p_(shape.getCenter());
            break;
           case 'MARKER': 
              tmp.geometry=this.p_(shape.getPosition());   
            break;  
           case 'RECTANGLE': 
              tmp.geometry=this.b_(shape.getBounds()); 
             break;   
           case 'POLYLINE': 
              tmp.geometry=this.l_(shape.getPath(),encoded);
             break;   
           case 'POLYGON': 
              tmp.geometry=this.m_(shape.getPaths(),encoded);
              
             break;   
       }
       shapes.push(tmp);
    }

    return shapes;
  },
  //returns array with google.maps.Overlays
  OUT:function(arr,//array containg the stored shape-definitions
               map//map where to draw the shapes
               ){
      var shapes     = [],
          goo=google.maps,
          map=map||null,
          shape,tmp;
      
      for(var i = 0; i < arr.length; i++)
      {   
        shape=arr[i];       
        
        switch(shape.type){
           case 'CIRCLE':
              tmp=new google.maps.Circle({radius:Number(shape.radius),center:this.LatLngObj.apply(this,shape.geometry)});
            break;
           case 'MARKER': 
              tmp=new google.maps.Marker({position:this.LatLngObj.apply(this,shape.geometry)});
            break;  
           case 'RECTANGLE': 
              tmp=new google.maps.Rectangle({bounds:this.bb_.apply(this,shape.geometry)});
             break;   
           case 'POLYLINE': 
              tmp=new google.maps.Polyline({path:this.ll_(shape.geometry)});
             break;   
           case 'POLYGON': 
              tmp=new google.maps.Polygon({paths:this.mm_(shape.geometry)});
              
             break;   
       }
       tmp.setValues({map:map,id:shape.id})
       shapes.push(tmp);
    }
    return shapes;
  },
  l_:function(path,e){
    path=(path.getArray)?path.getArray():path;
    if(e){
      return google.maps.geometry.encoding.encodePath(path);
    }else{
      var r=[];
      for(var i=0;i<path.length;++i){
        r.push(this.p_(path[i]));
      }
      return r;
    }
  },
  ll_:function(path){
    if(typeof path==='string'){
      return google.maps.geometry.encoding.decodePath(path);
    }
    else{
      var r=[];
      for(var i=0;i<path.length;++i){
        r.push(this.LatLngObj.apply(this,path[i]));
      }
      return r;
    }
  },

  m_:function(paths,e){
    var r=[];
    paths=(paths.getArray)?paths.getArray():paths;
    for(var i=0;i<paths.length;++i){
        r.push(this.l_(paths[i],e));
      }
     return r;
  },
  mm_:function(paths){
    var r=[];
    for(var i=0;i<paths.length;++i){
        r.push(this.ll_.call(this,paths[i]));
        
      }
     return r;
  },
  p_:function(latLng){
    return([latLng.lat(),latLng.lng()]);
  },
  LatLngObj:function(lat,lng){
    return new google.maps.LatLng(lat,lng);
  },
  b_:function(bounds){
    return([this.p_(bounds.getSouthWest()),
            this.p_(bounds.getNorthEast())]);
  },
  bb_:function(sw,ne){
    return new google.maps.LatLngBounds(this.LatLngObj.apply(this,sw),
                                        this.LatLngObj.apply(this,ne));
  },
  t_:function(s){
    var t=['CIRCLE','MARKER','RECTANGLE','POLYLINE','POLYGON'];
    for(var i=0;i<t.length;++i){
       if(s===google.maps.drawing.OverlayType[t[i]]){
         return t[i];
       }
    }
  }
  
}
//google.load("map", "3")
google.maps.event.addDomListener(window, 'load', initialize);
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
<div class="map" id="map_in"></div>
<div style="text-align:center">
  <!-- <a href="http://jsfiddle.net/doktormolle/EdZk4/">[source]</a> -->
  <input id="clear_shapes" value="clear shapes" type="button" />
  <input id="save_encoded" value="save encoded(IO.IN(shapes,true))" type="button" />
  <input id="save_raw_map" value="save raw(IO.IN(shapes,false))" type="button" />
  <input id="data" value="" style="width:100%" readonly />
  <input id="restore" value="restore(IO.OUT(array,map))" type="button" />
<?php

$sql = "SELECT id FROM google_map where description != '[]'";  

    $stmt = $conn->prepare($sql);
    $stmt->execute();  
    $stmt->setFetchMode(PDO::FETCH_ASSOC); 

    $result = $stmt->fetchAll();
$count = 0;

?>
  <select id="getMap" name="mapId" onchange="getMap()">
  	<?php foreach($result as $item) { $count++;?>  		
  	
  	<option value="<?= $item['id'];?>"><?= $count;?></option>
  	<?php } ?>
  </select>
</div>
<div class="map" id="map_out"></div>

  </body>
</html>
