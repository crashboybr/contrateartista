var JMTemplateRotator = new Class ({
		initialize : function(id) {
			this.element_id = id;
			this.groupcontainer = document.id('djc_category_items_' + this.moduleid);
			if (typeof(this.groupcontainer) == 'undefined' || !this.groupcontainer) return;
			
			this.groups = this.groupcontainer.getElements('div.djc_group');
			if (typeof(this.groups) == 'undefined' || !this.groups) return;
			
			this.groupcount = this.groups.length;
			if (this.groupcount == 0) return;
			
			if (this.groupcount > 1) {
				this.prevButton = document.id('djc_categoryitems_prev_' + this.moduleid);
				this.nextButton = document.id('djc_categoryitems_next_' + this.moduleid);
				
				this.prevButton.addEvent('click',this.loadPrev.bind(this));
				this.nextButton.addEvent('click',this.loadNext.bind(this));
			}
			
			this.currentlydisplayed = 0;
			this.loadinginprogress = true;
			this.setup();
			this.loadinginprogress = false;
			this.loadGroup(this.currentlydisplayed);
			
		},
		setup : function() {
			this.groupFx = new Array();
			this.groups.each(function(element, index) {
				this.groupFx[index] = new Fx.Tween(element ,{link: 'cancel', duration: 200});
				element.setStyle('opacity', '0');
				element.setStyle('display', 'none');
				//element.setStyle('display', 'block');
				//this.groupFx[index] = new Fx.Slide(element).slideOut('horizontal');
				/*var activeElems = element.getElements('li.active');
				if (activeElems.length > 0) {
					this.currentlydisplayed = index;
				}*/
			}.bind(this));
		},
		loadGroup : function(index) {
			/*
			if (index < this.groupcount && !this.loadinginprogress) {
				this.loadinginprogress = true;
				this.currentlydisplayed = index;
				this.groups.each(function(element, loopindex) {
					if (loopindex != index) {
						element.setStyle('display', 'none');
					}
				});
				this.groups[index].setStyle('display','block');
				this.loadinginprogress = false;
			}
			*/
			if (index < this.groupcount && !this.loadinginprogress) {
				this.loadinginprogress = true;
				
				/*
				this.groupFx[this.currentlydisplayed].start('opacity',1,0);
				
				(function(){
					this.groups[this.currentlydisplayed].setStyle('display','none');
					this.currentlydisplayed = index;
					this.groups[this.currentlydisplayed].setStyle('display','block');
					this.groupFx[this.currentlydisplayed].start('opacity',0,1);
					this.loadinginprogress = false;
				}).delay(220,this);*/
				
				this.groupFx[this.currentlydisplayed].start('opacity',1,0)
				.chain(function(){
					this.groups[this.currentlydisplayed].setStyle('display','none');
					this.currentlydisplayed = index;
					
					var groupImages = this.groups[this.currentlydisplayed].getElements('img.djc_thumb');
					groupImages.each(function(image){
						var src = (image.getProperty('src'));
						var title = (image.getProperty('title'));
						if ((/\/$/.test(src)) && title != '') {
							image.setProperty('title', '');
							image.setProperty('src', title);
						}
					});
					
					this.groups[this.currentlydisplayed].setStyle('display','block');
					this.groupFx[this.currentlydisplayed].start('opacity',0,1);
					this.loadinginprogress = false;
				}.bind(this));
				
			}
		},
		loadNext : function() {
			if (this.currentlydisplayed + 1 >= this.groupcount) {
				return this.loadGroup(0);
			} else {
				return this.loadGroup(this.currentlydisplayed + 1);
			}
		},
		loadPrev : function() {
			if (this.currentlydisplayed == 0) {
				return this.loadGroup(this.groupcount - 1);
			} else {
				return this.loadGroup(this.currentlydisplayed - 1);
			}
		}
	});
