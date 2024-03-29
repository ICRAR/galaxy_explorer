
/* Joe's Oval Tool Code
If you have any questions, feel free to email me at:
josephdunne1993@gmail.com
*/

/* README:
1. The functions made to interact with the tool by a backend are under "SERVER FUNCTIONS". (there are examples in index.js)
2. The code uses: Jquery, Bootstrap (not really required).
*/

//$(function() { // uncomment for shiny version

// INIT CANVAS //
/////////////////

// Get the canvas context
var canvas = document.getElementById("canvas1");
var ctx = canvas.getContext("2d");

// set canvas properties
var WIDTH = 800;    // width in pixels
var HEIGHT = 800;   // height in pixels
canvas.style.width = WIDTH + "px";
canvas.style.height = HEIGHT + "px";

// Set properties for high DPI canvas.
// On some high-resolution computers/laptops,
// a high DPI option is available for Canvas.
// Set DPI to 2 to enable high DPI.
// Set DPI to 1 to disable high DPI (DEFAULT).
var DPI = 1;
canvas.width = WIDTH * DPI;
canvas.height = HEIGHT * DPI;

// set starting line color
var lineColor = "#FF0000";

// Object storage
var ellipses = [];
var points = [];
var img = new Image();

// SERVER FUNCTIONS //
//////////////////////

// inputItems()
// Add ellipses/points to the program.
/*
format of argument 'm':
m.ellipses - array of ellipses to add.
m.points - array of points to add.

ellipses[i] format:
ellipse.x       - x coordinate (in pixels)
ellipse.y       - y coordinate (in pixels)
ellipse.radA    - semi major radius (pixels)
ellipse.radB    - semi minor radius (pixels)
ellipse.rot     - rotation of ellipse (radians)
ellipse.isMain  - optional argument, set to TRUE if the ellipse should
                    have an extra dotted ellipse surrounding it.

points[i] format:
point.x         - x coordinate (pixels)
point.y         - y coordinate (pixels)
*/
function inputItems(m) {
    clearEllipses();
    for (var i=0; i<m.ellipses.length; i++) {
        createEllipse(m.ellipses[i], true);
    }
    clearPoints();
    for (var i=0; i<m.points.length; i++) {
        createPoint(m.points[i]);
    }
    draw();
}

// setImage()
// sets the background image to draw ellipses/points on.
// src can either be:
// - path to a file.
// - a data URI of the image.
function setImage(src) {
    img.onload = function() {
        centreImage();
        draw();
    }
    img.src = src;
}

// getItems()
// collects the ellipses/points and returns them (likely to backend)
function getItems() {
    eList = [];
    for (var i=0; i<ellipses.length; i++) {
        e = ellipses[i].getParams();
        e.isMain = (i < mainEllipseCount);
        eList.push(e);
    }
    pList = [];
    for (var i=0; i<points.length; i++) {
        pList.push(points[i].getParams());
    }
    items = {ellipses:eList, points:pList};
    //Shiny.onInputChange("items", items); // uncomment for shiny version
    return items;
}

/* uncomment for shiny version
Shiny.addCustomMessageHandler("custom_setItems", function(m) {
    inputItems(m);
});
Shiny.addCustomMessageHandler("custom_setImage", function(m) {
    setImage(m.src);
});
*/


// ELLIPSE FUNCTIONS //
///////////////////////

