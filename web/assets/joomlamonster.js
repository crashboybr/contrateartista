var templateFilter=new Class({initialize:function(e,t){this.wrapper=document.id(e);this.limit=t;this.responseWrapper=document.id(e+"-fill");if(!this.wrapper||!this.responseWrapper){return false}instance=this;this.ajax=new Request({url:"index.php?option=com_joomlamonster&view=joomlamonster&format=raw",link:"cancel",onSuccess:function(e,t){this.onRequestResponse(e)}.bind(this)});return this.setup()},setup:function(){var e=this.wrapper.getElements("span.jm-category-link");var t=this.wrapper.getElements("span.jm-extension-link");var n=this;if(e.length){e.each(function(e,t){e.removeEvents("click");e.addEvent("click",n.click.pass([e,n,"category"]))})}if(t.length){t.each(function(e,t){e.removeEvents("click");e.addEvent("click",n.click.pass([e,n,"extension"]))})}this.template_groups=new grouppedTemplates(this.wrapper)},click:function(e,t,n){if(n=="extension"){var r=t.wrapper.getElements("div.jm-extension");if(r.length){r.each(function(e,t){e.removeClass("active")})}e.getParent("div.jm-extension").addClass("active")}var i=e.getAttribute("rel");this.isloading=true;t.responseWrapper.set("html","");t.wrapper.addClass("loading");t.ajax.send(i+"&gl="+t.limit)},onRequestResponse:function(e){this.responseWrapper.set("html",e);this.wrapper.removeClass("loading");this.isloading=false;this.setup();if(document.templateOverlays){document.templateOverlays.scanPage()}this.template_groups.setup()}});var templateOverlay=new Class({initialize:function(){this.scanPage()},scanPage:function(){var e=this;var t=document.getElements("div.jm-template");t.each(function(t,n){var r=t.getElements("div.jm-tpl-links a");var i=t.getElements("div.jm-tpl-links span.jm-tpl-extension-list");if(i.length>0){e.showIt(i[0])}r.each(function(t,n){t.removeEvents("mouseover");t.removeEvents("mouseout");t.addEvent("mouseover",function(t){t.stop();if(i.length>0){e.hideThem(i);e.showIt(i[n])}});t.addEvent("mouseout",function(t){t.stop();if(i.length>0){e.hideIt(i[n]);e.showIt(i[0])}})})})},hideThem:function(e){e.each(function(e){e.setStyle("display","none")})},showIt:function(e){e.setStyle("display","block")},hideIt:function(e){e.setStyle("display","none")}});var grouppedTemplates=new Class({initialize:function(e){this.moduleid=e;this.groupcontainer=document.id(this.moduleid);if(typeof this.groupcontainer=="undefined"||!this.groupcontainer)return;this.groups=this.groupcontainer.getElements("div.jm-tpl-group");if(typeof this.groups=="undefined"||!this.groups)return;this.groupcount=this.groups.length;if(this.groupcount==0)return;this.prevButton=this.groupcontainer.getElement(".jm-prev-column");this.nextButton=this.groupcontainer.getElement(".jm-next-column");if(this.groupcount>1){this.prevButton.addEvent("click",this.loadPrev.bind(this));this.nextButton.addEvent("click",this.loadNext.bind(this));this.prevButton.setStyle("opacity","1");this.nextButton.setStyle("opacity","1")}else{this.prevButton.setStyle("opacity","0");this.nextButton.setStyle("opacity","0")}this.currentlydisplayed=0;this.loadinginprogress=true;this.setup();this.loadinginprogress=false;this.loadGroup(this.currentlydisplayed)},setup:function(){this.groupFx=new Array;this.groups.each(function(e,t){this.groupFx[t]=new Fx.Tween(e,{link:"cancel",duration:200});e.setStyle("opacity","0");e.setStyle("display","none")}.bind(this))},loadGroup:function(e){if(e<this.groupcount&&!this.loadinginprogress){this.loadinginprogress=true;this.groupFx[this.currentlydisplayed].start("opacity",1,0).chain(function(){this.groups[this.currentlydisplayed].setStyle("display","none");this.currentlydisplayed=e;var t=this.groups[this.currentlydisplayed].getElements("img.jm-tpl-img");t.each(function(e){var t=e.getProperty("src");var n=e.getProperty("title");if(/\/$/.test(t)&&n!=""){var r=e.getParent("div.jm-template");r.setStyle("opacity",0);r.set("tween",{duration:"300",transition:"linear",link:"cancel"});e.onload=function(){r.tween("opacity",0,1)};e.setProperty("title","");e.setProperty("src",n)}});this.groups[this.currentlydisplayed].setStyle("display","block");this.groupFx[this.currentlydisplayed].start("opacity",0,1);this.loadinginprogress=false}.bind(this))}},loadNext:function(){if(this.currentlydisplayed+1>=this.groupcount){return this.loadGroup(0)}else{return this.loadGroup(this.currentlydisplayed+1)}},loadPrev:function(){if(this.currentlydisplayed==0){return this.loadGroup(this.groupcount-1)}else{return this.loadGroup(this.currentlydisplayed-1)}}});var responsiveNavigation={initialize:function(e){this.frame=document.id(e);if(!this.frame){return false}this.responsive=true;var t={id:"jm-res-desktop",width:0,height:0,frame_class:"jm-frame-desktop",active:true};var n={id:"jm-res-tablet-l",width:1024,height:768,frame_class:"jm-frame-tl",active:false};var r={id:"jm-res-tablet-p",width:767,height:1024,frame_class:"jm-frame-tp",active:false};var i={id:"jm-res-phone-l",width:480,height:320,frame_class:"jm-frame-pl",active:false};var s={id:"jm-res-phone-p",width:320,height:480,frame_class:"jm-frame-pp",active:false};this.frameMorphFx=new Fx.Morph(this.frame,{duration:5,transition:Fx.Transitions.Linear});this.buttons=[t,n,r,i,s];if(!document.id(t.id)){this.responsive=false;return}var o=this;this.buttons.each(function(e,t){if(document.id(e.id)){document.id(e.id).addEvent("click",o.click.pass([e,o]))}})},click:function(e,t){t.buttons.each(function(e,n){e.active=false;document.id(e.id).removeClass("active");t.frame.removeClass(e.frame_class)});if(e.height==0||e.width==0){var n=window.getWidth();var r=window.getHeight()-document.id("jm-top").getSize().y;var i=t.frame.getSize().y;var s=t.frame.getSize().x;t.frame.setStyle("margin-top","0");t.frame.setStyle("margin","0 auto");t.frameMorphFx.start({height:[i,r],width:[s,n]})}else{var r=e.height;var n=e.width;var i=t.frame.getSize().y;var s=t.frame.getSize().x;var o=(window.getHeight()-document.id("jm-top").getSize().y-r)/2;o=Math.max(o,0);t.frameMorphFx.start({height:[i,r],width:[s,n],"margin-top":o})}t.frame.addClass(e.frame_class);e.active=true;document.id(e.id).addClass("active")},resize:function(){this.buttons.each(function(e,t){if(e.active){document.id(e.id).fireEvent("click",document.id(e.id));return}})}}

