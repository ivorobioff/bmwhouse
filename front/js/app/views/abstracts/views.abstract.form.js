Views.Abstract.From = Views.Abstract.View.extend({
	events: {
		'click [type=submit]': function(){
			var data = this.$el.dataForSubmit();
			this._onSubmit(data);
		}
	},
	
	enableUI: function(){
		this.$el.find('[type=submit]').removeAttr('disabled');
	},
	
	disableUI: function(){
		this.$el.find('[type=submit]').attr('disabled', 'disabled');
	},
	
	_onSubmit: function(data){
		
	},
});