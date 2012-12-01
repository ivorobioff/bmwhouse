Views.Grid.Table = Views.Abstract.Collection.extend({
	
	url: '',
	
	events: {
		
	},
	
	rows: null,
	
	initialize: function(){
		this.rows = new Lib.Collection();
		this.fetch();
	},
	
	/**
	 * 1. Заполняем коллекцию даннымы здесь. 
	 * 2. Получаем состояние грида в модель Models.Grid.State
	 * 
	 * @param object state - состояние грида. 
	 * Если параметр не задан, то состояние по умолчанию будет взято на сторане сервера.
	 */
	fetch: function(state){
		var state = state;
		if (typeof state != 'object'){
			state = {};
		}
		
		this.collection = new Collections.Grid.Rows([{id: 1, title: 'AERDF0001'}, {id: 2, title: 'RRR9999'}]);
		this.model = new Models.Grid.State();
		
		Lib.Requesty.read({
			url: this.url,
			date: state,
			success: $.proxy(function(nevermid, data){
				if (typeof data.state != 'object'){
					return false;
				}
				
				if (typeof data.rows != 'object'){
					return false;
				}
				
				this.model = new Models.Grid.State(data.state);
				this.collection = new Collections.Grid.Rows(data.rows);
				this.render();
				this.model.on('change', function(){
					this.refresh();
				}, this);
			}, this),
			
		});
	},
	
	refresh: function(){
		this.rows.clear(function(row){
			row.remove();
		}, this);
		
		var state = this.model.toJSON();
		
		delete this.model;
		delete this.collection;
		
		this.fetch(state);
	},
	
	render: function(){
		var cell_settings = this.getCellSettings();
		
		var table = '';		
		
		for (var i in cell_settings){
			
			var label = typeof cell_settings[i].label == 'string' ?  cell_settings[i].label : '';
			
			table += '<th>' + label + '</th>';
		}
		
		table = '<table id="table"><tr>' + table + '</tr></table>';
		
		this.$el.html(table);
		
		this.collection.forEach(function(model){
			var row = new Views.Grid.Row({model: model, settings: cell_settings});
			this.rows.add(model.get('id'), row);
			this._appendRow(row);
		}, this);
	},
	
	/**
	 * Добавляет строку к гриду
	 */
	_appendRow: function(row){
		this.$el.find('#table').append(row.getElement());
	}
});