<template>
	<div class="form-group">
		<label v-if="!hideLabel ":for="name" :class="labelClass">{{ label }} <span v-if="required && editable" class="text-danger">*</span></label>
		<input :name="name" 
				:id="name" 
				class="form-control" 
				:type="type || 'text'" 
				:value="value" 
				@input="updateValue($event.target.value)"
				v-if="editable"
				ref="input" />
		<p class="input__field input__field--hoshi"
				v-html="value"
				v-else>	
		</p>
	</div>
</template>

<script>
	import moment from 'moment';
	export default {
		props: ['defaultValue', 'label', 'required', 'error', 'name', 'type', 'editable', 'focus', 'hideLabel'],
		data() {
			return {
				localValue: ''
			};
		},

		mounted() {
			if(this.focus && this.$refs.input)
			{
				this.$refs.input.focus();
			}
		},

		methods: {
			updateValue(value) {
				this.localValue = value;
				this.$emit('input', value);
			}
		},

		computed: {
			value() {
				if(this.defaultValue === undefined)
				{
					return this.localValue;
				}

				return !this.editable && this.type == 'date' ? this.$options.filters.date(this.defaultValue) : this.defaultValue;
			},

			labelClass() {
				return this.editable ? '' : 'label-small';
			}
		},

		filters: {
			ago: function(value) {
				if(!this.editable && this.type == 'date')
					return moment(value).fromNow();
				return value;
			}
		}
	}
</script>