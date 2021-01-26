  </body>
</html>

<script type="text/javascript">
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









    google.maps.event.addDomListener(byId('restore1'), 'click', function(){
      if(this.shapes){
        for(var i=0;i<this.shapes.length;++i){
              this.shapes[i].setMap(null);
        }
      }
      this.shapes=MapFactory.OutputMap(JSON.parse(byId('data').value),map);
    });    
}



</script>