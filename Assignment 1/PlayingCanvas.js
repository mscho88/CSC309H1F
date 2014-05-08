var canvas;
var ctx;
var line;
var triangle;
var rectangle;

var shapes = [];
var tool = "line";
var outlineWidth = "3";
var outlineColor = "black";
var fillColor = "white";	

var pastSelected;
var curSelected;

var isDraw;
var isMove;
var isResize;

function Shape(canvas, startX, startY, endX, endY, outlineColor, outlineWidth, fillColor, shapeName){
	if(canvas)
		this.ctx = canvas.getContext("2d");
	this.getOutlineColor = function(){return this.oColor;}
	this.setOutlineColor = function(value){this.oColor = value;}
	this.getOutlineWidth = function(){return this.oWidth;}
	this.setOutlineWidth = function(value){this.oWidth = value;}
	this.getTempWidth = function(){return this.tempWidth;}
	this.setTempWidth = function(value){this.tempWidth = value;}
	this.getFillColor = function(){return this.fColor;}
	this.setFillColor = function(value){this.fColor = value;}
}

function Line(canvas, startX, startY, endX, endY, outlineColor, outlineWidth, shapeName){
	Shape.call(this, canvas);
	this.shapeName = shapeName;
	this.startX = startX;
	this.startY = startY;
	this.endX = endX;
	this.endY = endY;
	this.oColor = outlineColor;
	this.oWidth = outlineWidth;
	this.tempWidth = outlineWidth;

	this.draw = function(){
		this.ctx.beginPath();
		this.ctx.moveTo(this.endX, this.endY);
		this.ctx.lineTo(this.startX, this.startY);
		this.ctx.strokeStyle = this.getOutlineColor();
		this.ctx.lineWidth = this.getOutlineWidth();
		this.ctx.closePath();
		this.ctx.stroke();
	}

	this.lineTest = function(event){
		var sx = this.startX, sy = this.startY, ex = this.endX, ey = this.endY;
		if(Math.abs(-(sy-ey)*event.pageX+(sx-ex)*event.pageY+sx*(sy-ey)-sy*(sx-ex))/Math.sqrt((sy-ey)*(sy-ey)+(sx-ex)*(sx-ex)) <= 4){
			return true;
		}
		return false;
	}
	
}

Line.prototype = new Shape();
Line.prototype.constructor = Line;

function Triangle(canvas, startX, startY, endX, endY, outlineColor, outlineWidth, fillColor, shapeName){
	Shape.call(this, canvas);

	this.shapeName = shapeName;
	this.startX = startX;
	this.startY = startY;
	this.endX = endX;
	this.endY = endY;
	this.oColor = outlineColor;
	this.oWidth = outlineWidth;
	this.fColor = fillColor;

	this.draw = function(){
		this.ctx.beginPath();
		this.ctx.moveTo(this.endX, this.endY);
		this.ctx.lineTo((this.startX + this.endX) / 2, this.startY);
		this.ctx.lineTo(this.startX, this.endY);
		this.ctx.strokeStyle = this.getOutlineColor();
		this.ctx.lineWidth = this.getOutlineWidth();
		this.ctx.fillStyle = this.getFillColor();
		this.ctx.closePath();
		this.ctx.fill();
		this.ctx.stroke();
	}

	this.triaTest = function(event){
		var sx = this.startX, sy = this.startY, ex = this.endX, ey = this.endY;
		if(ey > sy){
			// Case when a triangle is drawn normally
			if((2*(sy-ey) / (ex-sx))*(event.pageX-sx)-event.pageY+ey < 0 &&
			(2*(sy-ey) / (sx-ex)) *(event.pageX-ex)-event.pageY+ey < 0 &&
			event.pageY < ey){
				// considering three enges of triangle that clicked point is inside o
				return true;
			}
		}else if(ey < sy){
			// Case when a triangle is drawn upside down
			if((2*(sy-ey))/(ex-sx)*(event.pageX-sx)-event.pageY+ey > 0 &&
			(2*(sy-ey))/(sx-ex)*(event.pageX-ex)+ey-event.pageY > 0 &&
			event.pageY > ey){
				return true;
			}
		}
		return false;
	}
}

Triangle.prototype = new Shape();
Triangle.prototype.constructor = Triangle;

