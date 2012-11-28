$(function(){
	Views.Admin.ModulesGrid = Views.Abstract.Grid.extend({
		
		el: $('#default-grid'),
		
		_getGridSettings: function(){
			return {
				url: Resources.modules_list,
			    colModel :[ 
			      {
			    	  name: 'id', 
			    	  label: 'ID',
			    	  index: 'id', 
			    	  width: 20, 
			    	  resizable: false, 
			    	  align: 'center'
			      }, 
			      
			      {
			    	  name: 'pin',
			    	  label: 'Pin',
			    	  index: 'pin', 
			    	  width: 10, 
			    	  resizable: false, 
			    	  align: 'center', 
			    	  sortable: false,
			    	  formatter: 'checkbox',
			    	  formatoptions: {disabled: false}
			      },
			      
			      {
			    	  name: 'title',
			    	  label: 'Title',
			    	  index: 'title'
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
		}
	});
});