function createEllipse(params, fromServer) {
    var ellipse = {};
    ellipse.color = lineColor;
    ellipse.draw = function(isMain, isSelected) {

        // If the ellipse is not selected, draw slightly fainter
        if(!isSelected) {
            ctx.globalAlpha = 0.8;
        }

        // set line properties
        ctx.strokeStyle = this.color;
        ctx.lineWidth = 2;

        // get parameters in canvas space
        var CSradA = toCS(this.getradA());
        var CSradB = toCS(this.radB);
        var CSpC = {x:toCX(this.pC.x), y:toCY(this.pC.y)};
        var CSpA = {x:toCX(this.pA.x), y:toCY(this.pA.y)};

        // Draw the ellipse
        // NOTE:
        // ctx.ellipse() is only supported by Google Chrome browser
        ///////////////
        if(ctx.ellipse) {
            ctx.beginPath();
            ctx.ellipse(CSpC.x,CSpC.y,CSradA,CSradB,this.getrot(),0,2*Math.PI);
            ctx.stroke();

            // draw main 50% oval
            if(isMain) {
                ctx.setLineDash([5, 10]);
                ctx.beginPath();
                ctx.ellipse(CSpC.x,CSpC.y,CSradA*1.5,CSradB*1.5,this.getrot(),0,2*Math.PI);
                ctx.stroke();
                ctx.setLineDash([1, 0]);
            }
        }
        else {
            drawEllipse(CSpC.x,CSpC.y,CSradA,CSradB,this.getrot());

            // draw main 50% oval
            if(isMain) {
                ctx.setLineDash([5, 10]);
                drawEllipse(CSpC.x,CSpC.y,CSradA*1.5,CSradB*1.5,this.getrot());
                ctx.setLineDash([1, 0]);
            }
        }
        ///////////////

        // Draw semi major axis line
        ctx.beginPath();
        ctx.moveTo(CSpC.x,CSpC.y);
        ctx.lineTo(CSpA.x,CSpA.y);
        ctx.lineWidth = 1;
        ctx.stroke();

        // Draw the squares
        // drawAt = the size the ellipse needs to be (pixels) to draw the squares
        // (if the squares are too crowded the squares should not be drawn)
        // size = the size of the squares (in pixels)
        var drawAt = 16;
        var size = 8;
        ctx.lineWidth = 2;
        if(CSradA > drawAt) {
            ctx.strokeStyle = "#00AACC";
            drawSquare(CSpC.x,CSpC.y,size);
            drawSquare(CSpA.x,CSpA.y,size);
        }
        if(CSradB > drawAt) {
            ctx.strokeStyle = "#00CC00";
            drawSquare(toCX(this.pB.x),toCY(this.pB.y),size);
        }

        // reset global alpha
        ctx.globalAlpha = 1.0;
    }
    ellipse.click = function(xM, yM) {

        var b = 12; // how close (in pixels) a click should be to a square to select it.
        o = this; // reference to self
        // was the centre point clicked?
        if(Math.abs(toCX(this.pC.x)-xM)<b && Math.abs(toCY(this.pC.y)-yM)<b) {
            // select ellipse
            selectEllipse(getEllipseIndex(this));
            // returns instructions to the mouse function
            return {drag:function(xM, yM, cX, cY) {
                        var ix = toIX(xM);
                        var iy = toIY(yM);
                        o.pA.x += ix - o.pC.x;
                        o.pA.y += iy - o.pC.y;
                        o.pC.x = ix;
                        o.pC.y = iy;
                        o.updatePB();
                    },
                    rel:function(mX, mY){},
                    up:function(mX, mY){}
                   };
        }
        // was point A (semi major axis point) clicked?
        if(Math.abs(toCX(this.pA.x)-xM)<b && Math.abs(toCY(this.pA.y)-yM)<b) {
            // select ellipse
            selectEllipse(getEllipseIndex(this));
            // returns instructions to the mouse function
            return {drag:function(xM, yM, cX, cY) {
                        o.pA.x = toIX(xM);
                        o.pA.y = toIY(yM);
                        o.updatePB();
                    },
                    rel:function(mX, mY){},
                    up:function(mX, mY){}
                   };
        }
        // was point B (semi minor axis point) clicked?
        if(Math.abs(toCX(this.pB.x)-xM)<b && Math.abs(toCY(this.pB.y)-yM)<b) {
            // select ellipse
            selectEllipse(getEllipseIndex(this));
            // returns instructions to the mouse function
            return {drag:function(xM, yM, cX, cY) {
                        o.pB.x = toIX(xM);
                        o.pB.y = toIY(yM);
                        // update radius
                        o.radB = Math.sqrt(Math.pow(o.pC.x-o.pB.x,2)+Math.pow(o.pC.y-o.pB.y,2));
                    },
                    rel:function(mX, mY){
                        if(Math.abs(toCX(o.pB.x) - toCX(o.pA.x))<b && Math.abs(toCY(o.pB.y) - toCY(o.pA.y))<b) {
                            o.radB = o.getradA();
                        }
                        o.updatePB();
                    },
                    up:function(mX, mY){}
                   };
        }
        return null;
    }
    ellipse.updatePB = function() {
        var rot = this.getrot();
        this.pB.x = this.pC.x + (Math.sin(rot) * this.radB);
        this.pB.y = this.pC.y + (-Math.cos(rot) * this.radB);
    }
    ellipse.getradA = function() {
        return Math.sqrt(Math.pow(this.pC.x-this.pA.x, 2) + Math.pow(this.pC.y-this.pA.y, 2));
    }
    ellipse.getrot = function() {
        return Math.atan((this.pA.y-this.pC.y)/(this.pA.x-this.pC.x));
    }
    ellipse.getParams = function() {
        var params = {};
        params.x = this.pC.x;
        params.y = this.pC.y;
        params.radA = this.getradA();
        params.radB = this.radB;
        params.rot = this.getrot();
        return params;
    }
    
    // init the ellipse
    if (params) {
        // params were specified, use the params
        ellipse.pC = {x:params.x, y:params.y};
        ellipse.pA = {x:params.x + (Math.cos(params.rot) * params.radA),y:params.y + (Math.sin(params.rot) * params.radA)};
        ellipse.pB = {x:0,y:0}; // updated at the end of function
        ellipse.radB = params.radB;
    }
    else {
        // no params were specified, a default ellipse is made
        ellipse.pC = {x:toIX(canvas.width/2),y:toIY(canvas.height/2)};
        ellipse.pA = {x:toIX((canvas.width/2) + 40),y:toIY(canvas.height/2)};
        ellipse.pB = {x:0,y:0};
        ellipse.radB = toIS(40);
    }
    ellipse.updatePB();
    // check isMain.
    if((!fromServer && !mainEllipseCount) || (params && params.isMain)) {
        $("#ellipse_list").prepend('<li>Main Ellipse</li>');
        ellipses.splice(0, 0, ellipse);
        selectEllipse(0);
        mainEllipseCount++;
    }
    else {
        $("#ellipse_list").append('<li>Ellipse</li>');
        ellipses.push(ellipse);
        selectEllipse(ellipses.length - 1);
    }
    addClickable(ellipse);
    return ellipse;
}