window.addEvent('domready', function() {
	var tplFilter = new templateFilter('jm-templates', 15);
	var tplExtFilter = new templateFilter('jm-extensions-templates', 12);
	
	document.templateOverlays = new templateOverlay();
	
	var jmAccordion = new Fx.Accordion(
		document.id('jm-toolbar'), 
		'#jm-toolbar h1.jm-template-toggler', 
		'#jm-toolbar-content div.jm-template-content', 
		{
			display: -1, 
			alwaysHide: true, 
			duration: 200,
			initialDisplayFx: false,
			onActive: function(toggler,element) {
				toggler.addClass('active');
			},
			onBackground: function(toggler,element) {
				toggler.removeClass('active');
			},
			onComplete: function(){
				var wrapper = document.id('jm-toolbar-content');
				wrapper.setStyle('display', 'block');
				var overlay = document.id('jm-page-overlay');
				if (this.selfHidden || this.previous == -1) {
					overlay.removeClass('active');
				} else {
					overlay.addClass('active');
				}
			}
		}
	);
	
	var jmPageOverlay = document.id('jm-page-overlay');
	jmPageOverlay.addEvent('click', function(){
		jmAccordion.display(-1);
	});
	
	var jmTopSlide = new Fx.Tween('jm-top', {
		duration: '200',
	    transition: 'linear',
	    link: 'cancel',
	    property: 'height'
    });
    
    var jmTopSlideHeight = document.id('jm-top').getSize().y;
    
	document.id('jm-top-hide').addEvent('click', function(event){
	    event.stop();
	    document.id('jm-top').setStyle('overflow', 'hidden');
	    document.id('jm-top-hide').setStyle('display', 'none');
	    jmTopSlide.start(jmTopSlideHeight, 0).chain(function(){
	    	document.id('jm-top-show').setStyle('display', 'block');
	    	window.fireEvent('resize',window);	
	    });
	});

	document.id('jm-top-show').addEvent('click', function(event){
	    event.stop();
	    document.id('jm-top-show').setStyle('display', 'none');
	    jmTopSlide.start(0, jmTopSlideHeight).chain(function(){
	    	document.id('jm-top').setStyle('overflow', 'visible');
	    	document.id('jm-top-hide').setStyle('display', 'block');	
	    	window.fireEvent('resize',window);
	    });
	});
	
	var demoLinks = document.id(document.body).getElements('.jm-tpl-links a');
	demoLinks.each(function(el,ind){
		el.addEvent('click',function(event){
			document.id(document.body).removeClass('loaded');
			return event;
		});
	});
	
	responsiveNavigation.initialize('jm-frame');
	//document.id('jm-frame').setStyle("height",(window.getHeight()-document.id('jm-top').getSize().y)+"px");
	window.fireEvent('resize');
	
});


window.addEvent('resize', function() {
	$clear(this.resizeTimer);
	this.resizeTimer = (function() {
		if (typeof(responsiveNavigation) == 'undefined' || responsiveNavigation.responsive == false) {
			document.id('jm-frame').setStyle("height", (window.getHeight() - document.id('jm-top').getSize().y) + "px");
			document.id('jm-frame').setStyle("width", "100%");	
		} else {
			responsiveNavigation.resize();
		}
	}).delay(10);
}); 

window.addEvent('load', function() {
	document.id(document.body.addClass('loaded'));
});