function Rectangle(canvas, startX, startY, endX, endY, outlineColor, outlineWidth, fillColor, shapeName){
	Shape.call(this, canvas);

	this.shapeName = shapeName;
	this.startX = startX;
	this.startY = startY;
	this.endX = endX;
	this.endY = endY;
	this.oColor = outlineColor;
	this.oWidth = outlineWidth;
	this.fColor = fillColor;

	this.draw = function(){
		this.ctx.beginPath();
		this.ctx.moveTo(this.endX, this.endY);
		this.ctx.lineTo(this.endX, this.startY);
		this.ctx.lineTo(this.startX, this.startY);
		this.ctx.lineTo(this.startX, this.endY);
		this.ctx.strokeStyle = this.getOutlineColor();
		this.ctx.lineWidth = this.getOutlineWidth();
		this.ctx.fillStyle = this.getFillColor();
		this.ctx.closePath();
		this.ctx.fill();
		this.ctx.stroke();
	}

	this.rectTest = function(event){
		var sx = this.startX, sy = this.startY, ex = this.endX, ey = this.endY;
		if(sx < ex && sy < ey){
			if(event.pageX > sx && event.pageX < ex && event.pageY > sy && event.pageY < ey){return true;}
		}else if(sx > ex && sy > ey){
			if(event.pageX < sx && event.pageX > ex && event.pageY < sy && event.pageY > ey){return true;}
		}else if(sx > ex && sy < ey){
			if(event.pageX < sx && event.pageX > ex && event.pageY > sy && event.pageY < ey){return true;}
		}else if(sx < ex && sy > ey){
			if(event.pageX > sx && event.pageX < ex && event.pageY < sy && event.pageY > ey){return true;}
		}
		return false;
	}
}

Rectangle.prototype = new Shape();
Rectangle.prototype.constructor = Rectangle;

window.canvasReady = function(){
	canvas = document.getElementById("canvas");
	ctx = canvas.getContext("2d");

	isDraw = false;
	isResize = false;
	isMove = false;
 	
 	canvas.addEventListener("mousedown", startDrawing, false);
 	canvas.addEventListener("mousemove", nowDrawing, false);
 	canvas.addEventListener("mouseup", stopDrawing, false);

	var startX = 0;
	var startY = 0;
	var endX = 0;
	var endY = 0;

	$("#tool").change(function(){
		tool = $("#tool option:selected").val();	
	});

	$("#outlineColor").change(function(){
		outlineColor = $("#outlineColor option:selected").val();
		if(tool == "selector"){
			curSelected.setOutlineColor($("#outlineColor option:selected").val());
			drawShapes();
		}
	});

	$("#outlineWidth").change(function(){
		outlineWidth = $("#outlineWidth option:selected").val();
		if(tool == "selector"){
			curSelected.setTempWidth($("#outlineWidth option:selected").val());
			drawShapes();
		}
	});

	$("#fillColor").change(function(){
		fillColor = $("#fillColor option:selected").val();
		if(tool == "selector"){
			curSelected.setFillColor($("#fillColor option:selected").val());
			drawShapes();
		}
	});
};

function testHitHelper(i){
	if(pastSelected == null && curSelected == null){
		pastSelected = shapes[i];
		curSelected = shapes[i];
		curSelected.setTempWidth(curSelected.getOutlineWidth());
	}else{
		if(pastSelected == curSelected){
			curSelected.setOutlineWidth(curSelected.getTempWidth());
			curSelected = shapes[i];
		}else if(pastSelected != curSelected){
			curSelected.setOutlineWidth(curSelected.getTempWidth());
			pastSelected = curSelected;
			curSelected.getOutlineWidth(curSelected.setTempWidth());
			curSelected = shapes[i];
			curSelected.setTempWidth(curSelected.getOutlineWidth());
		}
	}
	curSelected.setTempWidth(curSelected.getOutlineWidth());
	curSelected.setOutlineWidth('7');

	var tempShape = shapes[i];
	shapes = Array.concat(shapes.slice(0, i), shapes.slice(i+1, shapes.length));
	shapes.push(tempShape);
}

function testHit(event){
	for(var i = shapes.length - 1; i >= 0; i--){
		if(shapes[i].shapeName == "line" && shapes[i].lineTest(event)){
			testHitHelper(i);
			drawShapes();
			break;
		}else if(shapes[i].shapeName == "triangle" && shapes[i].triaTest(event)){
			testHitHelper(i);
			drawShapes();
			break;
		}else if(shapes[i].shapeName == "rectangle" && shapes[i].rectTest(event)){
			testHitHelper(i);
			drawShapes();
			break;
		}
	}
}

function startDrawing(event){
	if(tool != 'selector'){
		isDraw = true;
	}else{
		testHit(event);
		if(curSelected == pastSelected){
			isMove = true;
			isResize = true;
		}
	}
	startX = event.pageX;
	startY = event.pageY;
	endX = event.pageX;
	endY = event.pageY;
}

