$(function(){
	Views.Admin.ModulesGrid = Views.Abstract.Grid.extend({
		
		el: $('#default-grid'),
		
		_getGridSettings: function(){
			return {
				url: Resources.modules_list,
			    colModel :[          
			      {
			    	  name: 'counter', 
			    	  label: '#',
			    	  index: 'id', 
			    	  width: 10, 
			    	  resizable: false, 
			    	  align: 'center',
			    	  sortable: false,
			      }, 
			      
			      {
			    	  name: 'pin',
			    	  label: 'Pin',
			    	  index: 'pin', 
			    	  width: 10, 
			    	  resizable: false, 
			    	  align: 'center', 
			    	  sortable: false,
			    	  formatter: $.proxy(this._formatPin, this),
			    	  formatoptions: {disabled: false}
			      },
			      
			      {
			    	  name: 'title',
			    	  label: 'Title',
			    	  index: 'title',
			    	  sortable: false
			      }, 
			      {
			    	  name: 'description', 
			    	  label: 'Description',
			    	  index: 'description', 
			    	  sortable: false
			      },
			    ],
			    caption: 'Доступные модули',
			    multiselect: true,
			  }
		},
		
		
		_formatPin: function(value, opt, row){
			var checked = '';
			var disabled = '';
			
			if (typeof value != 'undefined'){
				checked = 'checked="checked"';
			}
			
			if (typeof row.guid == 'undefined' || trim(row.guid) == ''){
				disabled = 'disabled="disabled"';
			}
			
			return '<input type="checkbox" ' + checked + ' ' + disabled + '/>';
		},
		
		_afterInsertRow: function(id, row, data){
			if (typeof data.guid != 'undefined' && trim(data.guid) != ''){
				this._grid.setSelection(id);
			}
		},
    	  
		_onApplyClick: function(){
			var ids = this._grid.getGridParam('selarrrow');
		},
		
		_onCancelClick: function(){
		
		}
	});
});