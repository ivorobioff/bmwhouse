Views.Abstract.Grid = Views.Abstract.View.extend({
	
	_grid: null,
		
	_default_settings: {
		datatype: 'json',
		mtype: 'GET',
		sortname: 'id',
	    sortorder: 'desc',
	    viewrecords: true,
	    gridview: false,
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
		
		this._initGridEvents(settings);
		
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
			this._addButton(this._onAddClick, 'ui-icon-plus');
		}
		
		if (typeof this._onEditClick == 'function'){
			this._addButton(this._onEditClick, 'ui-icon-pencil');
		}
		
		if (typeof this._onRemoveClick == 'function'){
			this._addButton(this._onRemoveClick, 'ui-icon-trash');
		}
		
		if (typeof this._onApplyClick == 'function'){
			this._addButton(this._onApplyClick, 'ui-icon-check');
		}
		
		if (typeof this._onCancelClick == 'function'){
			this._addButton(this._onCancelClick, 'ui-icon-cancel');
		}
	},
	
	_addButton: function(func, icon){
		this._grid.navButtonAdd(this._settings.pager, {
		   caption: '', 
		   buttonicon: icon, 
		   onClickButton: $.proxy(func, this),
		   position:"last"
		});
	},
	
	_initGridEvents: function(settings){
		settings.afterInsertRow = $.proxy(this._afterInsertRow, this);
	},
	
	_getGridSettings: function(){
		return {};
	},
	
	/**
	 * Делигация стандартных событий грида
	 */
	_afterInsertRow: function(){},
	
	/**
	 * Если данные функц. будут реализованы, то в гриде появятся соответ. кнопачки. 
	 */
	_onAddClick: null,
	_onEditClick: null,
	_onRemoveClick: null,
	_onApplyClick: null,
	_onCancelClick: null,
});