function nowDrawing(event){			
	if (isDraw == true) {
		if (tool == "line") {
			endX = event.pageX;
			endY = event.pageY;
			drawShapes();
			ctx.beginPath();
			ctx.moveTo(endX, endY);
			ctx.lineTo(startX, startY);
			ctx.strokeStyle = outlineColor;
			ctx.lineWidth = outlineWidth;
			ctx.closePath();
			ctx.stroke();
		} else if (tool == "triangle") {
			drawShapes();
			endX = event.pageX;
			endY = event.pageY;
			ctx.beginPath();
			ctx.moveTo(endX, endY);
			ctx.lineTo((startX + endX) / 2, startY);
			ctx.lineTo(startX, endY);
			ctx.strokeStyle = outlineColor;
			ctx.lineWidth = outlineWidth;
			ctx.fillStyle = fillColor;
			ctx.closePath();
			ctx.stroke();
			ctx.fill();
		} else if (tool == "rectangle") {
			endX = event.pageX;
			endY = event.pageY;
			drawShapes();
			ctx.beginPath();
			ctx.moveTo(endX, endY);
			ctx.lineTo(endX, startY);
			ctx.lineTo(startX, startY);
			ctx.lineTo(startX, endY);
			ctx.strokeStyle = outlineColor;
			ctx.lineWidth = outlineWidth;
			ctx.fillStyle = fillColor;
			ctx.closePath();
			ctx.stroke();
			ctx.fill();
		}
	}else if(isMove && $("input[type=radio]:checked").val() == "move"){
		endX = event.pageX;
		endY = event.pageY;
		drawShapes();
		curSelected.startX += (endX - startX);
		curSelected.endX += (endX - startX);
		startX = endX;
		curSelected.startY += (endY - startY);
		curSelected.endY += (endY - startY);
		startY = endY;
	}else if(isResize && $("input[type=radio]:checked").val() == "resize"){
		endX = event.pageX;
		endY = event.pageY;
		drawShapes();
		curSelected.startX = 2 * event.pageX - curSelected.endX;
		curSelected.startY = 2 * event.pageY - curSelected.endY;
	}
}

function stopDrawing(event){
	if (isDraw == true) {
		if (tool == "line") {
			if(!(startX == endX && startY == endY)){
				shapes.push(new Line(canvas, startX, startY, endX, endY, outlineColor, outlineWidth, tool));
			}
		} else if (tool == "triangle") {
			if(!(startX == endX && startY == endY)){
				shapes.push(new Triangle(canvas, startX, startY, endX, endY, outlineColor, outlineWidth, fillColor, tool));
			}
		} else if (tool == "rectangle") {
			if(!(startX == endX && startY == endY)){
				shapes.push(new Rectangle(canvas, startX, startY, endX, endY, outlineColor, outlineWidth, fillColor, tool));
			}
		}
	}else{
		isMove = false;
		isResize = false;
	}
	isDraw = false;
}

var newCanvas = function(){
	shapes = [];
	ctx.clearRect(0, 0, canvas.width, canvas.height);
};

var drawShapes = function(){
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	for(var i = 0; i < shapes.length; i++){
		shapes[i].draw();
	}
};

var copyShape = function(){
	if(tool == "selector" && curSelected != null){
		if(curSelected.shapeName == "line"){
			shapes.push(new Line(canvas, curSelected.startX+10, curSelected.startY+10, curSelected.endX+10, curSelected.endY+10, curSelected.getOutlineColor(), curSelected.getTempWidth(), "line"));
		}else if(curSelected.shapeName == "triangle"){
			shapes.push(new Triangle(canvas, curSelected.startX+10, curSelected.startY+10, curSelected.endX+10, curSelected.endY+10, curSelected.getOutlineColor(), curSelected.getTempWidth(), curSelected.getFillColor(), "triangle"));
		}else if(curSelected.shapeName == "rectangle"){
			shapes.push(new Rectangle(canvas, curSelected.startX+10, curSelected.startY+10, curSelected.endX+10, curSelected.endY+10, curSelected.getOutlineColor(), curSelected.getTempWidth(), curSelected.getFillColor(), "rectangle"));
		}
		drawShapes();
	}
};
var eraseShape = function(){
	if(tool == "selector" && shapes.length != 0){
		var i = shapes.indexOf(curSelected);
		if(shapes.length != 0){
			shapes = Array.concat(shapes.slice(0, i), shapes.slice(i+1, shapes.length));
			drawShapes();
		}
		curSelected = null;
		pastSelected = null;
	}
};
