/*
This plugin checks for certain custom attributes on tracked click events and includes as a parameter - value pair.
For example, this attribute:

data-wt.foo="bar" 

Logs this parameter and value:

WT.foo=bar


Example of including the plugin with your tag.

custom_attribs: { src: "http://www.abc.net.au/res/libraries/stats/webtrends-10.2/custom_attribs.js" }
*/

(function() {
	CustomAttribute= {
		transformWorker:function(dcs, options) {
			var attribName = "";
			var attribVal = "";
			var paramName = "";
			var el = options['element'] || {};
			
			if (el.attributes)	{
				for (var i = 0; i < el.attributes.length; i++)	{
					attribName = el.attributes[i].name;
					attribVal = el.attributes[i].value;
					if (attribName.match(/data-wt./))	{
						paramName = attribName.match(/data-wt.(.+)/)[1];
						dcs.WT[paramName]=attribVal; 
					}	
				}
			}
		},
		doWork: function(dcs, options) {				
			dcs.addTransform(CustomAttribute.transformWorker, 'all');
		}
	}
})();

Webtrends.registerPlugin("custom_attribs",CustomAttribute.doWork);