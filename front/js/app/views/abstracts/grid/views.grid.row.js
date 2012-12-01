Views.Grid.Row = Views.Abstract.View.extend({
	
	tagName: 'tr',

	_settings: null,
	
	events: null,
	
	initialize: function(){
		Views.Abstract.View.prototype.initialize.apply(this, arguments);
		this._settings = this.options.settings;
		this._initEvents();
		this.render();
		
		this.model.on('change', function(){
			this.refresh();
		}, this);
	},
	
	_initEvents: function(){
		for (var i  in this._settings){
			var events = this._settings[i].events;
			this._assignEvents(events, i);
		}
	},
	
	_assignEvents: function(events, item){
		var new_events = {};
		
		this.events = {};
		
		for (var i in events){
			new_events[this._prepareEvents(i, item)] = function(e){
				events[i](e, this.model, this);
			}
		}
		
		this.events = _.extend(this.events, new_events);
	},

	_prepareEvents: function(name, item){
		var full_name  = name;
		var event_name = trim(full_name.split(' ')[0]);
		full_name = trim(full_name.replace(event_name, ''));
		
		return event_name + ' #' + item + ' ' + full_name;
	},
	
	refresh: function(){
		this.render();
		this.delegateEvents();
	},
	
	render: function(){
		var row = '';
		
		for (var i  in this._settings){
			var value = '';
			
			if (this.model.has(i)){
				value = this.model.get(i);
			}
			
			value = _.escape(value);
			
			value = this._passThroughFormatters(value, i);
			
			row += '<td id="' + i + '">' + value + '</td>';
		}
		
		this.$el.html(row);
	},
	
	_passThroughFormatters: function(value, item){
		var value = value;
		var formatters = this._settings[item].formatter;
		
		if (typeof formatters == 'undefined'){
			return value;
		}
		
		if (typeof formatters == 'function'){
			return formatters(value, this.model, this);
		}
		
		for (var i in formatters){
			value = formatters[i](value, this.model, this);
		}
		
		return value;
	}
});