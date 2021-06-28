function drawPieChart( data, colors, title ){ 
    var canvas = document.getElementById( "piechart_3d" ); 
    var context = canvas.getContext( "2d" ); 
    //context.imageSmoothingEnabled= True;
 
    // get length of data array 
    var dataLength = data.length; 
    // declare variable to store the total of all values 
    var total = 0; 
 
    // calculate total 
    for( var i = 0; i < dataLength; i++ ){ 
        // add data value to total 
        total += data[ i ][ 1 ]; 
    } 
 
    // declare X and Y coordinates of the mid-point and radius 
    var x = (canvas.width/2 -20); 
    var y = (canvas.height/2); 
    var radius = (canvas.height + canvas.width)/8; 
 
    // declare starting point (right of circle) 
    var startingPoint = 0; 
 
    for( var i = 0; i < dataLength; i++ ){ 
        // calculate percent of total for current value 
        var percent = data[ i ][ 1 ] * 100 / total; 
 
        // calculate end point using the percentage (2 is the final point for the chart) 
        var endPoint = startingPoint + ( 2 / 100 * percent ); 
 
        // draw chart segment for current element 
        context.beginPath();    
        // select corresponding color 
        context.fillStyle = colors[ i ]; 
        context.moveTo( x, y ); 
        context.arc( x, y, radius, startingPoint * Math.PI, endPoint * Math.PI );     
        context.fill(); 
 
        // starting point for next element 
        startingPoint = endPoint;  
  
        // draw labels for each element 
        context.rect( 220, 25 * i, 15, 15 ); 
        context.fill(); 
        context.fillStyle = "black"; 
        context.fillText( data[ i ][ 0 ] + " (" + data[ i ][ 1 ] + ")", 245, 25 * i + 15 ); 
    }  
  
    // draw title 
    context.font = "20px Arial"; 
    context.textAlign = "center"; 
    context.fillText( title, 100, 225 );  
}  
  