function getEllipseIndex(o) {
    return ellipses.indexOf(o);
}

var mainEllipseCount = 0;
var selectedEllipse = -1;
function selectEllipse(i) {
    deselectEllipse();
    deselectPoint();
    if(i >= 0 && i < ellipses.length) {
        $("#ellipse_list li").eq(i).css("background-color","#CCFFFF");
        selectedEllipse = i;
    }
}
function deselectEllipse() {
    $("#ellipse_list li").css("background-color","");
    selectedEllipse = -1;
}
function copyEllipse(i) {
    if(i >= 0 && i < ellipses.length) {
        createEllipse(ellipses[i].getParams());
    }
}
function removeEllipse(i) {
    if(i >= 0 && i < ellipses.length) {
        $("#ellipse_list li").eq(i).remove();
        removeClickable(ellipses[i]);
        ellipses.splice(i, 1);
        // select next closest ellipse to i
        if(i < ellipses.length) {
            selectEllipse(i);
        }
        else {
            selectEllipse(ellipses.length-1);
        }
        // check if main ellipse was deleted
        if(i < mainEllipseCount) {
            mainEllipseCount--;
        }
    }
}
function clearEllipses() {
    for (var i=ellipses.length-1; i>=0; i--) {
        removeEllipse(i);
    }
}

// POINT FUNCTIONS //
/////////////////////

function createPoint(params) {
    var point = {};
    point.color = lineColor;
    point.draw = function(isSelected) {
        if(!isSelected) {
            ctx.globalAlpha = 0.8;
        }
        ctx.strokeStyle = this.color;
        ctx.lineWidth = 2;
        drawCross(toCX(this.pos.x),toCY(this.pos.y), 6);
        ctx.globalAlpha = 1.0;
    }
    point.click = function(xM, yM) {
        var b = 8; // bounds
        o = this; // reference to self
        if(Math.abs(toCX(this.pos.x)-xM)<b && Math.abs(toCY(this.pos.y)-yM)<b) {
            selectPoint(getPointIndex(this));
            return {drag:function(xM, yM, cX, cY) {
                        o.pos.x = toIX(xM);
                        o.pos.y = toIY(yM);
                    },
                    rel:function(mX, mY){},
                    up:function(mX, mY){}
                   };
        }
        return null;
    }
    point.getParams = function() {
        var params = {};
        params.x = this.pos.x;
        params.y = this.pos.y;
        return params;
    }
    
    // init point
    if (params == null) {
        // no params were specified, a default point is made
        point.pos = {x:toIX(canvas.width/2),y:toIY(canvas.height/2)};
    }
    else {
        // params were specified, use the params
        point.pos = {x:params.x, y:params.y};
    }
    $("#point_list").append('<li>Point</li>');
    points.push(point);
    addClickable(point);
    selectPoint(points.length - 1);
    return point;
}

function getPointIndex(o) {
    return points.indexOf(o);
}

var selectedPoint = -1;
function selectPoint(i) {
    deselectPoint();
    deselectEllipse();
    if(i >= 0 && i < points.length) {
        $("#point_list li").eq(i).css("background-color","#CCFFFF");
        selectedPoint = i;
    }
}
function deselectPoint() {
    $("#point_list li").css("background-color","");
    selectedPoint = -1;
}
function removePoint(i) {
    if(i >= 0 && i < points.length) {
        $("#point_list li").eq(i).remove();
        removeClickable(points[i]);
        points.splice(i, 1);
        // select closest point to i
        if(i < points.length) {
            selectPoint(i);
        }
        else {
            selectPoint(points.length-1);
        }
    }
}
function clearPoints() {
    for (var i=points.length-1; i>=0; i--) {
        removePoint(i);
    }
}

