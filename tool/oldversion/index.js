
$(function() {

    // some backend examples

    // set the background galaxy image
    setImage("./172119.png");

    // input ellipses/points
    inputItems({
    	ellipses:[
    	{x:400, y:400, radA:275, radB:159, rot:-0.65}
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