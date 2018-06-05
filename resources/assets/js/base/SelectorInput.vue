<template>
	<div>
		<label class="select-label" v-if="!hideLabel">
			<span v-text="$options.filters.trans(label)"></span>
			<span v-if="required" class="text-danger">*</span>
		</label>
		<div class="control" :class="canClearCss">
			<v-select :multiple="multiple" :options="potentialData" :value="this.defaultData" @input="updateValue" :name="name" :placeholder="$options.filters.trans(placeholder)" :closeOnSelect="!multiple || true">
			</v-select>
		</div>
		<span class="text-danger" v-if="error" v-text="error"></span>
	</div>
</template>

<script>
	import vSelect from 'vue-select';
	export default {
		props: ['potentialData', 'label', 'defaultData', 'error', 'name', 'placeholder', 'required', 'multiple', 'unclearable', 'hideLabel'],

		components: { vSelect },

		data() {
			return {
				
			};
		},

		methods: {
			updateValue(value) {
				this.$emit('input', value);
			}
		},	

		computed: {
			canClearCss() {
				return this.unclearable ? "unclearable" : "";
			}
		}

	}
</script>