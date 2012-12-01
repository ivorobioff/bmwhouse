$(function(){
	Views.Admin.GridControls = Views.Grid.Controls.extend({
		
		_template: $('#test-bb-controls-tmp'),
		
		events: {
			'click [data-page]': function(e){
				var $e = $(e.target);
				var page = $e.attr('data-page');
				
				this.model.set('current_page', page);
				
				return false;
			}
		},
		
		render: function(){
			var tmp = Handlebars.compile(this._template.html());
			
			var data = this.model.toJSON();
			
			Handlebars.registerHelper('on_page', function(value){
				if (data.rows_per_page == value){
					return 'selected="selected"';
				}
				return '';
			});
			
			Handlebars.registerHelper('pages_line', $.proxy(function(){
				var total_pages = 0;
				
				if (data.total > 0){
					total_pages = Math.ceil(data.total / data.rows_per_page);
				}
				
				if (data.total <= data.rows_per_page){
					return '1';
				}
			
				var line = '';
				var d = '';
				
				var count = total_pages > 3 ? 3 : total_pages;
								
				for (var i = 1; i <= count; i ++){
					line += d + '<a href="#" data-page="' + i + '">' + i + '</a>';
					d = ' ';
				}
								
				if (total_pages > 6){
					line += d + '<input type="text" class="bmw-5" name="current_page" />';
				}
			
				if (total_pages > 3){
					for (var i = total_pages; i >= 4; i --){
						line += d + '<a href="#" data-page="' + i + '">' + i + '</a>';
					}
				}
				
				return new Handlebars.SafeString(line);
								
			}, this));
			
			this.setElement($(tmp(data)));
			
			this.$el.insertAfter('#test-bb-table');
		}
	});
});