// DRAW FUNCTIONS //
////////////////////

// draw a simple sqare at (x, y) in pixels
// lW = line width
function drawSquare(x, y, size) {
    ctx.beginPath();
    ctx.moveTo(x-size,y-size);
    ctx.lineTo(x+size,y-size);
    ctx.lineTo(x+size,y+size);
    ctx.lineTo(x-size,y+size);
    ctx.closePath();
    ctx.stroke();
}

// draw a cross shape
function drawCross(x, y, size) {
    ctx.beginPath();
    ctx.moveTo(x-size,y-size);
    ctx.lineTo(x+size,y+size);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(x-size,y+size);
    ctx.lineTo(x+size,y-size);
    ctx.stroke();
}

// draw an ellipse
function drawEllipse(x, y, radA, radB, rot) {
    ctx.beginPath();
    for (var i = 0 * Math.PI; i < 2 * Math.PI; i += 0.01) {
        xPos = x - (radB * Math.sin(i)) * Math.sin(rot) + (radA * Math.cos(i)) * Math.cos(rot);
        yPos = y + (radA * Math.cos(i)) * Math.sin(rot) + (radB * Math.sin(i)) * Math.cos(rot);

        if (i == 0) {
            ctx.moveTo(xPos, yPos);
        } else {
            ctx.lineTo(xPos, yPos);
        }
    }
    ctx.stroke();
    ctx.closePath();
}

// The main draw function
function draw() {
    ctx.clearRect(0,0,canvas.width,canvas.height);
    ctx.drawImage(img, toCX(0), toCY(0), toCS(img.width), toCS(img.height));
    for(var i=0;i<ellipses.length;i++){
        ellipses[i].draw((i < mainEllipseCount), (i == selectedEllipse));
    }
    for(var i=0;i<points.length;i++){
        points[i].draw((i == selectedPoint));
    }
}

// IMAGE/CANVAS SPACE FUNCTIONS //
//////////////////////////////////

/* The program space is divided into two areas:
Canvas space - the 800x800 area of the Canvas.
Image space - the space relative to the Image inside the Canvas.

All the ellipses/points and the image itself exist in Image space.
Below are functions to convert a position in Canvas space (such as a mouse click),
to a position in Image space (such as selectable point).
*/

// image space properties (position/scale).
var imageP = {
    scale: 1.0,
    xDisp: 0.0,
    yDisp: 0.0
};

// convert variables from Image space to Canvas space
// convert x pos
function toCX(x) {
    return (x + imageP.xDisp) * imageP.scale;
}
// convert y pos
function toCY(y) {
    return (y + imageP.yDisp) * imageP.scale;
}
// convert scale
function toCS(l) {
    return l * imageP.scale;
}

// convert variables from Canvas space to Image space
// convert x pos
function toIX(x) {
    return (x / imageP.scale) - imageP.xDisp;
}
// convert y pos
function toIY(y) {
    return (y / imageP.scale) - imageP.yDisp;
}
// convert scale
function toIS(l) {
    return l / imageP.scale;
}

// centre image
function centreImage() {
    imageP.scale = 1.0
    imageP.xDisp = 0.0
    imageP.yDisp = 0.0
}

// CLICK FUNCTIONS //
/////////////////////

/*
    Storage of items that can be clicked.
*/
var clickable = [];
function addClickable(o) {
    clickable.push(o);
}
function removeClickable(o) {
    i = clickable.indexOf(o);
    if(i > -1) {
        clickable.splice(i, 1);
    }
}

/*
    How the mouse works:

    When the mouse is clicked in the canvas, the program iterates over the
    clickable objects in the canvas.

    If an object decides it has been clicked, it returns instructions
    to 'click.item' on what dragging/releasing/clicking should do.

    If no items were clicked, the defaultItem() instructions are used.
    (which drags the canvas, or creates a point if it was a regular click).
*/

// The Default instructons to the mouse click/drag/release etc. events.
function defaultItem() {
    var item = {};
    // mX = mouseX
    // mY = mouseY
    // cX = change in mouseX
    // cY = change in mouseY
    item.drag = function(mX, mY, cX, cY) {
        imageP.xDisp += toIS(cX);
        imageP.yDisp += toIS(cY);
    };
    item.rel = function(mX, mY) {};
    item.up = function(mX, mY) {
        createPoint({x:toIX(mX), y:toIY(mY)});
    };
    return item;
}

