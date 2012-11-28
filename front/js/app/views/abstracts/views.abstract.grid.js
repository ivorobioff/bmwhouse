Views.Abstract.Grid = Views.Abstract.View.extend({
	
	_grid: null,
		
	_default_settings: {
		datatype: 'json',
		mtype: 'GET',
		sortname: 'id',
	    sortorder: 'desc',
	    viewrecords: true,
	    gridview: true,
	    autowidth:true,
	    autoencode: true,
	    hidegrid: false,
	    rowNum: 10,
	    rowList: [10, 20, 50, 100],
	    pager: '#default-pager',
	    height: '100%',
	    
	    jsonReader : {
	        repeatitems: false,
	    },
	},
	
	_default_buttons: {
		view: false,
		edit: false,
		add: false,
		del: false,
		search: false,
		refresh: false
	},
	
	_settings: null,
	
	initialize: function(){
		Views.Abstract.View.prototype.initialize.apply(this, arguments);
		this.render();
	},
	
	render: function(){
		var settings = _.clone(this._default_settings);
		
		this._grid = this.$el.jqGrid(_.extend(settings, this._getGridSettings()));
		this._settings = _.extend(settings, this._getGridSettings());
		this._unsetDefaultButtons();
		this._addCustomButtons();
	},
	
	/**
	 * Отключаем кнопки по умолчанию
	 */
	_unsetDefaultButtons: function(){
		this._grid.navGrid(this._settings.pager, this._default_buttons);
	},
	
	_addCustomButtons: function(){
		if (typeof this._onAddClick == 'function'){
			this._grid.navButtonAdd(this._settings.pager, {
			   caption: '', 
			   buttonicon:'ui-icon-plus', 
			   onClickButton: $.proxy(this._onAddClick, this),
			   position:"last"
			})
		}
		
		if (typeof this._onEditClick == 'function'){
			this._grid.navButtonAdd(this._settings.pager, {
			   caption: '', 
			   buttonicon:'ui-icon-pencil', 
			   onClickButton: $.proxy(this._onAddClick, this),
			   position:"last"
			})
		}
		
		if (typeof this._onRemoveClick == 'function'){
			this._grid.navButtonAdd(this._settings.pager, {
			   caption: '', 
			   buttonicon:'ui-icon-trash', 
			   onClickButton: $.proxy(this._onAddClick, this),
			   position:"last"
			})
		}
	},
	
	_getGridSettings: function(){
		return {};
	},
	
	/**
	 * Если данные функц. будут реализованы, то в гриде появятся соответ. кнопачки. 
	 */
	_onAddClick: null,
	_onEditClick: null,
	_onRemoveClick: null,
});