
$(function() {

    // some backend examples

    // set the background galaxy image
    setImage("http://galaxyexplorer-images.s3-website-us-west-2.amazonaws.com/ABCimages/172119.png");

    // input ellipses/points
    inputItems({
    	ellipses:[
    	{x:400, y:400, radA:100, radB:57, rot:2.503126427615, isMain:true}
    	],
    	points:[
    	{x:388, y:182}
    	]
    })

    // get the ellipses/points
    $("#save_items").click(function(){
        var items = getItems();
        console.log(items);
    })

});