var click = {};
click.isDragging = false;
click.item = defaultItem();

$("#canvas1").mousedown(function(e){
    e.preventDefault();
    var left = $(this).offset().left;
    var top = $(this).offset().top;
    var x = (e.pageX-left) * DPI;
    var y = (e.pageY-top) * DPI;
    // console.log("mouse down:", x, y);

    // first check if any selected items have been clicked
    var found = false;
    if(selectedEllipse >= 0) {
        var it = ellipses[selectedEllipse].click(x, y);
        if(it != null) {
            click.item = it;
            found = true;
        }
    }
    if(selectedPoint >= 0 && !found) {
        var it = points[selectedPoint].click(x, y);
        if(it != null) {
            click.item = it;
            found = true;
        }
    }
    // if no item was clicked, try all items
    if(!found) {
        for(var i=clickable.length-1;i>=0;i--) {
            var it = clickable[i].click(x, y);
            if(it != null) {
                click.item = it;
                break;
            }
        }
    }
    var lastX = x;
    var lastY = y;
    draw();
    $(window).mousemove(function(e) {
        // the mouse is being dragged
        click.isDragging = true;
        var x = (e.pageX-left) * DPI;
        var y = (e.pageY-top) * DPI;
        click.item.drag(x, y, x-lastX, y-lastY);
        lastX = x;
        lastY = y;
        draw();
    });
})
.mouseup(function(e) {
    var x = (e.pageX-$(this).offset().left) * DPI;
    var y = (e.pageY-$(this).offset().top) * DPI;
    $(window).unbind("mousemove");
    if (!click.isDragging) {
        // standard click with no dragging
        // console.log("mouse up:",x ,y);
        click.item.up(x, y);
    }
    else {
        // the mouse was released after dragging
        // console.log("mouse release:",x,y);
        click.item.rel(x, y);
    }
    click.isDragging = false;
    click.item = defaultItem();
    draw();
});

// BUTTON ELEMENTS //
/////////////////////

$("#add_ellipse").click(function(){
    createEllipse();
    draw();
})
$("#copy_ellipse").click(function(){
    copyEllipse(selectedEllipse);
    draw();
})
$("#remove_ellipse").click(function(){
    removeEllipse(selectedEllipse);
    draw();
})
$('#ellipse_list').on('click', 'li', function() {
    selectEllipse($(this).index());
    draw();
});
$("#add_point").click(function(){
    createPoint();
    draw();
})
$("#remove_point").click(function(){
    removePoint(selectedPoint);
    draw();
})
$('#point_list').on('click', 'li', function() {
    selectPoint($(this).index());
    draw();
});
$("#clear_items").click(function(){
    clearEllipses();
    clearPoints();
    draw();
})
/* uncomment for shiny version
$("#save_items").click(function(){
    getItems();
})
*/
$("#zoom_in").click(function(){
    var x = toIX(canvas.width/2);
    var y = toIY(canvas.height/2);
    imageP.scale *= 1.2;
    imageP.xDisp = toIS(canvas.width/2) - x;    // centre the X
    imageP.yDisp = toIS(canvas.height/2) - y;   // centre the Y
    draw();
})
$("#zoom_out").click(function(){
    var x = toIX(canvas.width/2);
    var y = toIY(canvas.height/2);
    imageP.scale /= 1.2;
    imageP.xDisp = toIS(canvas.width/2) - x;    // centre the X
    imageP.yDisp = toIS(canvas.height/2) - y;   // centre the Y
    draw();
})
$("#zoom_centre").click(function(){
    centreImage();
    draw();
})

// COLOR BUTTONS //
///////////////////

$(".color_button").click(function(){
    lineColor = $(this).css("background-color");
    if(selectedEllipse >= 0) {
        ellipses[selectedEllipse].color = lineColor;
    }
    else if(selectedPoint >= 0) {
        points[selectedPoint].color = lineColor;
    }
    draw();
})
$("#color_all").click(function(){
    for(var i=0; i<ellipses.length; i++) {
        ellipses[i].color = lineColor;
    }
    for(var i=0; i<points.length; i++) {
        points[i].color = lineColor;
    }
    draw();
})

// OPTIONAL, allows DELETE key to delete ellipses //
////////////////////////////////////////////////////

/*
$(document).keydown(function(e) {
    if(e.which == 8) {
        e.preventDefault();
        removeEllipse(selectedEllipse);
        removePoint(selectedPoint);
        draw();
    }
});
*/


//});   //uncomment for shiny version